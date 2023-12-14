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
            $table->dropColumn(['name', 'password', 'remember_token']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->float('balance', 8, 2)->default(0)->after('id');
            $table->string('telegram_id', 30)->nullable()->after('id');
            $table->string('auth0_id')->nullable()->after('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['balance', 'auth0_id', 'telegram_id']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->rememberToken()->after('id');
            $table->string('password')->after('id');
            $table->string('name')->after('id');
        });
    }
};
