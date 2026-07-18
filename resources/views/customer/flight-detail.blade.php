@extends('layouts.customer')

@section('content')
{{-- HERO SECTION --}}
<div class="bg-gradient-to-br from-blue-700 via-blue-800 to-indigo-900 min-h-[280px] flex items-center relative overflow-hidden">
    <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNjAiIGhlaWdodD0iNjAiIHZpZXdCb3g9IjAgMCA2MCA2MCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48ZyBmaWxsPSJub25lIiBmaWxsLXJ1bGU9ImV2ZW5vZGQiPjxnIGZpbGw9IiNmZmYiIGZpbGwtb3BhY2l0eT0iMC4wNSI+PHBhdGggZD0iTTM2IDE4bDEyIDZ2MTJsLTEyIDYtMTItNlYyNGwxMi02eiIvPjwvZz48L2c+PC9zdmc+')] opacity-20"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full relative z-10 py-8">
        <div class="flex items-center gap-2 mb-4">
            <a href="{{ route('customer.flights.results', request()->query()) }}" class="text-white/60 hover:text-white transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            </a>
            <span class="text-white/50 text-xs font-medium">Kembali ke hasil pencarian</span>
        </div>

        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div class="flex-1">
                <h1 class="text-2xl md:text-3xl lg:text-4xl font-black text-white leading-tight">
                    {{ $flight->departureAirport->city }} ({{ $flight->departureAirport->iata_code }}) 
                    <span class="text-yellow-400 mx-1">→</span> 
                    {{ $flight->arrivalAirport->city }} ({{ $flight->arrivalAirport->iata_code }})
                </h1>
                <div class="flex flex-wrap items-center gap-x-4 gap-y-1 mt-2">
                    <span class="text-white/70 text-sm">
                        <svg class="w-4 h-4 inline-block mr-1 -mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        {{ \Carbon\Carbon::parse($flight->departure_time)->translatedFormat('l, d F Y') }}
                    </span>
                    <span class="text-white/50 text-sm">•</span>
                    <span class="text-white/70 text-sm">{{ $passengerCount ?? 1 }} Dewasa</span>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- MAIN CONTENT --}}
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 -mt-[60px] relative z-20 pb-16">

        {{-- KIRI: Informasi Penerbangan (8 kolom) --}}
        <div class="lg:col-span-8 space-y-6">

            {{-- CARD FLIGHT INFO --}}
            <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden">
                <div class="p-4 md:p-6 border-b border-slate-100">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            @if($flight->airline->logo)
                                <img src="{{ asset('storage/' . $flight->airline->logo) }}" alt="{{ $flight->airline->name }}" class="h-11 w-11 rounded-xl object-cover border border-slate-200">
                            @else
                                <div class="h-11 w-11 rounded-xl bg-gradient-to-br from-blue-500 to-blue-700 flex items-center justify-center text-white font-bold text-sm shadow-md">
                                    {{ substr($flight->airline->name, 0, 2) }}
                                </div>
                            @endif
                            <div>
                                <p class="font-bold text-slate-800 text-base">{{ $flight->airline->name }}</p>
                                <p class="text-xs text-slate-500">{{ $flight->flight_number }}</p>
                            </div>
                        </div>
                        <span class="px-3 py-1.5 bg-blue-50 text-blue-700 text-xs font-bold rounded-lg border border-blue-100">{{ $flight->flight_number }}</span>
                    </div>
                </div>

                {{-- Flight Schedule --}}
                <div class="p-4 md:p-6">
                    <div class="flex items-start gap-3 md:gap-6">
                        <div class="text-center min-w-[80px] md:min-w-[100px]">
                            <p class="text-2xl md:text-3xl font-black text-slate-800">{{ \Carbon\Carbon::parse($flight->departure_time)->format('H:i') }}</p>
                            <p class="text-xs font-semibold text-slate-500 mt-1">{{ \Carbon\Carbon::parse($flight->departure_time)->format('d M Y') }}</p>
                            <p class="text-xs font-bold text-slate-400 uppercase mt-1">{{ $flight->departureAirport->iata_code }}</p>
                            <p class="text-[11px] text-slate-400 truncate max-w-[80px]">{{ $flight->departureAirport->city }}</p>
                        </div>

                        <div class="flex-1 flex flex-col items-center pt-1">
                            <div class="relative w-full">
                                <div class="h-[3px] bg-gradient-to-r from-blue-600 via-blue-400 to-blue-600 w-full rounded-full"></div>
                                <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2">
                                    <div class="w-9 h-9 bg-white border-2 border-blue-600 rounded-full flex items-center justify-center shadow-md">
                                        <svg class="w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z"></path>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center gap-2 mt-2">
                                <span class="text-xs font-semibold text-slate-500">{{ $flight->duration_formatted }}</span>
                                <span class="text-xs text-slate-300">·</span>
                                <span class="text-xs font-medium text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full">Langsung</span>
                            </div>
                        </div>

                        <div class="text-center min-w-[80px] md:min-w-[100px]">
                            <p class="text-2xl md:text-3xl font-black text-slate-800">{{ \Carbon\Carbon::parse($flight->arrival_time)->format('H:i') }}</p>
                            <p class="text-xs font-semibold text-slate-500 mt-1">{{ \Carbon\Carbon::parse($flight->arrival_time)->format('d M Y') }}</p>
                            <p class="text-xs font-bold text-slate-400 uppercase mt-1">{{ $flight->arrivalAirport->iata_code }}</p>
                            <p class="text-[11px] text-slate-400 truncate max-w-[80px]">{{ $flight->arrivalAirport->city }}</p>
                        </div>
                    </div>
                </div>

                {{-- Flight Details Grid --}}
                <div class="border-t border-slate-100 bg-slate-50/80 p-4 md:p-6">
                    <h4 class="text-sm font-bold text-slate-700 mb-4 flex items-center gap-2">
                        <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Informasi Penerbangan
                    </h4>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                        <div class="bg-white rounded-xl p-3 border border-slate-200">
                            <p class="text-[11px] text-slate-400 font-medium uppercase tracking-wider">Tipe Pesawat</p>
                            <p class="text-sm font-bold text-slate-800 mt-1">{{ $flight->airplane->model ?? ($flight->airplane->type ?? '—') }}</p>
                        </div>
                        <div class="bg-white rounded-xl p-3 border border-slate-200">
                            <p class="text-[11px] text-slate-400 font-medium uppercase tracking-wider">Registrasi</p>
                            <p class="text-sm font-bold text-slate-800 mt-1">{{ $flight->airplane->registration_number ?? '—' }}</p>
                        </div>
                        <div class="bg-white rounded-xl p-3 border border-slate-200">
                            <p class="text-[11px] text-slate-400 font-medium uppercase tracking-wider">Bagasi</p>
                            <p class="text-sm font-bold text-slate-800 mt-1">{{ $flight->baggage_allowance_kg ?? 0 }} kg</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- CLASS SELECTION CARDS --}}
            <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden">
                <div class="p-4 md:p-6 border-b border-slate-100">
                    <h3 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                        Pilih Kelas Penerbangan
                    </h3>
                    <p class="text-sm text-slate-500 mt-1">Pilih kelas yang sesuai dengan kebutuhan Anda</p>
                </div>

                <div class="p-4 md:p-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        @forelse($flight->flightClasses->sortBy('price') as $class)
                            @php
                                $isAvailable = $class->available_quota >= $passengerCount;
                                $isSelected = ($selectedFlightClass && $selectedFlightClass->id === $class->id) || 
                                    (request('flight_class_id') == $class->id);
                                $classLabel = ucfirst(str_replace('_', ' ', $class->class_name));
                                $classIcon = match($class->class_name) {
                                    'economy' => 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4',
                                    'business' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
                                    'first' => 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z',
                                    default => 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
                                };
                                $classColor = match($class->class_name) {
                                    'economy' => 'blue',
                                    'business' => 'purple',
                                    'first' => 'amber',
                                    default => 'slate',
                                };
                            @endphp
                            <div class="relative">
                                <input type="radio" name="flight_class_id" value="{{ $class->id }}" 
                                    id="class_{{ $class->id }}"
                                    class="sr-only flight-class-radio"
                                    {{ $isSelected ? 'checked' : '' }}
                                    {{ !$isAvailable ? 'disabled' : '' }}
                                    data-class-id="{{ $class->id }}"
                                    data-class-name="{{ $class->class_name }}"
                                    data-price="{{ $class->price }}"
                                    data-seat-quota="{{ $class->seat_quota }}"
                                    onchange="selectClass(this)">
                                <label for="class_{{ $class->id }}" 
                                    id="label_class_{{ $class->id }}"
                                    class="class-card block p-5 rounded-2xl border-2 cursor-pointer transition-all duration-200
                                    @if(!$isAvailable) opacity-50 cursor-not-allowed bg-slate-50 border-slate-200
                                    @elseif($isSelected) border-blue-500 bg-blue-50 shadow-md ring-2 ring-blue-500 ring-offset-2
                                    @else border-slate-200 bg-white hover:border-blue-300 hover:shadow-md
                                    @endif">
                                    
                                    {{-- Class Icon & Name --}}
                                    <div class="flex items-center gap-3 mb-3">
                                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-{{ $classColor }}-500 to-{{ $classColor }}-700 flex items-center justify-center">
                                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $classIcon }}"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="font-bold text-slate-800">{{ $classLabel }}</p>
                                            <p class="text-xs text-slate-400">{{ $class->seat_quota }} kursi tersedia</p>
                                        </div>
                                    </div>

                                    {{-- Price --}}
                                    <div class="mt-2">
                                        <p class="text-2xl font-black text-slate-800">Rp{{ number_format($class->price, 0, ',', '.') }}</p>
                                        <p class="text-xs text-slate-400">per orang</p>
                                    </div>

                                    {{-- Availability --}}
                                    <div class="mt-3 flex items-center gap-2">
                                        @if($isAvailable)
                                            <span class="text-xs font-semibold text-emerald-600 bg-emerald-50 px-2.5 py-1 rounded-full">
                                                {{ $class->available_quota }} kursi tersisa
                                            </span>
                                        @else
                                            <span class="text-xs font-semibold text-red-600 bg-red-50 px-2.5 py-1 rounded-full">
                                                Habis
                                            </span>
                                        @endif
                                    </div>
                                </label>
                            </div>
                        @empty
                            <div class="col-span-full text-center py-8 text-slate-400">
                                Belum ada kelas yang tersedia untuk penerbangan ini.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        {{-- KANAN: Ringkasan Pemesanan (4 kolom) --}}
        <div class="lg:col-span-4">
            <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden sticky top-6">
                <div class="p-4 md:p-5 border-b border-slate-100 bg-gradient-to-r from-blue-50 to-indigo-50">
                    <h3 class="font-bold text-slate-800 flex items-center gap-2">
                        <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                        Ringkasan Pemesanan
                    </h3>
                </div>

                <div class="p-4 md:p-5 space-y-4">
                    {{-- Rute --}}
                    <div>
                        <p class="text-[11px] text-slate-400 font-medium uppercase tracking-wider">Rute Penerbangan</p>
                        <p class="text-sm font-bold text-slate-800 mt-1">{{ $flight->departureAirport->city }} ({{ $flight->departureAirport->iata_code }}) → {{ $flight->arrivalAirport->city }} ({{ $flight->arrivalAirport->iata_code }})</p>
                    </div>

                    {{-- Tanggal & Waktu --}}
                    <div>
                        <p class="text-[11px] text-slate-400 font-medium uppercase tracking-wider">Keberangkatan</p>
                        <p class="text-sm font-bold text-slate-800 mt-1">{{ \Carbon\Carbon::parse($flight->departure_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($flight->departure_time)->translatedFormat('l, d M') }}</p>
                    </div>

                    {{-- Penumpang --}}
                    <div>
                        <p class="text-[11px] text-slate-400 font-medium uppercase tracking-wider">Penumpang</p>
                        <p class="text-sm font-bold text-slate-800 mt-1">{{ $passengerCount ?? 1 }} Dewasa</p>
                    </div>

                    {{-- Selected Class Info --}}
                    <div id="selectedClassInfo" class="{{ $selectedFlightClass ? '' : 'hidden' }}">
                        <p class="text-[11px] text-slate-400 font-medium uppercase tracking-wider">Kelas Dipilih</p>
                        <div class="flex items-center gap-2 mt-1">
                            <span id="selectedClassBadge" class="px-3 py-1 bg-blue-100 text-blue-700 text-xs font-bold rounded-lg">
                                {{ $selectedFlightClass ? ucfirst(str_replace('_', ' ', $selectedFlightClass->class_name)) : '—' }}
                            </span>
                            <span id="selectedClassPrice" class="text-sm font-bold text-blue-600">
                                Rp{{ number_format($selectedFlightClass?->price ?? 0, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>

                    <hr class="border-slate-200">

                    {{-- Harga --}}
                    <div class="space-y-2">
                        <div class="flex justify-between text-sm">
                            <span class="text-slate-500">Harga Tiket</span>
                            <span id="ticketPriceDisplay" class="font-bold text-slate-800">
                                Rp{{ number_format($selectedFlightClass?->price ?? 0, 0, ',', '.') }}
                            </span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-slate-500">Jumlah Penumpang</span>
                            <span class="font-bold text-slate-800">{{ $passengerCount ?? 1 }}x</span>
                        </div>
                        <hr class="border-slate-200">
                        <div class="flex justify-between items-center">
                            <span class="font-bold text-slate-800">Subtotal</span>
                            <span id="totalPriceDisplay" class="text-xl font-black text-blue-600">
                                Rp{{ number_format(($selectedFlightClass?->price ?? 0) * ($passengerCount ?? 1), 0, ',', '.') }}
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Tombol Lanjut --}}
                <div class="p-4 md:p-5 border-t border-slate-100 bg-slate-50">
                    @auth
                        <form id="bookingForm" action="{{ route('customer.flight-detail.seat-selection', $flight) }}" method="GET" class="space-y-2">
                            <input type="hidden" name="passenger_count" value="{{ $passengerCount ?? 1 }}">
                            <input type="hidden" name="flight_class_id" id="selectedClassInput" value="{{ $selectedFlightClass?->id ?? '' }}">
                            <input type="hidden" name="travel_class" id="travelClassInput" value="{{ $selectedFlightClass?->class_name ?? '' }}">
                            <button type="submit" id="continueBtn"
                                class="w-full py-3.5 rounded-xl font-bold text-white transition-all duration-200 shadow-lg text-sm 
                                {{ $selectedFlightClass ? 'bg-blue-600 hover:bg-blue-700 cursor-pointer' : 'bg-slate-300 cursor-not-allowed' }}"
                                {{ $selectedFlightClass ? '' : 'disabled' }}>
                                {{ $selectedFlightClass ? 'Lanjut Pilih Kursi' : 'Pilih Kelas Terlebih Dahulu' }}
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}?redirect={{ url()->current() }}"
                            class="block w-full py-3.5 bg-blue-600 hover:bg-blue-700 text-white font-bold text-center rounded-xl transition-all shadow-lg text-sm">
                            Login untuk Memesan
                        </a>
                    @endauth
                    <p class="text-[11px] text-slate-400 text-center mt-2">Harga belum termasuk pajak & biaya layanan</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let selectedClassId = {{ $selectedFlightClass?->id ?? 'null' }};
let selectedClassName = '{{ $selectedFlightClass ? ucfirst(str_replace('_', ' ', $selectedFlightClass->class_name)) : '' }}';
let selectedClassPrice = {{ $selectedFlightClass?->price ?? 0 }};
let passengerCount = {{ $passengerCount ?? 1 }};

// Card style definitions
const CARD_BASE = 'class-card block p-5 rounded-2xl border-2 cursor-pointer transition-all duration-200';
const CARD_ACTIVE = 'border-blue-500 bg-blue-50 shadow-md ring-2 ring-blue-500 ring-offset-2';
const CARD_INACTIVE = 'border-slate-200 bg-white hover:border-blue-300 hover:shadow-md';
const CARD_DISABLED = 'opacity-50 cursor-not-allowed bg-slate-50 border-slate-200';

function selectClass(radioElement) {
    const classId = parseInt(radioElement.dataset.classId);
    const className = radioElement.dataset.className;
    const price = parseInt(radioElement.dataset.price);
    const isDisabled = radioElement.disabled;
    
    if (isDisabled) return;
    
    selectedClassId = classId;
    selectedClassName = className.charAt(0).toUpperCase() + className.slice(1).replace(/_/g, ' ');
    selectedClassPrice = price;
    
    // Update ALL card visuals - only the selected one gets active styles
    document.querySelectorAll('.flight-class-radio').forEach(function(radio) {
        var label = document.getElementById('label_class_' + radio.dataset.classId);
        if (!label) return;
        
        if (radio.disabled) {
            label.className = CARD_BASE + ' ' + CARD_DISABLED;
        } else if (parseInt(radio.dataset.classId) === classId) {
            label.className = CARD_BASE + ' ' + CARD_ACTIVE;
        } else {
            label.className = CARD_BASE + ' ' + CARD_INACTIVE;
        }
    });
    
    // Update summary sidebar
    document.getElementById('selectedClassInfo').classList.remove('hidden');
    document.getElementById('selectedClassBadge').textContent = selectedClassName;
    document.getElementById('selectedClassPrice').textContent = 'Rp' + new Intl.NumberFormat('id-ID').format(price);
    document.getElementById('ticketPriceDisplay').textContent = 'Rp' + new Intl.NumberFormat('id-ID').format(price);
    document.getElementById('totalPriceDisplay').textContent = 'Rp' + new Intl.NumberFormat('id-ID').format(price * passengerCount);
    document.getElementById('selectedClassInput').value = classId;
    document.getElementById('travelClassInput').value = className;
    
    // Enable button
    const btn = document.getElementById('continueBtn');
    btn.disabled = false;
    btn.className = 'w-full py-3.5 rounded-xl font-bold text-white transition-all duration-200 shadow-lg text-sm bg-blue-600 hover:bg-blue-700 cursor-pointer';
    btn.textContent = 'Lanjut Pilih Kursi';
}
</script>
@endsection