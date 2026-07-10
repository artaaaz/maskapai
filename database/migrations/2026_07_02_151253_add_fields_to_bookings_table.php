<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->enum('trip_type', ['one_way', 'round_trip'])->default('one_way')->after('status');
            $table->unsignedBigInteger('return_flight_id')->nullable()->after('trip_type');
            $table->string('promo_code')->nullable()->after('return_flight_id');
            $table->decimal('discount_amount', 12, 2)->default(0)->after('promo_code');
            $table->decimal('convenience_fee', 12, 2)->default(0)->after('discount_amount');
            $table->decimal('tax_amount', 12, 2)->default(0)->after('convenience_fee');
            $table->integer('points_earned')->default(0)->after('tax_amount');
            
            // Foreign key untuk return flight
            $table->foreign('return_flight_id')->references('id')->on('flights')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropForeign(['return_flight_id']);
            $table->dropColumn([
                'trip_type',
                'return_flight_id',
                'promo_code',
                'discount_amount',
                'convenience_fee',
                'tax_amount',
                'points_earned',
            ]);
        });
    }
};
