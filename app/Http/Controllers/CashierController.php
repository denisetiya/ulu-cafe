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

        \App\Events\OrderUpdate::dispatch('Order #' . $order->id . ' status updated to ' . $request->status);

        return redirect()->back()->with('success', 'Status pesanan berhasil diperbarui.');
    }
}
