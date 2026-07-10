@extends('layouts.customer')

@section('content')
<div class="bg-slate-50 min-h-screen py-8">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- Header --}}
        <div class="mb-8">
            <h1 class="text-3xl font-black text-slate-800 mb-2">Pilih Kursi Penerbangan</h1>
            <p class="text-slate-600">Pilih kursi yang Anda inginkan untuk penerbangan ini</p>
        </div>

        {{-- Flight Info --}}
        <div class="bg-white rounded-2xl shadow-lg p-6 mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-2xl font-bold text-slate-800">{{ $booking->flight->flight_number }}</p>
                    <p class="text-slate-600">{{ $booking->flight->airline->name }}</p>
                </div>
                <div class="text-center">
                    <p class="text-3xl font-black text-blue-600">{{ $booking->flight->departure_time->format('H:i') }}</p>
                    <p class="text-sm text-slate-500">{{ $booking->flight->departureAirport->iata_code }}</p>
                </div>
                <div class="text-center">
                    <svg class="w-8 h-8 text-slate-400 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                    </svg>
                    <p class="text-xs text-slate-500">{{ $booking->flight->duration }} menit</p>
                </div>
                <div class="text-center">
                    <p class="text-3xl font-black text-blue-600">{{ $booking->flight->arrival_time->format('H:i') }}</p>
                    <p class="text-sm text-slate-500">{{ $booking->flight->arrivalAirport->iata_code }}</p>
                </div>
            </div>
        </div>

        {{-- Legend --}}
        <div class="bg-white rounded-2xl shadow-sm p-4 mb-8 flex gap-6">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 bg-green-500 rounded-lg"></div>
                <span class="text-sm font-semibold text-slate-700">Tersedia</span>
            </div>
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 bg-red-500 rounded-lg"></div>
                <span class="text-sm font-semibold text-slate-700">Terisi</span>
            </div>
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 bg-blue-500 rounded-lg"></div>
                <span class="text-sm font-semibold text-slate-700">Terpilih</span>
            </div>
        </div>

        <form action="{{ route('customer.booking.store-seat', $booking) }}" method="POST" id="seatForm">
            @csrf
            
            @foreach(['first', 'business', 'premium_economy', 'economy'] as $class)
                @if(isset($seats[$class]))
                    <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
                        <h3 class="text-xl font-bold text-slate-800 mb-4 capitalize">
                            @php
                                $classNames = [
                                    'first' => 'First Class',
                                    'business' => 'Business Class',
                                    'premium_economy' => 'Premium Economy',
                                    'economy' => 'Economy'
                                ];
                            @endphp
                            {{ $classNames[$class] }}
                        </h3>

                        {{-- Seat Map --}}
                        <div class="grid grid-cols-6 gap-2 max-w-2xl mx-auto">
                            @php
                                $rows = $seats[$class]->groupBy(function($seat) {
                                    return preg_replace('/[A-F]/', '', $seat->seat_number);
                                })->sortKeys();
                            @endphp

                            @foreach($rows as $rowNumber => $rowSeats)
                                <div class="col-span-6 grid grid-cols-6 gap-2 mb-2">
                                    @foreach(['A', 'B', 'C', '', 'D', 'E', 'F'] as $index => $seatLetter)
                                        @if($seatLetter)
                                            @php
                                                $seatNumber = $rowNumber . $seatLetter;
                                                $seat = $rowSeats->firstWhere('seat_number', $seatNumber);
                                            @endphp
                                            
                                            @if($seat)
                                                <button type="button"
                                                        onclick="toggleSeat({{ $seat->id }}, '{{ $seat->status }}')"
                                                        class="seat-btn h-12 rounded-lg font-bold text-sm transition-all
                                                            @if($seat->status === 'booked')
                                                                bg-red-500 text-white cursor-not-allowed
                                                            @else
                                                                bg-green-500 text-white hover:bg-green-600 cursor-pointer
                                                            @endif"
                                                        data-seat-id="{{ $seat->id }}"
                                                        data-seat-number="{{ $seat->seat_number }}"
                                                        {{ $seat->status === 'booked' ? 'disabled' : '' }}>
                                                    {{ $seat->seat_number }}
                                                </button>
                                            @else
                                                <div class="h-12"></div>
                                            @endif
                                        @else
                                            <div class="h-12 w-8"></div> {{-- Gap aisle --}}
                                        @endif
                                    @endforeach
                                </div>
                            @endforeach
                        </div>

                        <div class="flex justify-center gap-8 mt-4 text-sm">
                            <span class="text-slate-600">Window</span>
                            <span class="text-slate-600">Middle</span>
                            <span class="text-slate-600">Aisle</span>
                            <span class="text-slate-600">Aisle</span>
                            <span class="text-slate-600">Middle</span>
                            <span class="text-slate-600">Window</span>
                        </div>
                    </div>
                @endif
            @endforeach

            {{-- Selected Seats Summary --}}
            <div class="bg-white rounded-2xl shadow-lg p-6 mb-6 sticky bottom-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-slate-600 mb-1">Kursi Terpilih:</p>
                        <p class="text-xl font-bold text-slate-800" id="selectedSeats">-</p>
                    </div>
                    <button type="submit" 
                            id="submitBtn"
                            disabled
                            class="px-8 py-3 bg-blue-600 hover:bg-blue-700 disabled:bg-slate-300 disabled:cursor-not-allowed text-white font-bold rounded-xl transition-colors shadow-lg">
                        Simpan Pilihan Kursi
                    </button>
                </div>
            </div>

            <input type="hidden" name="seats" id="selectedSeatsInput">
        </form>
    </div>
</div>

<script>
let selectedSeats = [];

function toggleSeat(seatId, status) {
    if (status === 'booked') return;

    const index = selectedSeats.indexOf(seatId);
    const btn = document.querySelector(`[data-seat-id="${seatId}"]`);
    const seatNumber = btn.dataset.seatNumber;

    if (index > -1) {
        // Deselect
        selectedSeats.splice(index, 1);
        btn.classList.remove('bg-blue-500');
        btn.classList.add('bg-green-500');
    } else {
        // Select
        selectedSeats.push(seatId);
        btn.classList.remove('bg-green-500');
        btn.classList.add('bg-blue-500');
    }

    updateSelectedSeatsDisplay();
}

function updateSelectedSeatsDisplay() {
    const display = document.getElementById('selectedSeats');
    const input = document.getElementById('selectedSeatsInput');
    const submitBtn = document.getElementById('submitBtn');

    const seatNumbers = [];
    selectedSeats.forEach(seatId => {
        const btn = document.querySelector(`[data-seat-id="${seatId}"]`);
        seatNumbers.push(btn.dataset.seatNumber);
    });

    if (seatNumbers.length > 0) {
        display.textContent = seatNumbers.join(', ');
        input.value = JSON.stringify(selectedSeats);
        submitBtn.disabled = false;
    } else {
        display.textContent = '-';
        input.value = '';
        submitBtn.disabled = true;
    }
}
</script>
@endsection