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
        if (!Schema::hasTable('open_case_results')) {
            Schema::create('open_case_results', function (Blueprint $table) {
                $table->id();
                $table->foreignIdFor(User::class);
                $table->foreignIdFor(Cases::class, 'opened_case_id');
                $table->foreignIdFor(Item::class);
                $table->boolean('is_received')->default(false);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('open_case_results');
    }
};
