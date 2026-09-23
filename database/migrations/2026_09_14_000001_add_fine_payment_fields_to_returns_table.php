<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('returns', function (Blueprint $table) {
            $table->enum('fine_payment_method', ['Belum Dipilih', 'Transfer', 'Cash'])->default('Belum Dipilih')->after('fine_amount');
            $table->string('fine_payment_proof')->nullable()->after('fine_payment_method');
            $table->enum('fine_payment_status', ['Belum Bayar', 'Menunggu Verifikasi', 'Lunas'])->default('Belum Bayar')->after('fine_payment_proof');
            $table->text('fine_payment_notes')->nullable()->after('fine_payment_status');
        });
    }

    public function down(): void
    {
        Schema::table('returns', function (Blueprint $table) {
            $table->dropColumn([
                'fine_payment_method',
                'fine_payment_proof',
                'fine_payment_status',
                'fine_payment_notes',
            ]);
        });
    }
};
