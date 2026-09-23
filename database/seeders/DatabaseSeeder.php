<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Item;
use App\Models\Borrowing;
use App\Models\ReturnRecord;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Users
        $admin = User::create([
            'name' => 'Admin NDE',
            'email' => 'admin@nde.co.id',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'phone' => '081298765432',
            'address' => 'Gedung NDE Tower Lt. 12, Jl. Sudirman Kav. 52-53, Jakarta Pusat',
            'status' => 'aktif',
        ]);

        $budi = User::create([
            'name' => 'Budi Santoso',
            'email' => 'budi@example.com',
            'password' => Hash::make('password'),
            'role' => 'customer',
            'phone' => '081234567890',
            'address' => 'Jl. Sudirman No. 45, Jakarta Pusat',
            'status' => 'aktif',
        ]);

        $siti = User::create([
            'name' => 'Siti Rahma',
            'email' => 'siti@example.com',
            'password' => Hash::make('password'),
            'role' => 'customer',
            'phone' => '082345678901',
            'address' => 'Jl. Thamrin No. 12, Jakarta Pusat',
            'status' => 'aktif',
        ]);

        $ahmad = User::create([
            'name' => 'Ahmad Fauzi',
            'email' => 'ahmad@example.com',
            'password' => Hash::make('password'),
            'role' => 'customer',
            'phone' => '083456789012',
            'address' => 'Jl. Gatot Subroto No. 7, Jakarta Selatan',
            'status' => 'aktif',
        ]);

        // 2. Categories
        $catElektronik = Category::create([
            'name' => 'Elektronik',
            'description' => 'Perangkat elektronik seperti laptop, tablet, dan kamera profesional',
        ]);

        $catAV = Category::create([
            'name' => 'AV Equipment',
            'description' => 'Peralatan audio visual: proyektor, speaker portable, wireless microphone',
        ]);

        $catKantor = Category::create([
            'name' => 'Perkantoran',
            'description' => 'Furnitur perkantoran, meja lipat, dan papan tulis whiteboard',
        ]);

        $catKendaraan = Category::create([
            'name' => 'Kendaraan',
            'description' => 'Kendaraan operasional dan inventaris mobilitas perusahaan',
        ]);

        // 3. Items
        $item1 = Item::create([
            'code' => 'BRG-001',
            'name' => 'Laptop Dell XPS 15',
            'category_id' => $catElektronik->id,
            'condition' => 'Baik',
            'stock' => 8,
            'price_per_day' => 50000,
            'description' => 'Laptop Dell XPS 15 dengan prosesor Intel Core i7 generasi ke-12, RAM 16GB DDR5, SSD NVMe 512GB, dan layar OLED 15.6" 4K. Cocok untuk presentasi dan pekerjaan berat.',
            'status' => 'Tersedia',
            'image' => 'items/laptop.jpg',
        ]);

        $item2 = Item::create([
            'code' => 'BRG-002',
            'name' => 'Proyektor Epson EB-X41',
            'category_id' => $catAV->id,
            'condition' => 'Baik',
            'stock' => 3,
            'price_per_day' => 75000,
            'description' => 'Proyektor 3600 lumens resolusi XGA, konektivitas HDMI dan VGA, cocok untuk ruang meeting besar.',
            'status' => 'Tersedia',
            'image' => 'items/proyektor.jpg',
        ]);

        $item3 = Item::create([
            'code' => 'BRG-003',
            'name' => 'Kamera Canon EOS 250D',
            'category_id' => $catElektronik->id,
            'condition' => 'Baik',
            'stock' => 5,
            'price_per_day' => 40000,
            'description' => 'DSLR ringan dengan sensor 24.1MP, perekaman 4K, layar putar sentuh, dan konektivitas WiFi/Bluetooth.',
            'status' => 'Tersedia',
            'image' => 'items/kamera.jpg',
        ]);

        $item4 = Item::create([
            'code' => 'BRG-004',
            'name' => 'Tablet Samsung Galaxy Tab',
            'category_id' => $catElektronik->id,
            'condition' => 'Baik',
            'stock' => 4,
            'price_per_day' => 30000,
            'description' => 'Tablet layar 10.4 inch dengan S-Pen, RAM 4GB, baterai 7040mAh.',
            'status' => 'Tersedia',
            'image' => 'items/laptop.jpg',
        ]);

        $item5 = Item::create([
            'code' => 'BRG-005',
            'name' => 'Speaker Portable JBL',
            'category_id' => $catAV->id,
            'condition' => 'Baik',
            'stock' => 6,
            'price_per_day' => 25000,
            'description' => 'Speaker bluetooth outdoor bertenaga dengan ketahanan baterai hingga 20 jam dan tahan air.',
            'status' => 'Tersedia',
            'image' => 'items/proyektor.jpg',
        ]);

        $item6 = Item::create([
            'code' => 'BRG-006',
            'name' => 'Meja Lipat Futura',
            'category_id' => $catKantor->id,
            'condition' => 'Baik',
            'stock' => 10,
            'price_per_day' => 0,
            'description' => 'Meja lipat serbaguna ukuran 120x60cm, kuat dan kokoh untuk event atau seminar.',
            'status' => 'Tersedia',
            'image' => 'items/kamera.jpg',
        ]);

        $item7 = Item::create([
            'code' => 'BRG-007',
            'name' => 'Whiteboard 120x80 cm',
            'category_id' => $catKantor->id,
            'condition' => 'Baik',
            'stock' => 4,
            'price_per_day' => 0,
            'description' => 'Papan tulis whiteboard magnetik dua sisi dengan kaki beroda yang bisa dikunci.',
            'status' => 'Tersedia',
            'image' => 'items/proyektor.jpg',
        ]);

        $item8 = Item::create([
            'code' => 'BRG-008',
            'name' => 'Microphone Wireless Set',
            'category_id' => $catAV->id,
            'condition' => 'Baik',
            'stock' => 8,
            'price_per_day' => 35000,
            'description' => 'Set microphone nirkabel 2 channel dengan jangkauan sinyal hingga 50 meter.',
            'status' => 'Tersedia',
            'image' => 'items/kamera.jpg',
        ]);

        // 4. Sample Borrowings
        $b1 = Borrowing::create([
            'borrow_code' => 'PMJ-2024-001',
            'user_id' => $budi->id,
            'item_id' => $item1->id,
            'quantity' => 1,
            'borrow_date' => Carbon::now()->subDays(2),
            'return_date' => Carbon::now()->addDays(5),
            'duration_days' => 7,
            'total_price' => 350000,
            'purpose' => 'Presentasi proyek di kantor klien Jakarta',
            'notes' => 'Mohon disiapkan charger dan tas laptop.',
            'status' => 'Menunggu',
        ]);

        $b2 = Borrowing::create([
            'borrow_code' => 'PMJ-2024-002',
            'user_id' => $siti->id,
            'item_id' => $item2->id,
            'quantity' => 1,
            'borrow_date' => Carbon::now()->subDays(5),
            'return_date' => Carbon::now()->addDays(2),
            'duration_days' => 7,
            'total_price' => 525000,
            'purpose' => 'Workshop internal tim marketing',
            'status' => 'Disetujui',
        ]);

        $b3 = Borrowing::create([
            'borrow_code' => 'PMJ-2024-003',
            'user_id' => $ahmad->id,
            'item_id' => $item3->id,
            'quantity' => 1,
            'borrow_date' => Carbon::now()->subDays(7),
            'return_date' => Carbon::now()->addDays(1),
            'duration_days' => 8,
            'total_price' => 320000,
            'purpose' => 'Dokumentasi event tahunan perusahaan',
            'status' => 'Dipinjam',
        ]);

        $b4 = Borrowing::create([
            'borrow_code' => 'PMJ-2024-004',
            'user_id' => $budi->id,
            'item_id' => $item7->id,
            'quantity' => 1,
            'borrow_date' => Carbon::now()->subDays(15),
            'return_date' => Carbon::now()->subDays(10),
            'duration_days' => 5,
            'total_price' => 0,
            'purpose' => 'Sesi brainstorming sprint',
            'status' => 'Selesai',
        ]);

        ReturnRecord::create([
            'return_code' => 'RTN-2024-001',
            'borrowing_id' => $b4->id,
            'return_date' => Carbon::now()->subDays(10),
            'item_condition' => 'Baik',
            'fine_amount' => 0,
            'admin_notes' => 'Barang dikembalikan lengkap dan dalam kondisi baik.',
            'verified_by' => $admin->id,
            'verified_at' => Carbon::now()->subDays(10),
        ]);
    }
}
