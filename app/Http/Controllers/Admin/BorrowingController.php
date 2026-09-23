<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\NotificationController;
use App\Models\Borrowing;
use App\Services\ActivityLogService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BorrowingController extends Controller
{
    public function index(Request $request)
    {
        $query = Borrowing::with(['user', 'item', 'returnRecord']);

        if ($request->filled('search')) {
            $query->where('borrow_code', 'like', '%' . $request->search . '%')
                  ->orWhereHas('user', function($q) use ($request) {
                      $q->where('name', 'like', '%' . $request->search . '%');
                  })
                  ->orWhereHas('item', function($q) use ($request) {
                      $q->where('name', 'like', '%' . $request->search . '%');
                  });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('extension')) {
            $query->where('extension_status', $request->extension);
        }

        if ($request->filled('date')) {
            $query->whereDate('borrow_date', $request->date);
        }

        $borrowings = $query->latest()->paginate(10);

        return view('admin.borrowings.index', compact('borrowings'));
    }

    public function approve(Borrowing $borrowing)
    {
        if ($borrowing->status !== 'Menunggu') {
            return back()->with('error', 'Peminjaman tidak dalam status menunggu.');
        }

        if ($borrowing->item->stock < $borrowing->quantity) {
            return back()->with('error', 'Stok barang tidak mencukupi untuk menyetujui peminjaman ini.');
        }

        $borrowing->status = 'Disetujui';
        $borrowing->save();

        NotificationController::send(
            $borrowing->user_id,
            'Pengajuan Peminjaman Disetujui! 🎉',
            'Pengajuan ' . $borrowing->item->name . ' (' . $borrowing->borrow_code . ') telah disetujui. Silakan ambil barang di bagian inventaris.',
            route('customer.borrowings.show', $borrowing),
            'success'
        );

        ActivityLogService::log(
            'borrowing.approved',
            'Peminjaman ' . $borrowing->borrow_code . ' (' . $borrowing->item->name . ') disetujui untuk customer ' . ($borrowing->user->name ?? '-') . '.',
            $borrowing,
            ['borrow_code' => $borrowing->borrow_code]
        );

        return redirect()->route('admin.borrowings.index')->with('success', 'Pengajuan peminjaman ' . $borrowing->borrow_code . ' berhasil disetujui!');
    }

    public function reject(Request $request, Borrowing $borrowing)
    {
        $validated = $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        $borrowing->status = 'Ditolak';
        $borrowing->rejection_reason = $validated['rejection_reason'];
        $borrowing->save();

        NotificationController::send(
            $borrowing->user_id,
            'Pengajuan Peminjaman Ditolak',
            'Pengajuan ' . $borrowing->item->name . ' (' . $borrowing->borrow_code . ') ditolak. Alasan: ' . $validated['rejection_reason'],
            route('customer.borrowings.show', $borrowing),
            'danger'
        );

        ActivityLogService::log(
            'borrowing.rejected',
            'Peminjaman ' . $borrowing->borrow_code . ' (' . $borrowing->item->name . ') ditolak. Alasan: ' . $validated['rejection_reason'],
            $borrowing,
            ['borrow_code' => $borrowing->borrow_code, 'reason' => $validated['rejection_reason']]
        );

        return redirect()->route('admin.borrowings.index')->with('success', 'Pengajuan peminjaman ' . $borrowing->borrow_code . ' telah ditolak.');
    }

    public function confirmPayment(Borrowing $borrowing)
    {
        $borrowing->update([
            'payment_status' => 'Lunas',
            'paid_at' => Carbon::now(),
        ]);

        NotificationController::send(
            $borrowing->user_id,
            'Pembayaran Sewa Dikonfirmasi Lunas! 💳✅',
            'Pembayaran sebesar Rp ' . number_format($borrowing->total_price, 0, ',', '.') . ' untuk peminjaman ' . $borrowing->item->name . ' (' . $borrowing->borrow_code . ') telah diverifikasi Lunas.',
            route('customer.borrowings.show', $borrowing),
            'success'
        );

        ActivityLogService::log(
            'borrowing.payment_confirmed',
            'Pembayaran sebesar Rp ' . number_format($borrowing->total_price, 0, ',', '.') . ' untuk ' . $borrowing->borrow_code . ' (' . ($borrowing->user->name ?? '-') . ') dikonfirmasi Lunas.',
            $borrowing,
            ['borrow_code' => $borrowing->borrow_code, 'amount' => $borrowing->total_price]
        );

        return back()->with('success', 'Pembayaran peminjaman ' . $borrowing->borrow_code . ' berhasil dikonfirmasi LUNAS.');
    }

    public function rejectPayment(Request $request, Borrowing $borrowing)
    {
        $validated = $request->validate([
            'payment_rejection_reason' => 'required|string|max:500',
        ]);

        $borrowing->update([
            'payment_status' => 'Ditolak',
            'payment_notes' => $validated['payment_rejection_reason'],
        ]);

        NotificationController::send(
            $borrowing->user_id,
            'Bukti Pembayaran Ditolak ⚠️',
            'Bukti pembayaran untuk ' . $borrowing->borrow_code . ' ditolak: ' . $validated['payment_rejection_reason'] . '. Silakan unggah bukti transfer yang valid.',
            route('customer.borrowings.show', $borrowing),
            'danger'
        );

        ActivityLogService::log(
            'borrowing.payment_rejected',
            'Bukti pembayaran untuk ' . $borrowing->borrow_code . ' ditolak. Alasan: ' . $validated['payment_rejection_reason'],
            $borrowing,
            ['borrow_code' => $borrowing->borrow_code, 'reason' => $validated['payment_rejection_reason']]
        );

        return back()->with('success', 'Bukti pembayaran telah ditolak. Notifikasi telah dikirim ke peminjam.');
    }

    public function release(Borrowing $borrowing)
    {
        if ($borrowing->status !== 'Disetujui') {
            return back()->with('error', 'Status peminjaman harus disetujui terlebih dahulu.');
        }

        $borrowing->status = 'Dipinjam';
        $borrowing->save();

        $item = $borrowing->item;
        $item->stock = max(0, $item->stock - $borrowing->quantity);
        if ($item->stock === 0) {
            $item->status = 'Dipinjam';
        }
        $item->save();

        NotificationController::send(
            $borrowing->user_id,
            'Barang Telah Diserahkan 📦',
            'Barang ' . $borrowing->item->name . ' resmi dipinjam. Harap kembalikan sebelum ' . $borrowing->return_date->format('d/m/Y') . '.',
            route('customer.borrowings.show', $borrowing),
            'info'
        );

        ActivityLogService::log(
            'borrowing.released',
            'Barang ' . $borrowing->item->name . ' diserahkan kepada customer ' . ($borrowing->user->name ?? '-') . ' (' . $borrowing->borrow_code . ').',
            $borrowing,
            ['borrow_code' => $borrowing->borrow_code, 'item' => $borrowing->item->name]
        );

        return redirect()->route('admin.borrowings.index')->with('success', 'Barang telah diserahkan. Status diubah menjadi "Dipinjam".');
    }

    public function approveExtension(Borrowing $borrowing)
    {
        if ($borrowing->extension_status !== 'Pending' || !$borrowing->extension_date) {
            return back()->with('error', 'Tidak ada permohonan perpanjangan yang menunggu persetujuan.');
        }

        $newEndDate = Carbon::parse($borrowing->extension_date);
        $startDate = $borrowing->borrow_date;
        
        $newDuration = max(1, $startDate->diffInDays($newEndDate));
        $newTotalPrice = $borrowing->item->price_per_day * $borrowing->quantity * $newDuration;

        $borrowing->update([
            'return_date' => $newEndDate,
            'duration_days' => $newDuration,
            'total_price' => $newTotalPrice,
            'extension_status' => 'Approved',
        ]);

        NotificationController::send(
            $borrowing->user_id,
            'Perpanjangan Waktu Disetujui! ✅',
            'Permohonan perpanjangan ' . $borrowing->item->name . ' disetujui hingga ' . $newEndDate->format('d/m/Y') . '.',
            route('customer.borrowings.show', $borrowing),
            'success'
        );

        ActivityLogService::log(
            'borrowing.extension_approved',
            'Perpanjangan ' . $borrowing->borrow_code . ' (' . $borrowing->item->name . ') disetujui hingga ' . $newEndDate->format('d/m/Y') . '.',
            $borrowing,
            ['borrow_code' => $borrowing->borrow_code, 'new_return_date' => $newEndDate->toDateString()]
        );

        return redirect()->route('admin.borrowings.index')->with('success', 'Perpanjangan peminjaman ' . $borrowing->borrow_code . ' berhasil disetujui!');
    }

    public function rejectExtension(Request $request, Borrowing $borrowing)
    {
        $borrowing->update([
            'extension_status' => 'Rejected',
        ]);

        NotificationController::send(
            $borrowing->user_id,
            'Perpanjangan Waktu Ditolak ⚠️',
            'Permohonan perpanjangan ' . $borrowing->item->name . ' ditolak. Harap kembalikan barang sesuai jadwal semula (' . $borrowing->return_date->format('d/m/Y') . ').',
            route('customer.borrowings.show', $borrowing),
            'warning'
        );

        ActivityLogService::log(
            'borrowing.extension_rejected',
            'Perpanjangan ' . $borrowing->borrow_code . ' (' . $borrowing->item->name . ') ditolak.',
            $borrowing,
            ['borrow_code' => $borrowing->borrow_code]
        );

        return redirect()->route('admin.borrowings.index')->with('success', 'Perpanjangan peminjaman ' . $borrowing->borrow_code . ' telah ditolak.');
    }
}
