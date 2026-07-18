<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Modify the ENUM to include premium_economy
        DB::statement("ALTER TABLE seats MODIFY COLUMN class ENUM('economy', 'premium_economy', 'business', 'first') NOT NULL DEFAULT 'economy'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE seats MODIFY COLUMN class ENUM('economy', 'business', 'first') NOT NULL DEFAULT 'economy'");
    }
};