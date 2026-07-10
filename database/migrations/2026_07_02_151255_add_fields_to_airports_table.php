<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('airports', function (Blueprint $table) {
            $table->string('terminal')->nullable()->after('iata_code');
            $table->string('timezone')->default('Asia/Jakarta')->after('terminal');
        });
    }

    public function down(): void
    {
        Schema::table('airports', function (Blueprint $table) {
            $table->dropColumn([
                'terminal',
                'timezone',
            ]);
        });
    }
};