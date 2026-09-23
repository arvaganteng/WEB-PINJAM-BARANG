<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('returns', function (Blueprint $table) {
            $table->id();
            $table->string('return_code')->unique();
            $table->foreignId('borrowing_id')->constrained('borrowings')->onDelete('cascade');
            $table->date('return_date'); // Tanggal customer mengajukan pengembalian
            $table->enum('item_condition', ['Baik', 'Kurang Baik', 'Rusak Ringan', 'Rusak Berat'])->nullable();
            $table->decimal('fine_amount', 12, 2)->default(0);
            $table->text('admin_notes')->nullable();
            $table->text('customer_notes')->nullable(); // Catatan dari customer
            $table->foreignId('verified_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('verified_at')->nullable(); // Kapan admin verifikasi
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('returns');
    }
};
