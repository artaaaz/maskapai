@extends('layouts.customer')

@section('content')
    {{-- Search Banner --}}
    <div class="bg-gradient-to-r from-blue-700 via-blue-800 to-indigo-900 py-10 shadow-lg text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-black mb-2">Cari Penerbangan ✈️</h1>
            <p class="text-blue-100 text-sm">Temukan rute terbaik dengan harga hemat untuk liburan Anda</p>
        </div>
    </div>

    {{-- Compact Search Form Banner (tiket.com style) --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-6 relative z-10">
        <div class="bg-white rounded-3xl shadow-xl p-5 md:p-6 mb-8 border border-slate-105">
            <form action="{{ route('customer.search') }}" method="GET" id="searchForm">
                {{-- Trip Type Selection --}}
                <div class="flex gap-4 mb-4 border-b border-slate-100 pb-3">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="trip_type" id="tripTypeOneWay" value="one_way" class="w-4 h-4 text-blue-600" 
                            {{ (request('trip_type', $searchParams['trip_type'] ?? 'one_way') == 'one_way') ? 'checked' : '' }}>
                        <span class="text-slate-700 font-semibold text-sm">Sekali jalan</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="trip_type" id="tripTypeRoundTrip" value="round_trip" class="w-4 h-4 text-blue-600"
                            {{ (request('trip_type', $searchParams['trip_type'] ?? '') == 'round_trip') ? 'checked' : '' }}>
                        <span class="text-slate-700 font-semibold text-sm">Pulang-pergi</span>
                    </label>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                    {{-- From --}}
                    <div class="md:col-span-3">
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Dari</label>
                        <select name="departure_airport_id" required class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm font-semibold text-slate-800">
                            <option value="">Pilih Asal</option>
                            @foreach($airports as $airport)
                                <option value="{{ $airport->id }}" {{ (request('departure_airport_id', $searchParams['departure_airport_id'] ?? '') == $airport->id) ? 'selected' : '' }}>
                                    {{ $airport->city }} ({{ $airport->iata_code }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- To --}}
                    <div class="md:col-span-3">
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Ke</label>
                        <select name="arrival_airport_id" required class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm font-semibold text-slate-800">
                            <option value="">Pilih Tujuan</option>
                            @foreach($airports as $airport)
                                <option value="{{ $airport->id }}" {{ (request('arrival_airport_id', $searchParams['arrival_airport_id'] ?? '') == $airport->id) ? 'selected' : '' }}>
                                    {{ $airport->city }} ({{ $airport->iata_code }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Departure Date --}}
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Pergi</label>
                        <input type="date" name="departure_date" required id="departureDate"
                            value="{{ request('departure_date', $searchParams['departure_date'] ?? now()->format('Y-m-d')) }}" 
                            class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm font-semibold text-slate-800">
                    </div>

                    {{-- Return Date --}}
                    <div class="md:col-span-2 relative" id="returnDateWrapper" onclick="handleReturnDateClick()">
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Pulang</label>
                        <div class="relative">
                            <input type="date" name="return_date" id="returnDate"
                                value="{{ request('return_date', $searchParams['return_date'] ?? now()->addDay()->format('Y-m-d')) }}" 
                                class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm font-semibold text-slate-800">
                            
                            <div id="returnDateOverlay" class="absolute inset-0 bg-slate-100/95 rounded-xl flex items-center justify-between px-3 cursor-pointer hover:bg-slate-200 transition-colors">
                                <span class="text-xs font-semibold text-slate-500">Sekali Jalan</span>
                                <span class="text-[10px] bg-blue-105 text-blue-700 font-bold px-2 py-0.5 rounded-full uppercase">Aktifkan</span>
                            </div>
                        </div>
                    </div>

                    {{-- Passengers & Class --}}
                    <div class="md:col-span-2 relative">
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Kelas & Penumpang</label>
                        <button type="button" id="passengerClassBtn" onclick="togglePassengerPopover(event)"
                            class="w-full text-left px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm font-semibold text-slate-800 flex justify-between items-center">
                            <span id="passengerClassLabel">1 Pax, Ekonomi</span>
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>

                        {{-- Popover --}}
                        <div id="passengerClassPopover" class="hidden absolute right-0 z-50 bg-white border border-slate-200 shadow-2xl rounded-2xl p-6 mt-2 w-80 text-slate-800">
                            <div class="mb-4">
                                <h4 class="font-bold text-xs text-slate-700 mb-2">Jumlah Penumpang</h4>
                                <div class="flex justify-between items-center bg-slate-50 p-2 rounded-xl">
                                    <span class="text-xs font-semibold text-slate-650">Dewasa / Anak</span>
                                    <div class="flex items-center gap-3">
                                        <button type="button" onclick="adjustPassengers(-1)" class="w-7 h-7 bg-white border rounded-full flex items-center justify-center font-bold text-slate-700 shadow-sm">-</button>
                                        <span id="passengerCountVal" class="font-bold text-sm w-4 text-center">1</span>
                                        <button type="button" onclick="adjustPassengers(1)" class="w-7 h-7 bg-white border rounded-full flex items-center justify-center font-bold text-slate-700 shadow-sm">+</button>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <h4 class="font-bold text-xs text-slate-700 mb-2">Kelas Kabin</h4>
                                <div class="grid grid-cols-2 gap-2">
                                    @foreach(config('travel_class') as $key => $class)
                                        <button type="button" onclick="selectTravelClass('{{ $key }}', '{{ $class['label'] }}')"
                                            id="btn-class-{{ $key }}"
                                            class="class-btn p-2 text-left border rounded-xl hover:bg-blue-50/50 hover:border-blue-200 text-xs">
                                            <p class="text-[10px] text-slate-400 font-semibold">Mulai dari</p>
                                            <p class="font-bold text-slate-700 leading-tight">{{ $class['label'] }}</p>
                                        </button>
                                    @endforeach
                                </div>
                            </div>

                            <button type="button" onclick="closePassengerPopover()" class="w-full mt-4 py-2.5 bg-blue-650 hover:bg-blue-700 text-white font-bold text-xs rounded-xl shadow-md">Selesai</button>
                        </div>

                        {{-- Hidden inputs --}}
                        <input type="hidden" name="passenger_count" id="passenger_count" value="{{ request('passenger_count', $searchParams['passenger_count'] ?? 1) }}">
                        <input type="hidden" name="travel_class" id="travel_class" value="{{ request('travel_class', $searchParams['travel_class'] ?? 'economy') }}">
                    </div>
                </div>

                <div class="mt-4 flex justify-end">
                    <button type="submit" class="w-full md:w-56 py-3 bg-blue-650 hover:bg-blue-700 text-white font-bold rounded-xl transition-all shadow-md flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        Cari Ulang
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Search Results Section --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-16">
        @if(isset($flights))
            {{-- Check if Round-Trip Selection flow --}}
            @php
                $isRoundTrip = request('trip_type', $searchParams['trip_type'] ?? 'one_way') === 'round_trip';
                $selectedDepartureId = request('selected_departure_id');
                $selectedDepartureFlight = $selectedDepartureId ? $flights->firstWhere('id', $selectedDepartureId) : null;
            @endphp

            @if($isRoundTrip)
                {{-- Round Trip flow --}}
                <div class="mb-6">
                    <div class="flex items-center gap-2 text-slate-500 text-sm mb-4">
                        <span class="font-bold {{ !$selectedDepartureFlight ? 'text-blue-600' : '' }}">1. Penerbangan Pergi</span>
                        <span class="text-slate-350">➔</span>
                        <span class="font-bold {{ $selectedDepartureFlight ? 'text-blue-600' : '' }}">2. Penerbangan Pulang</span>
                    </div>

                    @if($selectedDepartureFlight)
                        {{-- Show Selected Departure Flight Card --}}
                        <div class="bg-blue-50 border border-blue-200 rounded-2xl p-5 mb-8 flex justify-between items-center flex-wrap gap-4">
                            <div>
                                <span class="px-2.5 py-0.5 bg-blue-600 text-white font-bold text-xs rounded-full uppercase tracking-wider">Pergi Terpilih</span>
                                <h3 class="font-black text-slate-800 mt-2 text-lg">
                                    {{ $selectedDepartureFlight->departureAirport->city }} ({{ $selectedDepartureFlight->departureAirport->iata_code }}) ➔ 
                                    {{ $selectedDepartureFlight->arrivalAirport->city }} ({{ $selectedDepartureFlight->arrivalAirport->iata_code }})
                                </h3>
                                <p class="text-slate-500 text-sm mt-1">
                                    {{ $selectedDepartureFlight->airline->name }} · {{ $selectedDepartureFlight->flight_number }} · 
                                    {{ $selectedDepartureFlight->departure_time->format('d M Y, H:i') }}
                                </p>
                            </div>
                            <div>
                                <a href="?{{ http_build_query(request()->except('selected_departure_id')) }}" class="px-5 py-2 bg-white hover:bg-slate-50 border border-slate-300 text-slate-700 font-bold rounded-xl text-sm transition-colors shadow-sm">
                                    Ubah Penerbangan
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            @endif

            {{-- List Flights --}}
            @if(!$isRoundTrip || !$selectedDepartureFlight)
                {{-- Listing Departure Flights --}}
                <h2 class="text-2xl font-black text-slate-800 mb-6 flex items-center gap-2">
                    <span>Penerbangan Pergi</span>
                    <span class="text-slate-400 font-normal text-sm">({{ $flights->count() }} pilihan)</span>
                </h2>

                @if($flights->count() > 0)
                    <div class="space-y-4">
                        @foreach($flights as $flight)
                            <div class="bg-white rounded-3xl shadow-md border border-slate-100 p-6 hover:shadow-xl hover:border-slate-200 transition-all">
                                <div class="flex items-center justify-between flex-wrap gap-6">
                                    <div class="flex items-center gap-8 flex-1 min-w-[300px]">
                                        <div class="text-center">
                                            <p class="text-2xl font-black text-slate-800">{{ $flight->departure_time->format('H:i') }}</p>
                                            <p class="text-xs text-slate-455 font-bold uppercase">{{ $flight->departureAirport->iata_code }}</p>
                                        </div>

                                        <div class="flex-1 text-center">
                                            <p class="text-xs text-slate-500 font-bold">{{ $flight->airline->name }}</p>
                                            <div class="flex items-center justify-center gap-2 my-1">
                                                <div class="h-px bg-slate-200 flex-1"></div>
                                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                                </svg>
                                                <div class="h-px bg-slate-200 flex-1"></div>
                                            </div>
                                            <p class="text-[10px] text-slate-455 font-bold uppercase">{{ $flight->flight_number }}</p>
                                        </div>

                                        <div class="text-center">
                                            <p class="text-2xl font-black text-slate-800">{{ $flight->arrival_time->format('H:i') }}</p>
                                            <p class="text-xs text-slate-455 font-bold uppercase">{{ $flight->arrivalAirport->iata_code }}</p>
                                        </div>
                                    </div>

                                    <div class="text-right">
                                        <p class="text-2xl font-black text-blue-600">Rp {{ number_format($flight->display_price ?? $flight->price, 0, ',', '.') }}</p>
                                        <p class="text-xs text-slate-500 font-semibold mb-3">{{ $flight->available_seats }} kursi tersedia</p>
                                        
                                        @if($isRoundTrip)
                                            <a href="?{{ http_build_query(array_merge(request()->all(), ['selected_departure_id' => $flight->id])) }}" class="inline-block px-7 py-2.5 bg-blue-650 hover:bg-blue-700 text-white font-bold rounded-xl transition-all shadow-md">
                                                Pilih Pergi
                                            </a>
                                        @else
                                            <a href="{{ route('customer.booking.create', $flight) }}?passenger_count={{ request('passenger_count', 1) }}&travel_class={{ request('travel_class', 'economy') }}" class="inline-block px-7 py-2.5 bg-blue-650 hover:bg-blue-700 text-white font-bold rounded-xl transition-all shadow-md">
                                                Pilih Penerbangan
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="bg-white rounded-3xl p-12 text-center border border-slate-100 shadow-md">
                        <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-slate-455" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-slate-800 mb-1">Penerbangan Pergi Tidak Ditemukan</h3>
                        <p class="text-slate-500 text-sm">Coba ubah kriteria pencarian Anda</p>
                    </div>
                @endif
            @else
                {{-- Round-trip: Listing Return Flights --}}
                <h2 class="text-2xl font-black text-slate-800 mb-6 flex items-center gap-2">
                    <span>Penerbangan Pulang</span>
                    <span class="text-slate-400 font-normal text-sm">({{ $returnFlights->count() }} pilihan)</span>
                </h2>

                @if($returnFlights->count() > 0)
                    <div class="space-y-4">
                        @foreach($returnFlights as $returnFlight)
                            <div class="bg-white rounded-3xl shadow-md border border-slate-100 p-6 hover:shadow-xl hover:border-slate-200 transition-all">
                                <div class="flex items-center justify-between flex-wrap gap-6">
                                    <div class="flex items-center gap-8 flex-1 min-w-[300px]">
                                        <div class="text-center">
                                            <p class="text-2xl font-black text-slate-800">{{ $returnFlight->departure_time->format('H:i') }}</p>
                                            <p class="text-xs text-slate-455 font-bold uppercase">{{ $returnFlight->departureAirport->iata_code }}</p>
                                        </div>

                                        <div class="flex-1 text-center">
                                            <p class="text-xs text-slate-500 font-bold">{{ $returnFlight->airline->name }}</p>
                                            <div class="flex items-center justify-center gap-2 my-1">
                                                <div class="h-px bg-slate-200 flex-1"></div>
                                                <svg class="w-5 h-5 text-blue-65" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                                </svg>
                                                <div class="h-px bg-slate-200 flex-1"></div>
                                            </div>
                                            <p class="text-[10px] text-slate-455 font-bold uppercase">{{ $returnFlight->flight_number }}</p>
                                        </div>

                                        <div class="text-center">
                                            <p class="text-2xl font-black text-slate-800">{{ $returnFlight->arrival_time->format('H:i') }}</p>
                                            <p class="text-xs text-slate-455 font-bold uppercase">{{ $returnFlight->arrivalAirport->iata_code }}</p>
                                        </div>
                                    </div>

                                    <div class="text-right">
                                        <p class="text-2xl font-black text-blue-600">Rp {{ number_format($returnFlight->display_price ?? $returnFlight->price, 0, ',', '.') }}</p>
                                        <p class="text-xs text-slate-500 font-semibold mb-3">{{ $returnFlight->available_seats }} kursi tersedia</p>
                                        
                                        <a href="{{ route('customer.booking.create', ['flight' => $selectedDepartureId, 'return_flight_id' => $returnFlight->id]) }}?passenger_count={{ request('passenger_count', 1) }}&travel_class={{ request('travel_class', 'economy') }}&trip_type=round_trip&return_date={{ request('return_date') }}" 
                                            class="inline-block px-7 py-2.5 bg-blue-650 hover:bg-blue-700 text-white font-bold rounded-xl transition-all shadow-md">
                                            Pilih Pulang
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="bg-white rounded-3xl p-12 text-center border border-slate-100 shadow-md">
                        <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-slate-455" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-slate-800 mb-1">Penerbangan Pulang Tidak Ditemukan</h3>
                        <p class="text-slate-500 text-sm">Tidak ada jadwal penerbangan pulang untuk rute dan tanggal terpilih.</p>
                    </div>
                @endif
            @endif
        @endif
    </div>

    <script>
        // Toggle return date field based on trip type
        function toggleReturnDate() {
            const tripType = document.querySelector('input[name="trip_type"]:checked');
            const returnDateOverlay = document.getElementById('returnDateOverlay');
            const returnDateInput = document.getElementById('returnDate');
            const returnDateWrapper = document.getElementById('returnDateWrapper');

            if (tripType && tripType.value === 'round_trip') {
                returnDateOverlay.style.display = 'none';
                returnDateInput.removeAttribute('disabled');
                returnDateWrapper.classList.remove('opacity-50', 'pointer-events-none');
            } else {
                returnDateOverlay.style.display = 'flex';
                returnDateInput.setAttribute('disabled', 'disabled');
            }
        }

        function handleReturnDateClick() {
            const tripType = document.querySelector('input[name="trip_type"]:checked');
            if (tripType && tripType.value === 'one_way') {
                document.getElementById('tripTypeRoundTrip').checked = true;
                toggleReturnDate();
                setTimeout(() => {
                    document.getElementById('returnDate').showPicker();
                }, 50);
            }
        }

        // Passenger & Cabin Class Popover Logic
        function togglePassengerPopover(event) {
            event.stopPropagation();
            const popover = document.getElementById('passengerClassPopover');
            popover.classList.toggle('hidden');
        }

        function closePassengerPopover() {
            document.getElementById('passengerClassPopover').classList.add('hidden');
        }

        let passengerCount = parseInt(document.getElementById('passenger_count').value) || 1;
        let selectedClassKey = document.getElementById('travel_class').value || 'economy';
        const classLabels = {
            'economy': 'Ekonomi',
            'premium_economy': 'Premium Ekonomi',
            'business': 'Bisnis',
            'first': 'First Class'
        };

        function adjustPassengers(change) {
            passengerCount = Math.max(1, Math.min(9, passengerCount + change));
            document.getElementById('passengerCountVal').innerText = passengerCount;
            document.getElementById('passenger_count').value = passengerCount;
            updateLabel();
        }

        function selectTravelClass(classKey, label) {
            selectedClassKey = classKey;
            document.getElementById('travel_class').value = classKey;
            
            // Update active styling
            document.querySelectorAll('.class-btn').forEach(btn => {
                btn.classList.remove('bg-blue-50', 'border-blue-500');
            });
            const activeBtn = document.getElementById('btn-class-' + classKey);
            if (activeBtn) {
                activeBtn.classList.add('bg-blue-50', 'border-blue-500');
            }
            updateLabel();
        }

        function updateLabel() {
            const labelBtn = document.getElementById('passengerClassLabel');
            labelBtn.innerText = `${passengerCount} Pax, ${classLabels[selectedClassKey] || selectedClassKey}`;
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('input[name="trip_type"]').forEach(radio => {
                radio.addEventListener('change', toggleReturnDate);
            });

            // Set initial state
            toggleReturnDate();
            selectTravelClass(selectedClassKey, classLabels[selectedClassKey]);

            // Close popover when clicking outside
            document.addEventListener('click', function(event) {
                const popover = document.getElementById('passengerClassPopover');
                const btn = document.getElementById('passengerClassBtn');
                if (popover && !popover.contains(event.target) && !btn.contains(event.target)) {
                    closePassengerPopover();
                }
            });
        });
    </script>
@endsection