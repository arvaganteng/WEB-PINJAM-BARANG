<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\NotificationController;
use App\Models\ReturnRecord;
use App\Models\Borrowing;
use App\Services\ActivityLogService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReturnController extends Controller
{
    public function index()
    {
        $pendingReturns = ReturnRecord::with(['borrowing.user', 'borrowing.item'])
            ->whereNull('verified_at')
            ->latest()
            ->get();
            
        $verifiedReturns = ReturnRecord::with(['borrowing.user', 'borrowing.item', 'verifiedBy'])
            ->whereNotNull('verified_at')
            ->latest()
            ->paginate(10);

        return view('admin.returns.index', compact('pendingReturns', 'verifiedReturns'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'return_id' => 'required|exists:returns,id',
            'item_condition' => 'required|in:Baik,Kurang Baik,Rusak Ringan,Rusak Berat',
            'fine_amount' => 'nullable|numeric|min:0',
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        DB::beginTransaction();
        try {
            $return = ReturnRecord::with('borrowing.item')->findOrFail($validated['return_id']);
            
            if ($return->verified_at) {
                return back()->with('error', 'Pengembalian ini sudah diverifikasi sebelumnya.');
            }

            $borrowing = $return->borrowing;
            $item = $borrowing->item;

            $fineAmount = $validated['fine_amount'] ?? 0;
            $finePaymentStatus = $fineAmount > 0 ? ($return->fine_payment_status !== 'Belum Dipilih' ? $return->fine_payment_status : 'Belum Bayar') : 'Lunas';

            $return->update([
                'item_condition' => $validated['item_condition'],
                'fine_amount' => $fineAmount,
                'fine_payment_status' => $finePaymentStatus,
                'admin_notes' => $validated['admin_notes'],
                'verified_by' => Auth::id(),
                'verified_at' => now(),
            ]);

            $borrowing->update(['status' => 'Selesai']);

            $item->increment('stock', $borrowing->quantity);

            if ($validated['item_condition'] === 'Rusak Berat') {
                $item->update([
                    'condition' => 'Rusak Berat',
                    'status' => 'Rusak',
                ]);
            } elseif ($validated['item_condition'] === 'Rusak Ringan') {
                $item->update([
                    'condition' => 'Rusak Ringan',
                    'status' => 'Tersedia',
                ]);
            } else {
                $item->update([
                    'status' => 'Tersedia',
                ]);
            }

            DB::commit();

            NotificationController::send(
                $borrowing->user_id,
                'Pengembalian Telah Diverifikasi ✅',
                'Pengembalian ' . $item->name . ' telah diverifikasi dengan kondisi: ' . $validated['item_condition'] . '. Silakan berikan ulasan & rating!',
                route('customer.borrowings.show', $borrowing),
                'success'
            );

            ActivityLogService::log(
                'return.verified',
                'Pengembalian ' . $borrowing->borrow_code . ' (' . $item->name . ') diverifikasi. Kondisi: ' . $validated['item_condition'] . ($fineAmount > 0 ? '. Denda: Rp ' . number_format($fineAmount, 0, ',', '.') : '') . '.',
                $return,
                ['borrow_code' => $borrowing->borrow_code, 'condition' => $validated['item_condition'], 'fine' => $fineAmount]
            );

            return redirect()->route('admin.returns.index')
                ->with('success', 'Pengembalian barang berhasil diverifikasi! Kondisi: ' . $validated['item_condition']);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memproses pengembalian: ' . $e->getMessage());
        }
    }

    public function confirmFinePayment(Request $request, ReturnRecord $returnRecord)
    {
        $returnRecord->update([
            'fine_payment_status' => 'Lunas',
            'admin_notes' => ($returnRecord->admin_notes ? $returnRecord->admin_notes . "\n" : '') . 'Denda Rp ' . number_format($returnRecord->fine_amount, 0, ',', '.') . ' dikonfirmasi LUNAS (' . ($returnRecord->fine_payment_method ?? 'Cash/Transfer') . ') oleh Admin.',
        ]);

        NotificationController::send(
            $returnRecord->borrowing->user_id,
            'Pembayaran Denda LUNAS ✅',
            'Pembayaran denda sebesar Rp ' . number_format($returnRecord->fine_amount, 0, ',', '.') . ' (' . ($returnRecord->fine_payment_method ?? 'Cash') . ') telah dikonfirmasi LUNAS oleh admin.',
            route('customer.borrowings.show', $returnRecord->borrowing),
            'success'
        );

        ActivityLogService::log(
            'fine.payment_confirmed',
            'Admin mengonfirmasi pelunasan denda Rp ' . number_format($returnRecord->fine_amount, 0, ',', '.') . ' (' . ($returnRecord->fine_payment_method ?? 'Cash') . ') untuk ' . $returnRecord->borrowing->borrow_code . '.',
            $returnRecord,
            ['borrow_code' => $returnRecord->borrowing->borrow_code, 'amount' => $returnRecord->fine_amount]
        );

        return back()->with('success', 'Pembayaran denda berhasil dikonfirmasi LUNAS!');
    }
}
