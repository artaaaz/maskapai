<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('flights', function (Blueprint $table) {
            $table->integer('duration_minutes')->nullable()->after('arrival_time');
            $table->integer('baggage_allowance_kg')->default(0)->after('duration_minutes');
            $table->text('refund_policy')->nullable()->after('baggage_allowance_kg');
        });
    }

    public function down(): void
    {
        Schema::table('flights', function (Blueprint $table) {
            $table->dropColumn([
                'duration_minutes',
                'baggage_allowance_kg',
                'refund_policy',
            ]);
        });
    }
};