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
        Schema::table('users', function (Blueprint $table) {
            if (
                !Schema::hasColumn('users', 'steam_profile_url')
                && !Schema::hasColumn('users', 'steam_trade_link')
            ) {
                $table->string('steam_trade_link', 60)->nullable()->after('telegram_id');
                $table->string('steam_profile_url', 100)->nullable()->after('telegram_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (
                Schema::hasColumn('users', 'steam_profile_url')
                && Schema::hasColumn('users', 'steam_trade_link')
            ) {
                $table->dropColumn(['steam_trade_link', 'steam_profile_url']);
            }
        });
    }
};
