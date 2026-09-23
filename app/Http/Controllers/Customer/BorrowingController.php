<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Controllers\NotificationController;
use App\Models\Borrowing;
use App\Models\Item;
use App\Models\Review;
use App\Models\User;
use App\Services\ActivityLogService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BorrowingController extends Controller
{
    public function index()
    {
        $borrowings = Borrowing::where('user_id', Auth::id())
            ->whereIn('status', ['Menunggu', 'Disetujui', 'Dipinjam', 'Menunggu Verifikasi'])
            ->with(['item', 'returnRecord', 'review'])
            ->latest()
            ->get();

        return view('customer.borrowings.index', compact('borrowings'));
    }

    public function history(Request $request)
    {
        $query = Borrowing::where('user_id', Auth::id())
            ->whereIn('status', ['Selesai', 'Ditolak', 'Dibatalkan'])
            ->with(['item', 'returnRecord', 'review']);

        if ($request->filled('search')) {
            $query->where('borrow_code', 'like', '%' . $request->search . '%')
                  ->orWhereHas('item', function($q) use ($request) {
                      $q->where('name', 'like', '%' . $request->search . '%');
                  });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $borrowings = $query->latest()->paginate(10);

        return view('customer.borrowings.history', compact('borrowings'));
    }

    public function create(Item $item)
    {
        $activeBorrowingsCount = Borrowing::where('user_id', Auth::id())
            ->whereIn('status', ['Menunggu', 'Disetujui', 'Dipinjam', 'Menunggu Verifikasi'])
            ->count();

        if ($activeBorrowingsCount >= 2) {
            return redirect()->route('customer.borrowings.index')
                ->with('error', '⚠️ Batas Maksimal Peminjaman Tercapai! Anda saat ini sedang memiliki ' . $activeBorrowingsCount . ' barang yang aktif dipinjam / diajukan. Silakan kembalikan salah satu barang terlebih dahulu untuk mengajukan peminjaman baru.')
                ->with('max_limit_alert', true);
        }

        return view('customer.borrowings.create', compact('item'));
    }

    public function store(Request $request)
    {
        $activeBorrowingsCount = Borrowing::where('user_id', Auth::id())
            ->whereIn('status', ['Menunggu', 'Disetujui', 'Dipinjam', 'Menunggu Verifikasi'])
            ->count();

        if ($activeBorrowingsCount >= 2) {
            return redirect()->route('customer.borrowings.index')
                ->with('error', '⚠️ Batas Maksimal Peminjaman Tercapai! Anda saat ini sedang memiliki ' . $activeBorrowingsCount . ' barang yang aktif dipinjam / diajukan. Silakan kembalikan salah satu barang terlebih dahulu untuk mengajukan peminjaman baru.')
                ->with('max_limit_alert', true);
        }

        $hasExistingIdCard = !empty(Auth::user()->id_card_image);

        $validated = $request->validate([
            'item_id' => 'required|exists:items,id',
            'quantity' => 'required|integer|min:1',
            'borrow_date' => 'required|date|after_or_equal:today',
            'return_date' => 'required|date|after:borrow_date',
            'location' => 'required|string|max:255',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'purpose' => 'required|string|max:255',
            'notes' => 'nullable|string',
            'id_card_image' => $hasExistingIdCard 
                ? 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072' 
                : 'required|image|mimes:jpeg,png,jpg,webp|max:3072',
            'payment_proof' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
        ], [
            'location.required' => 'Lokasi penggunaan barang wajib diisi (bisa deteksi live GPS otomatis).',
            'id_card_image.required' => 'Foto KTP / KK asli wajib diunggah sebagai dokumen jaminan peminjaman.',
            'id_card_image.image' => 'File KTP / KK harus berformat gambar (JPG, JPEG, PNG, atau WEBP).',
            'id_card_image.max' => 'Ukuran foto KTP / KK maksimal 3 MB.',
            'payment_proof.image' => 'File bukti pembayaran harus berupa gambar (JPG, JPEG, PNG, atau WEBP).',
            'payment_proof.mimes' => 'Format bukti pembayaran harus berjenis gambar: JPG, JPEG, PNG, atau WEBP (file PDF tidak diizinkan).',
            'payment_proof.max' => 'Ukuran foto bukti pembayaran maksimal 3 MB.',
        ]);

        $item = Item::findOrFail($validated['item_id']);

        if ($item->stock < $validated['quantity']) {
            return back()->with('error', 'Jumlah yang diminta melebihi stok yang tersedia saat ini (' . $item->stock . ' unit).');
        }

        // Simpan file foto KTP/KK
        $idCardPath = Auth::user()->id_card_image;
        if ($request->hasFile('id_card_image')) {
            $idCardPath = $request->file('id_card_image')->store('ktp', 'public');
            Auth::user()->update(['id_card_image' => $idCardPath]);
        }

        $startDate = Carbon::parse($validated['borrow_date']);
        $endDate = Carbon::parse($validated['return_date']);
        $durationDays = max(1, $startDate->diffInDays($endDate));

        if ($durationDays > 10) {
            return back()->with('error', '⚠️ Batas Durasi Peminjaman Terlampaui! Maksimal durasi peminjaman barang adalah 10 hari.')->withInput();
        }

        $totalPrice = $item->price_per_day * $validated['quantity'] * $durationDays;

        $borrowCode = 'PMJ-' . date('Ymd') . '-' . str_pad(Borrowing::count() + 1, 4, '0', STR_PAD_LEFT);

        // Alur Pembayaran Peminjaman
        $paymentMethod = $request->input('payment_method', 'Belum Dipilih');
        $paymentProof = null;
        $paymentStatus = 'Belum Bayar';

        if ($totalPrice <= 0) {
            $paymentMethod = 'Gratis';
            $paymentStatus = 'Lunas';
        } elseif ($request->hasFile('payment_proof')) {
            $paymentProof = $request->file('payment_proof')->store('payments', 'public');
            $paymentStatus = 'Menunggu Verifikasi';
        } elseif ($paymentMethod === 'Tunai') {
            $paymentStatus = 'Belum Bayar';
        }

        $borrowing = Borrowing::create([
            'borrow_code' => $borrowCode,
            'user_id' => Auth::id(),
            'item_id' => $item->id,
            'quantity' => $validated['quantity'],
            'borrow_date' => $validated['borrow_date'],
            'return_date' => $validated['return_date'],
            'duration_days' => $durationDays,
            'total_price' => $totalPrice,
            'purpose' => $validated['purpose'],
            'location' => $validated['location'],
            'latitude' => $request->latitude ?? null,
            'longitude' => $request->longitude ?? null,
            'notes' => $validated['notes'] ?? null,
            'id_card_image' => $idCardPath,
            'status' => 'Menunggu',
            'payment_method' => $paymentMethod,
            'payment_proof' => $paymentProof,
            'payment_status' => $paymentStatus,
        ]);

        // Notify Admins
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            NotificationController::send(
                $admin->id,
                'Pengajuan Peminjaman Baru',
                Auth::user()->name . ' mengajukan peminjaman ' . $item->name . ' (' . $borrowCode . ') - Biaya: Rp ' . number_format($totalPrice, 0, ',', '.'),
                route('admin.borrowings.index'),
                'info'
            );
        }

        ActivityLogService::log(
            'borrowing.created',
            Auth::user()->name . ' mengajukan peminjaman ' . $item->name . ' (' . $borrowCode . ') mulai ' . $borrowing->borrow_date->format('d/m/Y') . ' s/d ' . $borrowing->return_date->format('d/m/Y') . ' (Metode Bayar: ' . $paymentMethod . ').',
            $borrowing,
            ['borrow_code' => $borrowCode, 'item' => $item->name, 'quantity' => $borrowing->quantity, 'total_price' => $totalPrice]
        );

        return redirect()->route('customer.borrowings.show', $borrowing)->with('success', 'Pengajuan peminjaman berhasil dikirim! Menunggu verifikasi admin.');
    }

    public function payBorrowing(Request $request, Borrowing $borrowing)
    {
        if ($borrowing->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'payment_method' => 'required|in:Transfer Bank,QRIS,Tunai',
            'payment_proof' => 'required_if:payment_method,Transfer Bank,QRIS|nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
            'payment_notes' => 'nullable|string|max:500',
        ], [
            'payment_proof.required_if' => 'Bukti pembayaran wajib diunggah untuk metode Transfer Bank dan QRIS.',
            'payment_proof.image' => 'File bukti pembayaran harus berupa gambar (JPG, JPEG, PNG, atau WEBP).',
            'payment_proof.mimes' => 'Format bukti pembayaran harus berjenis gambar: JPG, JPEG, PNG, atau WEBP (file PDF tidak diizinkan).',
            'payment_proof.max' => 'Ukuran foto bukti pembayaran maksimal 3 MB.',
        ]);

        $proofPath = $borrowing->payment_proof;
        if ($request->hasFile('payment_proof')) {
            $proofPath = $request->file('payment_proof')->store('payments', 'public');
        }

        $status = ($validated['payment_method'] === 'Tunai') ? 'Belum Bayar' : 'Menunggu Verifikasi';

        $borrowing->update([
            'payment_method' => $validated['payment_method'],
            'payment_proof' => $proofPath,
            'payment_status' => $status,
            'payment_notes' => $validated['payment_notes'] ?? $borrowing->payment_notes,
        ]);

        // Notify Admins
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            NotificationController::send(
                $admin->id,
                'Bukti Pembayaran Peminjaman Masuk 💳',
                Auth::user()->name . ' telah mengunggah bukti pembayaran untuk peminjaman ' . $borrowing->borrow_code . ' (Rp ' . number_format($borrowing->total_price, 0, ',', '.') . ').',
                route('admin.borrowings.index'),
                'info'
            );
        }

        ActivityLogService::log(
            'borrowing.payment_uploaded',
            Auth::user()->name . ' mengunggah bukti pembayaran ' . $validated['payment_method'] . ' untuk ' . $borrowing->borrow_code . '.',
            $borrowing,
            ['borrow_code' => $borrowing->borrow_code, 'method' => $validated['payment_method']]
        );

        return back()->with('success', 'Bukti pembayaran berhasil diunggah! Menunggu konfirmasi admin.');
    }

    public function invoice(Borrowing $borrowing)
    {
        if ($borrowing->user_id !== Auth::id() && (!Auth::check() || Auth::user()->role !== 'admin')) {
            abort(403, 'Unauthorized');
        }

        $borrowing->load(['user', 'item.category', 'returnRecord']);
        return view('customer.borrowings.invoice', compact('borrowing'));
    }

    public function show(Borrowing $borrowing)
    {
        if ($borrowing->user_id !== Auth::id()) {
            abort(403);
        }

        $borrowing->load(['item.category', 'returnRecord', 'review']);
        return view('customer.borrowings.show', compact('borrowing'));
    }

    public function requestReturn(Request $request, Borrowing $borrowing)
    {
        if ($borrowing->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        if ($borrowing->status !== 'Dipinjam') {
            return back()->with('error', 'Peminjaman ini tidak dapat dikembalikan. Status: ' . $borrowing->status);
        }

        if ($borrowing->returnRecord) {
            return back()->with('error', 'Pengembalian untuk peminjaman ini sudah diajukan sebelumnya.');
        }

        $validated = $request->validate([
            'customer_notes' => 'nullable|string|max:500',
            'return_photo' => 'nullable|image|max:3072',
        ]);

        $photoPath = null;
        if ($request->hasFile('return_photo')) {
            $photoPath = $request->file('return_photo')->store('returns', 'public');
        }

        $lastReturn = \App\Models\ReturnRecord::latest('id')->first();
        $nextId = $lastReturn ? $lastReturn->id + 1 : 1;
        $returnCode = 'RTN-' . date('Ymd') . '-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);

        while (\App\Models\ReturnRecord::where('return_code', $returnCode)->exists()) {
            $nextId++;
            $returnCode = 'RTN-' . date('Ymd') . '-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);
        }

        $borrowing->returnRecord()->create([
            'return_code' => $returnCode,
            'return_date' => now()->toDateString(),
            'customer_notes' => $validated['customer_notes'] ?? null,
            'return_photo' => $photoPath,
        ]);

        $borrowing->update([
            'status' => 'Menunggu Verifikasi',
        ]);

        // Notify Admins
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            NotificationController::send(
                $admin->id,
                'Pengembalian Perlu Verifikasi',
                Auth::user()->name . ' telah mengembalikan ' . $borrowing->item->name . ' (' . $borrowing->borrow_code . ')',
                route('admin.returns.index'),
                'warning'
            );
        }

        ActivityLogService::log(
            'borrowing.return_requested',
            Auth::user()->name . ' mengajukan pengembalian ' . $borrowing->item->name . ' (' . $borrowing->borrow_code . ').',
            $borrowing,
            ['borrow_code' => $borrowing->borrow_code, 'item' => $borrowing->item->name]
        );

        return redirect()->route('customer.borrowings.index')
            ->with('success', 'Pengajuan pengembalian berhasil! Admin akan memeriksa kondisi fisik barang: ' . $borrowing->item->name);
    }

    public function requestExtension(Request $request, Borrowing $borrowing)
    {
        if ($borrowing->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        if (!$borrowing->canRequestExtension()) {
            return back()->with('error', 'Perpanjangan tidak dapat diajukan untuk peminjaman ini.');
        }

        $validated = $request->validate([
            'extension_date' => 'required|date|after:' . $borrowing->return_date->toDateString(),
            'extension_reason' => 'required|string|max:500',
        ]);

        $currentReturnDate = Carbon::parse($borrowing->return_date);
        $extensionDate = Carbon::parse($validated['extension_date']);
        $extraDays = max(1, $currentReturnDate->diffInDays($extensionDate));

        if ($extraDays > 3) {
            return back()->with('error', '⚠️ Batas Perpanjangan Terlampaui! Perpanjangan waktu maksimal adalah 3 hari dari tanggal pengembalian sebelumnya.')->withInput();
        }

        $borrowing->update([
            'extension_date' => $validated['extension_date'],
            'extension_reason' => $validated['extension_reason'],
            'extension_status' => 'Pending',
        ]);

        // Notify Admins
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            NotificationController::send(
                $admin->id,
                'Permohonan Perpanjangan Waktu',
                Auth::user()->name . ' meminta perpanjangan pinjam ' . $borrowing->item->name . ' s/d ' . Carbon::parse($validated['extension_date'])->format('d/m/Y'),
                route('admin.borrowings.index'),
                'info'
            );
        }

        ActivityLogService::log(
            'borrowing.extension_requested',
            Auth::user()->name . ' meminta perpanjangan ' . $borrowing->borrow_code . ' (' . $borrowing->item->name . ') hingga ' . Carbon::parse($validated['extension_date'])->format('d/m/Y') . '.',
            $borrowing,
            ['borrow_code' => $borrowing->borrow_code, 'extension_date' => $validated['extension_date']]
        );

        return redirect()->route('customer.borrowings.show', $borrowing)
            ->with('success', 'Permohonan perpanjangan peminjaman berhasil dikirim! Menunggu persetujuan admin.');
    }

    public function submitReview(Request $request, Borrowing $borrowing)
    {
        if ($borrowing->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        if ($borrowing->status !== 'Selesai') {
            return back()->with('error', 'Hanya peminjaman yang sudah selesai yang dapat diberi ulasan.');
        }

        if ($borrowing->review) {
            return back()->with('error', 'Anda sudah memberikan ulasan untuk peminjaman ini.');
        }

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        Review::create([
            'borrowing_id' => $borrowing->id,
            'user_id' => Auth::id(),
            'item_id' => $borrowing->item_id,
            'rating' => $validated['rating'],
            'comment' => $validated['comment'] ?? null,
        ]);

        return redirect()->route('customer.borrowings.show', $borrowing)
            ->with('success', 'Terima kasih atas ulasan dan rating Anda!');
    }

    public function payFine(Request $request, Borrowing $borrowing)
    {
        if ($borrowing->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $return = $borrowing->returnRecord;
        if (!$return || $return->fine_amount <= 0) {
            return back()->with('error', 'Tidak ada tagihan denda untuk peminjaman ini.');
        }

        $validated = $request->validate([
            'fine_payment_method' => 'required|in:Transfer,Cash',
            'fine_payment_proof'  => 'required_if:fine_payment_method,Transfer|nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
            'fine_payment_notes'  => 'nullable|string|max:500',
        ], [
            'fine_payment_method.required' => 'Pilih metode pembayaran (Transfer / Cash).',
            'fine_payment_proof.required_if' => 'Foto bukti transfer wajib diunggah untuk metode Pembayaran Transfer.',
            'fine_payment_proof.image' => 'File bukti transfer harus berupa gambar (JPG, JPEG, PNG, atau WEBP).',
            'fine_payment_proof.mimes' => 'Format bukti transfer denda harus berjenis gambar: JPG, JPEG, PNG, atau WEBP (file PDF tidak diizinkan).',
            'fine_payment_proof.max' => 'Ukuran foto bukti transfer denda maksimal 3 MB.',
        ]);

        $proofPath = $return->fine_payment_proof;
        if ($request->hasFile('fine_payment_proof')) {
            $proofPath = $request->file('fine_payment_proof')->store('fine_proofs', 'public');
        }

        $return->update([
            'fine_payment_method' => $validated['fine_payment_method'],
            'fine_payment_proof'  => $proofPath,
            'fine_payment_status' => 'Menunggu Verifikasi',
            'fine_payment_notes'  => $validated['fine_payment_notes'] ?? null,
        ]);

        // Notify Admins
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            NotificationController::send(
                $admin->id,
                'Pembayaran Denda Perlu Verifikasi 💳',
                Auth::user()->name . ' melakukan pembayaran denda (' . $validated['fine_payment_method'] . ') Rp ' . number_format($return->fine_amount, 0, ',', '.') . ' (' . $borrowing->borrow_code . ')',
                route('admin.returns.index'),
                'warning'
            );
        }

        ActivityLogService::log(
            'fine.payment_submitted',
            Auth::user()->name . ' mengajukan pembayaran denda metode ' . $validated['fine_payment_method'] . ' sebesar Rp ' . number_format($return->fine_amount, 0, ',', '.') . ' (' . $borrowing->borrow_code . ').',
            $return,
            ['borrow_code' => $borrowing->borrow_code, 'method' => $validated['fine_payment_method'], 'amount' => $return->fine_amount]
        );

        $msg = $validated['fine_payment_method'] === 'Transfer' 
            ? 'Bukti transfer berhasil dikirim! Menunggu konfirmasi verifikasi admin.' 
            : 'Konfirmasi bayar cash berhasil diajukan! Silakan serahkan uang tunai ke kasir/petugas gudang saat pengembalian.';

        return redirect()->route('customer.borrowings.show', $borrowing)->with('success', $msg);
    }
}
