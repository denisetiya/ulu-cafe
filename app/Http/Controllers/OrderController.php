<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\CartItem;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Midtrans\Config;
use Midtrans\CoreApi;
use Midtrans\Transaction;

class OrderController extends Controller
{
    public function __construct()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');
        
        // Disable SSL Verification for Local Development to avoid cURL 60 error
        // And provide empty CURLOPT_HTTPHEADER to avoid Midtrans SDK Bug (Undefined array key 10023)
        Config::$curlOptions = [
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_HTTPHEADER => [] 
        ];
    }

    public function checkout()
    {
        $cart = [];
        $total = 0;

        if (Auth::check()) {
            $dbCart = CartItem::with('product')->where('user_id', Auth::id())->get();
            foreach ($dbCart as $item) {
                $cart[$item->product_id] = [
                    "id" => $item->product_id,
                    "name" => $item->product->name,
                    "quantity" => $item->quantity,
                    "price" => $item->product->final_price,
                    "image" => $item->product->image
                ];
                $total += $item->product->final_price * $item->quantity;
            }
        } else {
            $cart = Session::get('cart', []);
            foreach ($cart as $item) {
                $total += $item['price'] * $item['quantity'];
            }
        }

        if (empty($cart)) return redirect()->route('home');
        
        $grandTotal = $total; // No shipping fee for dine-in
        
        return view('checkout.index', compact('cart', 'total', 'grandTotal'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'required',
            'table_number' => 'required',
            'payment_method' => 'required',
        ]);

        $cart = [];
        $total = 0;

        if (Auth::check()) {
            $dbCart = CartItem::with('product')->where('user_id', Auth::id())->get();
            foreach ($dbCart as $item) {
                $cart[$item->product_id] = [
                    "id" => $item->product_id,
                    "name" => $item->product->name,
                    "quantity" => $item->quantity,
                    "price" => $item->product->final_price,
                ];
                $total += $item->product->final_price * $item->quantity;
            }
        } else {
            $cart = Session::get('cart', []);
            foreach ($cart as $item) {
                $total += $item['price'] * $item['quantity'];
            }
        }

        if (empty($cart)) {
            if ($request->wantsJson()) {
                return response()->json(['error' => 'Keranjang kosong'], 400);
            }
            return redirect()->route('home');
        }

        // Voucher Logic
        $discountAmount = 0;
        $voucherCode = null;

        if ($request->voucher_code) {
            $voucher = Voucher::where('code', $request->voucher_code)->where('is_active', true)->first();
            if ($voucher && $total >= $voucher->min_purchase) {
                $voucherCode = $voucher->code;
                if ($voucher->type == 'fixed') {
                    $discountAmount = $voucher->amount;
                } else {
                    $discountAmount = ($voucher->amount / 100) * $total;
                }
                $discountAmount = min($discountAmount, $total);
            }
        }

        $finalTotal = $total - $discountAmount;
        
        // Add Payment Surcharge
        $surcharge = 0;
        $surchargeLabel = '';
        if ($request->payment_method == 'qris') {
            $surcharge = ceil($finalTotal * 0.007);
            $surchargeLabel = 'QRIS';
        } elseif (str_starts_with($request->payment_method, 'bank_transfer_')) {
            $surcharge = 4000; // Biaya admin VA
            $surchargeLabel = 'VA';
        }
        $finalTotal += $surcharge;

        $notes = $request->notes;
        if($surcharge > 0) {
            $notes .= " (Termasuk Biaya " . $surchargeLabel . ": Rp " . number_format($surcharge, 0, ',', '.') . ")";
        }
        
        $order = Order::create([
            'user_id' => Auth::id(),
            'customer_name' => $request->customer_name,
            'table_number' => $request->table_number,
            'customer_address' => null, // Dine-in
            'customer_phone' => $request->customer_phone,
            'total_amount' => $finalTotal,
            'status' => 'pending',
            'payment_status' => 'unpaid',
            'notes' => $notes,
            'voucher_code' => $voucherCode,
            'discount_amount' => $discountAmount
        ]);

        foreach($cart as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['id'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'options' => null
            ]);
        }

        // Midtrans Core API Logic
        $midtransOrderId = $order->id . '-' . time() . '-' . rand(1000, 9999);
        
        $params = [
            'transaction_details' => [
                'order_id' => $midtransOrderId, 
                'gross_amount' => (int) $order->total_amount,
            ],
            'customer_details' => [
                'first_name' => $request->customer_name,
                'email' => Auth::check() ? Auth::user()->email : 'guest@ulucafe.com',
            ],
            'expiry' => [
                'unit' => 'minutes',
                'duration' => 120 
            ],
        ];

        $paymentMethod = $request->payment_method;
        
        if ($paymentMethod == 'qris') {
            $params['payment_type'] = 'qris';
        } elseif (str_starts_with($paymentMethod, 'bank_transfer_')) {
            $params['payment_type'] = 'bank_transfer';
            $bank = str_replace('bank_transfer_', '', $paymentMethod);
            $params['bank_transfer'] = [
                'bank' => $bank
            ];
        }

        try {
            $response = CoreApi::charge($params);
            
            $paymentInfo = [];
            if ($paymentMethod == 'qris') {
                // Usually qr_string is available in actions or directly
                // For simple integration we check both
                $paymentInfo['type'] = 'qris';
                $paymentInfo['midtrans_order_id'] = $midtransOrderId;
                // In Core API response for QRIS, typically 'actions' has 'generate-qr-code' URL
                // Or sometimes 'qr_string' is top level.
                // Let's capture the whole response for debugging if needed, but specifically look for qr string
                $paymentInfo['qr_string'] = $response->qr_string ?? null;
                if(isset($response->actions)){
                    foreach($response->actions as $action){
                        if($action->name == 'generate-qr-code'){
                            $paymentInfo['qr_url'] = $action->url;
                        }
                    }
                }
            } elseif (str_starts_with($paymentMethod, 'bank_transfer_')) {
                $paymentInfo['type'] = 'bank_transfer';
                $paymentInfo['midtrans_order_id'] = $midtransOrderId;
                $paymentInfo['bank'] = $params['bank_transfer']['bank'];
                $paymentInfo['va_number'] = $response->va_numbers[0]->va_number ?? null;
            }

            $order->payment_type = $paymentMethod;
            $order->payment_info = json_encode($paymentInfo);
            $order->save();

            // Clear Cart
            if (Auth::check()) {
                CartItem::where('user_id', Auth::id())->delete();
            } else {
                Session::forget('cart');
            }

            return redirect()->route('order.payment', $order->id);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memproses pembayaran: ' . $e->getMessage());
        }
    }
    
    public function payment($id)
    {
        $order = Order::findOrFail($id);
        return view('order.payment', compact('order'));
    }

    public function success($id)
    {
        $order = Order::findOrFail($id);
        
        $paymentInfo = json_decode($order->payment_info, true);
        $midtransOrderId = $paymentInfo['midtrans_order_id'] ?? null;

        if ($midtransOrderId && $order->payment_status == 'unpaid') {
            try {
                $status = Transaction::status($midtransOrderId);
                $transactionStatus = $status->transaction_status;
                $fraudStatus = $status->fraud_status ?? null;

                if ($transactionStatus == 'capture') {
                    if ($fraudStatus == 'challenge') {
                        // challenge
                    } else if ($fraudStatus == 'accept') {
                        $order->payment_status = 'paid';
                        $order->save();
                    }
                } else if ($transactionStatus == 'settlement') {
                    $order->payment_status = 'paid';
                    $order->save();
                    \App\Events\OrderUpdate::dispatch('Order #' . $order->id . ' baru saja masuk!');
                } else if ($transactionStatus == 'cancel' || $transactionStatus == 'deny' || $transactionStatus == 'expire') {
                    $order->payment_status = 'failed';
                    $order->save();
                }
            } catch (\Exception $e) {
                // Ignore error
            }
        }
        
        if($order->payment_status == 'paid'){
            return view('order.success', compact('order'));
        } else {
            return redirect()->route('order.payment', $order->id)->with('error', 'Pembayaran belum dikonfirmasi oleh sistem. Silakan refresh beberapa saat lagi.');
        }
    }

    public function history()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $orders = Order::with('items.product')
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();
        return view('order.history', compact('orders'));
    }
}
