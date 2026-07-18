<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('passengers', function (Blueprint $table) {
            $table->enum('title', ['Mr', 'Mrs', 'Ms'])->nullable()->after('gender');
            $table->string('phone', 20)->nullable()->after('passport_number');
            $table->string('email')->nullable()->after('phone');
            $table->string('frequent_flyer_number')->nullable()->after('email');
        });
    }

    public function down(): void
    {
        Schema::table('passengers', function (Blueprint $table) {
            $table->dropColumn([
                'title',
                'phone',
                'email',
                'frequent_flyer_number',
            ]);
        });
    }
};