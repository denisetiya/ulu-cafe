<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Withdrawal;
use App\Services\IrisService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OwnerController extends Controller
{
    protected $irisService;

    public function __construct(IrisService $irisService)
    {
        $this->irisService = $irisService;
    }

    public function index()
    {
        try {
            // Stats
            $todaySales = Order::where('payment_status', 'paid')
                ->whereDate('created_at', today())
                ->sum('total_amount');

            $monthlySales = Order::where('payment_status', 'paid')
                ->whereMonth('created_at', now()->month)
                ->sum('total_amount');

            $totalOrders = Order::where('payment_status', 'paid')->count();

            // Best selling products
            $bestSellers = DB::table('order_items')
                ->join('products', 'order_items.product_id', '=', 'products.id')
                ->join('orders', 'order_items.order_id', '=', 'orders.id')
                ->where('orders.payment_status', 'paid')
                ->select('products.name', DB::raw('SUM(order_items.quantity) as total_sold'))
                ->groupBy('products.id', 'products.name')
                ->orderByDesc('total_sold')
                ->take(5)
                ->get();

            // Iris Balance - with error handling
            $irisBalance = 0;
            try {
                $balance = $this->irisService->getBalance();
                $irisBalance = $balance['balance'] ?? 0;
            } catch (\Exception $e) {
                \Log::warning('Iris balance fetch failed: ' . $e->getMessage());
            }

            // Banks list
            $banks = $this->irisService->getBanks();

            // Withdrawal history - with error handling
            $withdrawals = collect();
            try {
                $withdrawals = Withdrawal::latest()->take(10)->get();
            } catch (\Exception $e) {
                \Log::warning('Withdrawal fetch failed: ' . $e->getMessage());
            }

            return view('owner.index', compact(
                'todaySales', 
                'monthlySales', 
                'totalOrders', 
                'bestSellers',
                'irisBalance',
                'banks',
                'withdrawals'
            ));
        } catch (\Exception $e) {
            \Log::error('Owner Dashboard Error: ' . $e->getMessage());
            return view('owner.index', [
                'todaySales' => 0,
                'monthlySales' => 0,
                'totalOrders' => 0,
                'bestSellers' => collect(),
                'irisBalance' => 0,
                'banks' => [],
                'withdrawals' => collect(),
                'error' => 'Terjadi kesalahan saat memuat data: ' . $e->getMessage(),
            ]);
        }
    }

    public function processWithdraw(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:10000',
            'bank_code' => 'required|string',
            'account_number' => 'required|string',
            'account_name' => 'required|string',
            'notes' => 'nullable|string|max:255',
        ]);

        // Get bank name from code
        $banks = $this->irisService->getBanks();
        $bank = collect($banks)->firstWhere('code', $request->bank_code);
        $bankName = $bank['name'] ?? $request->bank_code;

        // Create withdrawal record
        $withdrawal = Withdrawal::create([
            'amount' => $request->amount,
            'bank_code' => $request->bank_code,
            'bank_name' => $bankName,
            'account_number' => $request->account_number,
            'account_name' => $request->account_name,
            'notes' => $request->notes,
            'status' => 'processing',
        ]);

        // Process payout via Iris
        $result = $this->irisService->createPayout([
            'amount' => $request->amount,
            'bank_code' => $request->bank_code,
            'account_number' => $request->account_number,
            'account_name' => $request->account_name,
            'notes' => $request->notes ?? 'Withdraw from Ulu Coffee',
        ]);

        if ($result['success']) {
            $withdrawal->update([
                'status' => 'success',
                'iris_reference' => $result['reference'],
            ]);

            return redirect()->route('owner.dashboard')
                ->with('success', 'Withdraw berhasil diproses! Ref: ' . $result['reference']);
        } else {
            $withdrawal->update([
                'status' => 'failed',
                'error_message' => $result['error'],
            ]);

            return redirect()->route('owner.dashboard')
                ->with('error', 'Withdraw gagal: ' . $result['error']);
        }
    }
}
