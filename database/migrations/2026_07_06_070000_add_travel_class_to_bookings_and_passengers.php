<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->string('travel_class')->default('economy')->after('trip_type');
            $table->date('return_date')->nullable()->after('travel_class');
        });

        Schema::table('passengers', function (Blueprint $table) {
            $table->string('travel_class')->default('economy')->after('seat_number');
        });

        Schema::table('payments', function (Blueprint $table) {
            if (!Schema::hasColumn('payments', 'midtrans_transaction_id')) {
                $table->string('midtrans_transaction_id')->nullable()->after('transaction_code');
            }
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['travel_class', 'return_date']);
        });

        Schema::table('passengers', function (Blueprint $table) {
            $table->dropColumn('travel_class');
        });

        Schema::table('payments', function (Blueprint $table) {
            if (Schema::hasColumn('payments', 'midtrans_transaction_id')) {
                $table->dropColumn('midtrans_transaction_id');
            }
        });
    }
};
