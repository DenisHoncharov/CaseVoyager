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
                !Schema::hasColumn('items', 'source_marketplace_link')
                && !Schema::hasColumn('items', 'source_preview_link')
            ) {
                $table->string('source_preview_link')->nullable()->after('image');
                $table->string('source_marketplace_link')->nullable()->after('image');
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
                Schema::hasColumn('items', 'source_marketplace_link')
                && Schema::hasColumn('items', 'source_preview_link')
            ) {
                $table->dropColumn(['source_marketplace_link', 'source_preview_link']);
            }
        });
    }
};
