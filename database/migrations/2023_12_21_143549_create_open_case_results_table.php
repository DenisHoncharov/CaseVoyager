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
        if (!Schema::hasTable('open_case_results')) {
            Schema::create('open_case_results', function (Blueprint $table) {
                $table->id();
                $table->foreignIdFor(\App\Models\User::class);
                $table->foreignIdFor(\App\Models\Cases::class, 'opened_case_id');
                $table->foreignIdFor(\App\Models\Item::class);
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
