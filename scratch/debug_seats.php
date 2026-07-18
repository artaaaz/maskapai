<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Check ALL flights and their seat availability
echo "=== ALL FLIGHTS ===\n";
$flights = App\Models\Flight::with('flightClasses', 'airplane')->get();
foreach ($flights as $flight) {
    $airplaneId = $flight->airplane_id;
    echo "Flight {$flight->id}: airplane_id={$airplaneId}\n";
    
    // Check if airplane exists
    if (!$flight->airplane) {
        echo "  WARNING: No airplane found for this flight!\n";
        continue;
    }
    
    foreach ($flight->flightClasses as $fc) {
        $seatCount = App\Models\Seat::where('airplane_id', $airplaneId)
            ->where('class', $fc->class_name)
            ->count();
        echo "  FC id={$fc->id} name='{$fc->class_name}': seats={$seatCount}\n";
    }
    echo "\n";
}