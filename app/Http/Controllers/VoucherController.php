<?php

namespace App\Http\Controllers;

use App\Models\Voucher;
use App\Jobs\SendPromoNotification;
use Illuminate\Http\Request;

class VoucherController extends Controller
{
    public function index()
    {
        $vouchers = Voucher::latest()->get();
        return view('dashboard.vouchers.index', compact('vouchers'));
    }

    public function create()
    {
        return view('dashboard.vouchers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|unique:vouchers',
            'amount' => 'required|numeric',
            'type' => 'required|in:fixed,percent'
        ]);

        $voucher = Voucher::create($request->except('send_notification'));

        // Send email notification if checkbox is checked
        if ($request->has('send_notification')) {
            SendPromoNotification::dispatch('voucher', [
                'code' => $voucher->code,
                'type' => $voucher->type,
                'amount' => $voucher->amount,
                'min_purchase' => $voucher->min_purchase ?? 0,
            ]);
        }

        return redirect()->route('vouchers.index')->with('success', 'Voucher berhasil ditambahkan');
    }

    public function edit(Voucher $voucher)
    {
        return view('dashboard.vouchers.edit', compact('voucher'));
    }

    public function update(Request $request, Voucher $voucher)
    {
        $request->validate([
            'code' => 'required|unique:vouchers,code,' . $voucher->id,
            'amount' => 'required|numeric',
            'type' => 'required|in:fixed,percent'
        ]);

        $voucher->update($request->all());
        return redirect()->route('vouchers.index')->with('success', 'Voucher berhasil diperbarui');
    }

    public function destroy(Voucher $voucher)
    {
        $voucher->delete();
        return redirect()->route('vouchers.index')->with('success', 'Voucher berhasil dihapus');
    }

    public function check(Request $request)
    {
        $request->validate([
            'code' => 'required',
            'total' => 'required|numeric'
        ]);

        $voucher = Voucher::where('code', $request->code)->where('is_active', true)->first();

        if (!$voucher) {
            return response()->json(['valid' => false, 'message' => 'Kode voucher tidak valid atau tidak aktif.']);
        }

        if ($request->total < $voucher->min_purchase) {
            return response()->json([
                'valid' => false, 
                'message' => 'Minimal pembelian untuk voucher ini adalah Rp ' . number_format($voucher->min_purchase, 0, ',', '.')
            ]);
        }

        $discount = 0;
        if ($voucher->type == 'fixed') {
            $discount = $voucher->amount;
        } else {
            $discount = ($voucher->amount / 100) * $request->total;
        }

        // Ensure discount doesn't exceed total
        $discount = min($discount, $request->total);
        $newTotal = $request->total - $discount;

        return response()->json([
            'valid' => true,
            'discount_amount' => $discount,
            'new_total' => $newTotal,
            'code' => $voucher->code
        ]);
    }
}
