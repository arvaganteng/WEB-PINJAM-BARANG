<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Add extension columns to borrowings if not exist
        if (!Schema::hasColumn('borrowings', 'extension_date')) {
            Schema::table('borrowings', function (Blueprint $table) {
                $table->date('extension_date')->nullable()->after('return_date');
                $table->text('extension_reason')->nullable()->after('notes');
                $table->enum('extension_status', ['None', 'Pending', 'Approved', 'Rejected'])->default('None')->after('status');
            });
        }

        // 2. Add return_photo to returns if not exist
        if (!Schema::hasColumn('returns', 'return_photo')) {
            Schema::table('returns', function (Blueprint $table) {
                $table->string('return_photo')->nullable()->after('customer_notes');
            });
        }

        // 3. Create reviews table if not exist
        if (!Schema::hasTable('reviews')) {
            Schema::create('reviews', function (Blueprint $table) {
                $table->id();
                $table->foreignId('borrowing_id')->constrained('borrowings')->onDelete('cascade');
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                $table->foreignId('item_id')->constrained('items')->onDelete('cascade');
                $table->tinyInteger('rating')->unsigned(); // 1 - 5
                $table->text('comment')->nullable();
                $table->timestamps();
            });
        }

        // 4. Create notifications table if not exist
        if (!Schema::hasTable('notifications')) {
            Schema::create('notifications', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                $table->string('title');
                $table->text('message');
                $table->string('link')->nullable();
                $table->string('type')->default('info'); // info, success, warning, danger
                $table->boolean('is_read')->default(false);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
        Schema::dropIfExists('reviews');

        Schema::table('returns', function (Blueprint $table) {
            $table->dropColumn('return_photo');
        });

        Schema::table('borrowings', function (Blueprint $table) {
            $table->dropColumn(['extension_date', 'extension_reason', 'extension_status']);
        });
    }
};
