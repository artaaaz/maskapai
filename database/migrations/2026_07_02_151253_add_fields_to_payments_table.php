<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->string('payment_gateway')->nullable()->after('payment_method');
            $table->string('virtual_account_number')->nullable()->after('payment_gateway');
            $table->string('payment_proof')->nullable()->after('virtual_account_number');
            $table->timestamp('paid_at')->nullable()->after('payment_status');
            $table->timestamp('expired_at')->nullable()->after('paid_at');
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn([
                'payment_gateway',
                'virtual_account_number',
                'payment_proof',
                'paid_at',
                'expired_at',
            ]);
        });
    }
};
