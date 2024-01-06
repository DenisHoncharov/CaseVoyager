<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('item_user', function (Blueprint $table) {
            $table->boolean('is_requested')->default(false)->after('item_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('item_user', function (Blueprint $table) {
            if (Schema::hasColumn('item_user', 'is_requested')) {
                $table->dropColumn('is_requested');
            }
        });
    }
};
