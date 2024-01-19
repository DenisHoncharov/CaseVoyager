<?php

use App\Models\Cases;
use App\Models\Item;
use App\Models\User;
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
        Schema::create('case_item', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Cases::class, 'cases_id')->cascadeOnDelete();
            $table->foreignIdFor(Item::class, 'item_id')->cascadeOnDelete();
            $table->float('drop_percentage')->default(0);
            $table->foreignIdFor(User::class, 'user_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('case_item');
    }
};
