<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('seat_reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained()->cascadeOnDelete();
            $table->foreignId('flight_id')->constrained()->cascadeOnDelete();
            $table->foreignId('seat_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('flight_class_id')->constrained('flight_classes')->cascadeOnDelete();
            $table->enum('status', ['reserved', 'paid', 'checked_in', 'boarded', 'completed', 'cancelled'])->default('reserved');
            $table->timestamps();
            
            // Prevent double booking of same seat
            $table->unique(['seat_id', 'flight_id', 'status'], 'unique_active_reservation');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seat_reservations');
    }
};