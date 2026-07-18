<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Flight;
use App\Models\Airport;
use App\Models\Airline;
use App\Models\FlightClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FlightResultsController extends Controller
{
    public function index(Request $request)
    {
        $airports = Airport::orderBy('city')->get();
        $airlines = Airline::orderBy('name')->get();
        $travelClasses = config('travel_class');
        
        // Get all available class names from flight_classes for filter
        $availableClasses = FlightClass::select('class_name')->distinct()->pluck('class_name');

        if ($request->has('departure_airport_id')) {
            $validated = $request->validate([
                'departure_airport_id' => 'required|exists:airports,id',
                'arrival_airport_id' => 'required|exists:airports,id|different:departure_airport_id',
                'departure_date' => 'required|date',
                'trip_type' => 'required|in:one_way,round_trip',
                'return_date' => 'nullable|required_if:trip_type,round_trip|date|after:departure_date',
                'passenger_count' => 'required|integer|min:1|max:9',
                'travel_class' => 'required|in:' . implode(',', array_keys(config('travel_class'))),
                'airline_id' => 'nullable|exists:airlines,id',
                'max_price' => 'nullable|numeric|min:0',
                'max_duration' => 'nullable|integer|min:0',
                'departure_time_from' => 'nullable|date_format:H:i',
                'departure_time_to' => 'nullable|date_format:H:i',
                'sort_by' => 'nullable|in:price_asc,price_desc,time_asc,time_desc,duration_asc',
                'class_filter' => 'nullable|in:economy,business,first,premium_economy',
            ]);

            $departureDate = $request->departure_date;
            $passengerCount = $request->passenger_count;
            $travelClassFilter = $request->travel_class;

            // Build query for departure flights using flight_classes for pricing
            $query = Flight::with([
                'airline', 
                'departureAirport', 
                'arrivalAirport', 
                'airplane',
                'flightClasses' => function ($q) {
                    $q->orderBy('price', 'asc');
                }
            ])
            ->where('departure_airport_id', $request->departure_airport_id)
            ->where('arrival_airport_id', $request->arrival_airport_id)
            ->whereDate('departure_time', $departureDate)
            ->where('departure_time', '>=', now()->subHours(1));
            // BUG 1 FIX: whereHas('flightClasses') dihapus - filter ini membuang
            // Flight lama yang belum punya baris flight_classes. Flight tsb sekarang
            // disembuhkan (auto-create economy class) di bawah, di dalam ->get()
            // ->filter(...) sebelum dicek quota-nya.

            // Apply filters - support multiple airline_ids (array from checkboxes)
            if ($request->filled('airline_id')) {
                $airlineIds = $request->input('airline_id');
                if (is_array($airlineIds)) {
                    $query->whereIn('airline_id', $airlineIds);
                } else {
                    $query->where('airline_id', $airlineIds);
                }
            }

            // Filter by class if specified
            if ($request->filled('class_filter')) {
                $query->whereHas('flightClasses', function ($q) use ($request) {
                    $q->where('class_name', $request->class_filter);
                });
            }

            // Filter by max price from flight_classes
            if ($request->filled('max_price')) {
                $query->whereHas('flightClasses', function ($q) use ($request, $travelClassFilter) {
                    $q->where('class_name', $travelClassFilter)
                      ->where('price', '<=', $request->max_price);
                });
            }

            if ($request->filled('departure_time_from')) {
                $query->whereTime('departure_time', '>=', $request->departure_time_from);
            }

            if ($request->filled('departure_time_to')) {
                $query->whereTime('departure_time', '<=', $request->departure_time_to);
            }

            // Get results
            $flights = $query->get()->filter(function ($flight) use ($passengerCount, $travelClassFilter) {
                // Self-healing: Flight lama tanpa flight_classes otomatis dibuatkan
                // kelas economy dari data flight itu sendiri.
                $flight->ensureHasFlightClass();

                // Check if the specific class has enough quota
                $class = $flight->flightClasses->firstWhere('class_name', $travelClassFilter);
                if (!$class) {
                    // Fallback: check if any class has enough quota
                    $hasQuota = $flight->flightClasses->contains(function ($fc) use ($passengerCount) {
                        return $fc->available_quota >= $passengerCount;
                    });
                    return $hasQuota;
                }
                return $class->available_quota >= $passengerCount;
            })->map(function ($flight) use ($travelClassFilter) {
                // Find the price for the requested class, or get the cheapest
                $class = $flight->flightClasses->firstWhere('class_name', $travelClassFilter);
                if (!$class) {
                    $class = $flight->flightClasses->first();
                }
                $flight->display_price = $class ? $class->price : 0;
                $flight->selected_class_name = $class ? $class->class_name : 'economy';
                $flight->transit_count = 0;
                return $flight;
            })->values();

            // Sorting
            $sortBy = $request->get('sort_by', 'time_asc');
            switch ($sortBy) {
                case 'price_asc':
                    $flights = $flights->sortBy('display_price')->values();
                    break;
                case 'price_desc':
                    $flights = $flights->sortByDesc('display_price')->values();
                    break;
                case 'time_asc':
                    $flights = $flights->sortBy('departure_time')->values();
                    break;
                case 'time_desc':
                    $flights = $flights->sortByDesc('departure_time')->values();
                    break;
                case 'duration_asc':
                    $flights = $flights->sortBy('duration_minutes')->values();
                    break;
                default:
                    $flights = $flights->sortBy('departure_time')->values();
            }

            // Filter by max duration
            if ($request->filled('max_duration')) {
                $flights = $flights->filter(function ($flight) use ($request) {
                    $dep = \Carbon\Carbon::parse($flight->departure_time);
                    $arr = \Carbon\Carbon::parse($flight->arrival_time);
                    $durationMinutes = $dep->diffInMinutes($arr);
                    return $durationMinutes <= $request->max_duration;
                })->values();
            }

            // Round trip - return flights
            $returnFlights = collect();
            if ($request->trip_type === 'round_trip' && $request->return_date) {
                $returnQuery = Flight::with([
                    'airline', 
                    'departureAirport', 
                    'arrivalAirport', 
                    'airplane',
                    'flightClasses' => function ($q) {
                        $q->orderBy('price', 'asc');
                    }
                ])
                ->where('departure_airport_id', $request->arrival_airport_id)
                ->where('arrival_airport_id', $request->departure_airport_id)
                ->whereDate('departure_time', $request->return_date)
                ->where('departure_time', '>=', now()->subHours(1));
                // BUG 1 FIX: whereHas('flightClasses') dihapus, sama seperti query
                // departure di atas - Flight lama disembuhkan di ->filter() di bawah.

                if ($request->filled('airline_id')) {
                    $airlineIds = $request->input('airline_id');
                    if (is_array($airlineIds)) {
                        $returnQuery->whereIn('airline_id', $airlineIds);
                    } else {
                        $returnQuery->where('airline_id', $airlineIds);
                    }
                }

                $returnFlights = $returnQuery->get()->filter(function ($flight) use ($passengerCount, $travelClassFilter) {
                    $flight->ensureHasFlightClass();

                    $class = $flight->flightClasses->firstWhere('class_name', $travelClassFilter);
                    if (!$class) {
                        $hasQuota = $flight->flightClasses->contains(function ($fc) use ($passengerCount) {
                            return $fc->available_quota >= $passengerCount;
                        });
                        return $hasQuota;
                    }
                    return $class->available_quota >= $passengerCount;
                })->map(function ($flight) use ($travelClassFilter) {
                    $class = $flight->flightClasses->firstWhere('class_name', $travelClassFilter);
                    if (!$class) {
                        $class = $flight->flightClasses->first();
                    }
                    $flight->display_price = $class ? $class->price : 0;
                    $flight->selected_class_name = $class ? $class->class_name : 'economy';
                    $flight->transit_count = 0;
                    return $flight;
                })->values();
            }

            $searchParams = $request->only([
                'departure_airport_id', 'arrival_airport_id', 'departure_date',
                'return_date', 'trip_type', 'passenger_count', 'travel_class',
            ]);

            $selectedDepartureId = $request->selected_departure_id;
            $selectedDepartureFlight = $selectedDepartureId ? $flights->firstWhere('id', $selectedDepartureId) : null;

            return view('customer.results', compact(
                'flights', 'returnFlights', 'airports', 'airlines',
                'travelClasses', 'searchParams', 'selectedDepartureFlight',
                'availableClasses'
            ));
        }

        return view('customer.results', compact('airports', 'airlines', 'travelClasses'));
    }

    public function show(Flight $flight, Request $request)
    {
        $flight->load([
            'airline', 
            'departureAirport', 
            'arrivalAirport', 
            'airplane.seats',
            'flightClasses'
        ]);

        // BUG 1 FIX: pastikan flight ini punya minimal 1 flight_class supaya harga
        // & kelas tetap tampil walau data lama belum punya baris flight_classes.
        $flight->ensureHasFlightClass();

        $travelClass = $request->get('travel_class', 'economy');
        $passengerCount = max(1, (int) $request->get('passenger_count', 1));
        
        // Get price from flight_classes
        $selectedFlightClass = $flight->flightClasses->firstWhere('class_name', $travelClass);
        if (!$selectedFlightClass) {
            $selectedFlightClass = $flight->flightClasses->first();
        }
        $flight->display_price = $selectedFlightClass ? $selectedFlightClass->price : 0;
        $flight->cheapest_class = $flight->flightClasses->sortBy('price')->first();

        // Get booked seat numbers
        $bookedSeatNumbers = \App\Models\Passenger::whereHas('booking', function ($query) use ($flight) {
            $query->where('flight_id', $flight->id)
                  ->where('status', '!=', 'cancelled');
        })->whereNotNull('seat_number')->pluck('seat_number')->toArray();

        return view('customer.flight-detail', compact('flight', 'travelClass', 'passengerCount', 'bookedSeatNumbers', 'selectedFlightClass'));
    }
}