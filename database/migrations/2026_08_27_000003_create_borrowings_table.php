<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('borrowings', function (Blueprint $table) {
            $table->id();
            $table->string('borrow_code')->unique();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('item_id')->constrained('items')->onDelete('cascade');
            $table->integer('quantity')->default(1);
            $table->date('borrow_date');
            $table->date('return_date');
            $table->integer('duration_days')->default(1);
            $table->decimal('total_price', 12, 2)->default(0);
            $table->string('purpose');
            $table->text('notes')->nullable();
            $table->enum('status', ['Menunggu', 'Disetujui', 'Ditolak', 'Dipinjam', 'Menunggu Verifikasi', 'Selesai', 'Dibatalkan'])->default('Menunggu');
            $table->text('rejection_reason')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('borrowings');
    }
};
