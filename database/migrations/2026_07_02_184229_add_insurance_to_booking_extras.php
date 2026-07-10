<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE booking_extras MODIFY COLUMN extra_type ENUM('seat', 'baggage', 'meal', 'insurance') NOT NULL");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE booking_extras MODIFY COLUMN extra_type ENUM('seat', 'baggage', 'meal') NOT NULL");
    }
};