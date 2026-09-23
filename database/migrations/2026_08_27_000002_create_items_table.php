<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->enum('condition', ['Baik', 'Kurang Baik', 'Rusak Ringan', 'Rusak Berat'])->default('Baik');
            $table->integer('stock')->default(1);
            $table->decimal('price_per_day', 12, 2)->default(0);
            $table->string('image')->nullable();
            $table->text('description')->nullable();
            $table->enum('status', ['Tersedia', 'Dipinjam', 'Tidak Tersedia', 'Rusak'])->default('Tersedia');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
