<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('borrowings', function (Blueprint $table) {
            if (!Schema::hasColumn('borrowings', 'location')) {
                $table->string('location')->nullable()->after('purpose');
            }
            if (!Schema::hasColumn('borrowings', 'id_card_image')) {
                $table->string('id_card_image')->nullable()->after('notes');
            }
        });

        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'id_card_image')) {
                $table->string('id_card_image')->nullable()->after('avatar');
            }
        });
    }

    public function down(): void
    {
        Schema::table('borrowings', function (Blueprint $table) {
            $table->dropColumn(['location', 'id_card_image']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['id_card_image']);
        });
    }
};
