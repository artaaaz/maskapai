<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('flight_classes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('flight_id')->constrained()->cascadeOnDelete();
            $table->string('class_name'); // economy, business, first
            $table->decimal('price', 12, 2);
            $table->integer('seat_quota')->default(0);
            $table->timestamps();
            
            // Unique constraint: one class_name per flight
            $table->unique(['flight_id', 'class_name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('flight_classes');
    }
};