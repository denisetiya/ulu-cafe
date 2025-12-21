<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class CashierController extends Controller
{
    public function index()
    {
        // Active orders (Pending, Processing, Ready) - Oldest first
        $orders = Order::where('payment_status', 'paid')
            ->whereIn('status', ['pending', 'processing', 'ready'])
            ->with('items.product')
            ->orderBy('created_at', 'asc')
            ->get();

        return view('cashier.index', compact('orders'));
    }

    public function history()
    {
        // Completed and Cancelled orders - Newest first, paginated
        $orders = Order::where('payment_status', 'paid')
            ->whereIn('status', ['delivered', 'cancelled'])
            ->with('items.product')
            ->orderBy('updated_at', 'desc')
            ->paginate(10);

        return view('cashier.history', compact('orders'));
    }

    public function updateStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $order->status = $request->status;
        $order->save();

        // If AJAX request, return JSON
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Status pesanan berhasil diperbarui.']);
        }

        return redirect()->back()->with('success', 'Status pesanan berhasil diperbarui.');
    }

    /**
     * Get orders as JSON for AJAX polling
     */
    public function ordersJson()
    {
        $orders = Order::where('payment_status', 'paid')
            ->whereIn('status', ['pending', 'processing', 'ready'])
            ->with('items.product')
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function ($order) {
                return [
                    'id' => $order->id,
                    'table_number' => $order->table_number,
                    'customer_name' => $order->customer_name,
                    'status' => $order->status,
                    'notes' => $order->notes,
                    'created_at' => $order->created_at->diffForHumans(),
                    'updated_at' => $order->updated_at->diffForHumans(),
                    'items' => $order->items->map(function ($item) {
                        return [
                            'quantity' => $item->quantity,
                            'product_name' => $item->product ? $item->product->name : 'Menu Dihapus',
                        ];
                    }),
                ];
            });

        return response()->json([
            'orders' => $orders,
            'counts' => [
                'pending' => $orders->where('status', 'pending')->count(),
                'processing' => $orders->where('status', 'processing')->count(),
                'ready' => $orders->where('status', 'ready')->count(),
            ],
        ]);
    }
}
