<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('airplanes', function (Blueprint $table) {
            $table->integer('total_seats')->default(160)->after('model');
        });
    }

    public function down(): void
    {
        Schema::table('airplanes', function (Blueprint $table) {
            $table->dropColumn('total_seats');
        });
    }
};