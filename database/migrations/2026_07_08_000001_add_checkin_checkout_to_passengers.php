<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('passengers', function (Blueprint $table) {
            $table->boolean('has_checked_in')->default(false)->after('frequent_flyer_number');
            $table->boolean('has_boarded')->default(false)->after('has_checked_in');
            $table->timestamp('checked_in_at')->nullable()->after('has_boarded');
            $table->timestamp('boarded_at')->nullable()->after('checked_in_at');
            $table->timestamp('checked_out_at')->nullable()->after('boarded_at');
        });
    }

    public function down(): void
    {
        Schema::table('passengers', function (Blueprint $table) {
            $table->dropColumn(['has_checked_in', 'has_boarded', 'checked_in_at', 'boarded_at', 'checked_out_at']);
        });
    }
};