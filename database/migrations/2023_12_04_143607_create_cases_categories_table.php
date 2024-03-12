<?php

use App\Models\Cases;
use App\Models\Category;
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
        Schema::create('case_category', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Cases::class, 'cases_id')->cascadeOnDelete();
            $table->foreignIdFor(Category::class, 'category_id')->cascadeOnDelete();
            $table->foreignIdFor(User::class, 'user_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('case_category');
    }
};
