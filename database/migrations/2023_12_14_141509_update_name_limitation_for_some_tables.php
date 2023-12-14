<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private const TABLES_NAME_COLUMN_TO_UPDATE = [
        'cases',
        'items',
        'categories',
    ];

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        foreach (self::TABLES_NAME_COLUMN_TO_UPDATE as $table) {
            if (Schema::hasColumn($table, 'name')) {
                Schema::table($table, function (Blueprint $table) {
                    $table->string('name', 30)->change();
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        foreach (self::TABLES_NAME_COLUMN_TO_UPDATE as $table) {
            if (Schema::hasColumn($table, 'name')) {
                Schema::table($table, function (Blueprint $table) {
                    $table->string('name', 12)->change();
                });
            }
        }
    }
};
