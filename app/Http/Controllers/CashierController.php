<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class CashierController extends Controller
{
    public function index()
    {
        // Get orders that are paid but not delivered yet, ordered by oldest first
        $orders = Order::where('payment_status', 'paid')
            ->whereIn('status', ['pending', 'processing', 'ready'])
            ->with('items.product')
            ->orderBy('created_at', 'asc')
            ->get();

        return view('cashier.index', compact('orders'));
    }

    public function updateStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $order->status = $request->status;
        $order->save();

        return redirect()->back()->with('success', 'Status pesanan berhasil diperbarui.');
    }
}
