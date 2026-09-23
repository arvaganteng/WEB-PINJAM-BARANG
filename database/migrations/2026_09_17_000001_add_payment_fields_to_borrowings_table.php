<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('borrowings', function (Blueprint $table) {
            $table->enum('payment_method', ['Belum Dipilih', 'Transfer Bank', 'QRIS', 'Tunai', 'Gratis'])->default('Belum Dipilih')->after('total_price');
            $table->string('payment_proof')->nullable()->after('payment_method');
            $table->enum('payment_status', ['Belum Bayar', 'Menunggu Verifikasi', 'Lunas', 'Ditolak'])->default('Belum Bayar')->after('payment_proof');
            $table->timestamp('paid_at')->nullable()->after('payment_status');
            $table->text('payment_notes')->nullable()->after('paid_at');
        });
    }

    public function down(): void
    {
        Schema::table('borrowings', function (Blueprint $table) {
            $table->dropColumn([
                'payment_method',
                'payment_proof',
                'payment_status',
                'paid_at',
                'payment_notes',
            ]);
        });
    }
};
