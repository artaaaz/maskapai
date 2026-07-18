<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('promos', function (Blueprint $table) {
            if (!Schema::hasColumn('promos', 'min_purchase')) {
                $table->decimal('min_purchase', 15, 0)->nullable()->default(0)->after('min_transaction');
            }
            if (!Schema::hasColumn('promos', 'quota')) {
                $table->integer('quota')->nullable()->default(0)->after('min_purchase');
            }
            if (!Schema::hasColumn('promos', 'used_count')) {
                $table->integer('used_count')->default(0)->after('quota');
            }
            if (!Schema::hasColumn('promos', 'title')) {
                $table->string('title')->nullable()->after('name');
            }
        });
    }

    public function down(): void
    {
        Schema::table('promos', function (Blueprint $table) {
            $table->dropColumn(['min_purchase', 'quota', 'used_count', 'title']);
        });
    }
};