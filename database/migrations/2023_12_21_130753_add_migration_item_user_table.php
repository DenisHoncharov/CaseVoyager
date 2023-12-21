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
        if (!Schema::hasTable('item_user')) {
            Schema::create('item_user', function (Blueprint $table) {
                $table->id();
                $table->foreignIdFor(\App\Models\User::class, 'user_id')->cascadeOnDelete();
                $table->foreignIdFor(\App\Models\Item::class, 'item_id')->cascadeOnDelete();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_user');
    }
};