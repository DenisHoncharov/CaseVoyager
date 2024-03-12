<?php

use App\Models\Type;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private const TABLES_WITH_TYPE_COLUMN = [
        'cases',
        'items',
        'categories',
    ];

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        foreach (self::TABLES_WITH_TYPE_COLUMN as $table) {
            if (Schema::hasColumn($table, 'type')) {
                Schema::table($table, function (Blueprint $table) {
                    $table->dropColumn('type');
                });
            }

            Schema::table($table, function (Blueprint $table) {
                $table->foreignIdFor(Type::class)
                    ->nullable()
                    ->after('name')
                    ->nullOnDelete();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        foreach (self::TABLES_WITH_TYPE_COLUMN as $table) {
            if (Schema::hasColumn($table, 'type_id')) {
                Schema::table($table, function (Blueprint $table) {
                    $table->dropForeignIdFor(Type::class);
                });
            }

            Schema::table($table, function (Blueprint $table) {
                $table->string('type', 12)->default('cs2')->after('name');
            });
        }
    }
};
