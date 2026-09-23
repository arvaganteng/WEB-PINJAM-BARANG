<?php

namespace Database\Seeders;

use App\Models\ActivityLog;
use App\Models\Borrowing;
use App\Models\Item;
use App\Models\Category;
use App\Models\ReturnRecord;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ActivityLogSeeder extends Seeder
{
    public function run(): void
    {
        // Hapus log lama dulu
        ActivityLog::truncate();

        $admin = User::where('role', 'admin')->first();
        $customers = User::where('role', 'customer')->get();

        // ── 1. Log Registrasi Customer ───────────────────────────────
        foreach ($customers as $customer) {
            ActivityLog::create([
                'user_id'     => $customer->id,
                'causer_name' => $customer->name,
                'causer_role' => 'customer',
                'action'      => 'auth.register',
                'description' => "Customer baru {$customer->name} mendaftarkan akun.",
                'subject_type'=> User::class,
                'subject_id'  => $customer->id,
                'properties'  => ['email' => $customer->email],
                'ip_address'  => '127.0.0.1',
                'created_at'  => $customer->created_at,
                'updated_at'  => $customer->created_at,
            ]);
        }

        // ── 2. Log Login Admin ───────────────────────────────────────
        if ($admin) {
            ActivityLog::create([
                'user_id'     => $admin->id,
                'causer_name' => $admin->name,
                'causer_role' => 'admin',
                'action'      => 'auth.login',
                'description' => "Admin {$admin->name} berhasil login ke panel admin.",
                'subject_type'=> User::class,
                'subject_id'  => $admin->id,
                'properties'  => ['email' => $admin->email],
                'ip_address'  => '127.0.0.1',
                'created_at'  => now()->subDays(1),
                'updated_at'  => now()->subDays(1),
            ]);
        }

        // ── 3. Log Penambahan Barang ─────────────────────────────────
        $items = Item::with('category')->get();
        foreach ($items as $item) {
            ActivityLog::create([
                'user_id'     => $admin?->id,
                'causer_name' => $admin?->name ?? 'Admin',
                'causer_role' => 'admin',
                'action'      => 'item.created',
                'description' => "Barang baru '{$item->name}' (kode: {$item->code}) berhasil ditambahkan ke inventaris.",
                'subject_type'=> Item::class,
                'subject_id'  => $item->id,
                'properties'  => ['code' => $item->code, 'name' => $item->name, 'category' => $item->category?->name],
                'ip_address'  => '127.0.0.1',
                'created_at'  => $item->created_at,
                'updated_at'  => $item->created_at,
            ]);
        }

        // ── 4. Log Kategori ──────────────────────────────────────────
        $categories = Category::all();
        foreach ($categories as $cat) {
            ActivityLog::create([
                'user_id'     => $admin?->id,
                'causer_name' => $admin?->name ?? 'Admin',
                'causer_role' => 'admin',
                'action'      => 'category.created',
                'description' => "Kategori '{$cat->name}' berhasil ditambahkan.",
                'subject_type'=> Category::class,
                'subject_id'  => $cat->id,
                'properties'  => ['name' => $cat->name],
                'ip_address'  => '127.0.0.1',
                'created_at'  => $cat->created_at,
                'updated_at'  => $cat->created_at,
            ]);
        }

        // ── 5. Log Peminjaman ────────────────────────────────────────
        $borrowings = Borrowing::with(['user', 'item'])->get();
        foreach ($borrowings as $borrowing) {
            $customer = $borrowing->user;
            $item = $borrowing->item;

            // Log pengajuan peminjaman
            ActivityLog::create([
                'user_id'     => $customer?->id,
                'causer_name' => $customer?->name ?? 'Customer',
                'causer_role' => 'customer',
                'action'      => 'borrowing.created',
                'description' => ($customer?->name ?? 'Customer') . " mengajukan peminjaman {$item?->name} ({$borrowing->borrow_code}) mulai " . $borrowing->borrow_date->format('d/m/Y') . " s/d " . $borrowing->return_date->format('d/m/Y') . ".",
                'subject_type'=> Borrowing::class,
                'subject_id'  => $borrowing->id,
                'properties'  => [
                    'borrow_code' => $borrowing->borrow_code,
                    'item'        => $item?->name,
                    'quantity'    => $borrowing->quantity,
                ],
                'ip_address'  => '127.0.0.1',
                'created_at'  => $borrowing->created_at,
                'updated_at'  => $borrowing->created_at,
            ]);

            // Log status lanjutan berdasarkan status peminjaman
            $statusTime = $borrowing->created_at->addHours(2);

            if (in_array($borrowing->status, ['Disetujui', 'Dipinjam', 'Selesai', 'Ditolak'])) {
                ActivityLog::create([
                    'user_id'     => $admin?->id,
                    'causer_name' => $admin?->name ?? 'Admin',
                    'causer_role' => 'admin',
                    'action'      => $borrowing->status === 'Ditolak' ? 'borrowing.rejected' : 'borrowing.approved',
                    'description' => "Peminjaman {$borrowing->borrow_code} ({$item?->name}) " . ($borrowing->status === 'Ditolak' ? 'ditolak' : 'disetujui') . " untuk customer " . ($customer?->name ?? '-') . ".",
                    'subject_type'=> Borrowing::class,
                    'subject_id'  => $borrowing->id,
                    'properties'  => ['borrow_code' => $borrowing->borrow_code],
                    'ip_address'  => '127.0.0.1',
                    'created_at'  => $statusTime,
                    'updated_at'  => $statusTime,
                ]);
            }

            if (in_array($borrowing->status, ['Dipinjam', 'Selesai'])) {
                $releaseTime = $statusTime->addHours(1);
                ActivityLog::create([
                    'user_id'     => $admin?->id,
                    'causer_name' => $admin?->name ?? 'Admin',
                    'causer_role' => 'admin',
                    'action'      => 'borrowing.released',
                    'description' => "Barang {$item?->name} diserahkan kepada customer " . ($customer?->name ?? '-') . " ({$borrowing->borrow_code}).",
                    'subject_type'=> Borrowing::class,
                    'subject_id'  => $borrowing->id,
                    'properties'  => ['borrow_code' => $borrowing->borrow_code, 'item' => $item?->name],
                    'ip_address'  => '127.0.0.1',
                    'created_at'  => $releaseTime,
                    'updated_at'  => $releaseTime,
                ]);
            }
        }

        // ── 6. Log Pengembalian ──────────────────────────────────────
        $returns = ReturnRecord::with(['borrowing.user', 'borrowing.item'])->whereNotNull('verified_at')->get();
        foreach ($returns as $ret) {
            $borrowing = $ret->borrowing;
            $item = $borrowing?->item;
            $customer = $borrowing?->user;

            // Log customer minta kembali
            ActivityLog::create([
                'user_id'     => $customer?->id,
                'causer_name' => $customer?->name ?? 'Customer',
                'causer_role' => 'customer',
                'action'      => 'borrowing.return_requested',
                'description' => ($customer?->name ?? 'Customer') . " mengajukan pengembalian {$item?->name} ({$borrowing?->borrow_code}).",
                'subject_type'=> Borrowing::class,
                'subject_id'  => $borrowing?->id,
                'properties'  => ['borrow_code' => $borrowing?->borrow_code, 'item' => $item?->name],
                'ip_address'  => '127.0.0.1',
                'created_at'  => $ret->created_at,
                'updated_at'  => $ret->created_at,
            ]);

            // Log admin verifikasi
            $verifyTime = $ret->verified_at;
            ActivityLog::create([
                'user_id'     => $admin?->id,
                'causer_name' => $admin?->name ?? 'Admin',
                'causer_role' => 'admin',
                'action'      => 'return.verified',
                'description' => "Pengembalian {$borrowing?->borrow_code} ({$item?->name}) diverifikasi. Kondisi: {$ret->item_condition}" . ($ret->fine_amount > 0 ? ". Denda: Rp " . number_format($ret->fine_amount, 0, ',', '.') : "") . ".",
                'subject_type'=> ReturnRecord::class,
                'subject_id'  => $ret->id,
                'properties'  => [
                    'borrow_code' => $borrowing?->borrow_code,
                    'condition'   => $ret->item_condition,
                    'fine'        => $ret->fine_amount,
                ],
                'ip_address'  => '127.0.0.1',
                'created_at'  => $verifyTime,
                'updated_at'  => $verifyTime,
            ]);
        }
    }
}
