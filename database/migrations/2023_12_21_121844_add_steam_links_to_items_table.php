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
        Schema::table('items', function (Blueprint $table) {
            if (
                !Schema::hasColumn('items', 'steam_market_place_link')
                && !Schema::hasColumn('items', 'steam_preview_link')
            ) {
                $table->string('steam_preview_link')->nullable()->after('image');
                $table->string('steam_market_place_link')->nullable()->after('image');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('items', function (Blueprint $table) {
            if (
                Schema::hasColumn('items', 'steam_market_place_link')
                && Schema::hasColumn('items', 'steam_preview_link')
            ) {
                $table->dropColumn(['steam_market_place_link', 'steam_preview_link']);
            }
        });
    }
};