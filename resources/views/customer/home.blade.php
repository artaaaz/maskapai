@extends('layouts.customer')

@section('content')
<style>
* {
    box-sizing: border-box;
}

/* Minimalist card style */
.card-minimal {
    border-radius: 12px;
    transition: all 0.2s ease;
}
.card-minimal:hover {
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
}

/* Section heading underline */
.heading-line {
    display: inline-block;
    position: relative;
}
.heading-line::after {
    content: '';
    position: absolute;
    bottom: -6px;
    left: 0;
    width: 40px;
    height: 3px;
    background: #2563EB;
    border-radius: 2px;
}
</style>

{{-- ============================== --}}
{{-- HERO SECTION with image background --}}
{{-- ============================== --}}
<section class="relative min-h-[500px] md:min-h-[600px] flex items-center overflow-hidden">
    {{-- Background image --}}
    <div class="absolute inset-0">
        <img src="{{ asset('images/2.jpg') }}" alt="Background" class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-gradient-to-r from-blue-900/80 via-blue-800/60 to-blue-900/70"></div>
    </div>

    {{-- Content --}}
    <div class="relative w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 md:py-20 z-10">
        <div class="max-w-2xl">
            <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-bold text-white leading-tight mb-4">
                @auth
                    Selamat datang, {{ Auth::user()->name }}
                @else
                    Terbang ke <br>Destinasi Impian
                @endauth
            </h1>
            
            <p class="text-white/80 text-base md:text-lg max-w-lg mb-8">
                @auth
                    Pesan tiket pesawat dengan mudah dan cepat.
                @else
                    Temukan tiket pesawat terbaik untuk perjalanan Anda. Harga bersahabat, proses mudah.
                @endauth
            </p>

            {{-- Search Form --}}
            <div class="bg-white rounded-xl p-4 md:p-6 shadow-lg">
                <form action="{{ route('customer.search') }}" method="GET">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-3">
                        <div class="lg:col-span-3">
                            <label class="block text-xs font-semibold text-gray-500 mb-1">Dari</label>
                            <select name="departure_airport_id" required 
                                    class="w-full h-11 px-3 bg-gray-50 border border-gray-200 rounded-lg text-sm text-black focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                                <option value="" class="text-gray-400">Pilih kota</option>
                                @foreach ($airports as $airport)
                                    <option value="{{ $airport->id }}">{{ $airport->city }} ({{ $airport->iata_code }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="lg:col-span-3">
                            <label class="block text-xs font-semibold text-gray-500 mb-1">Ke</label>
                            <select name="arrival_airport_id" required 
                                    class="w-full h-11 px-3 bg-gray-50 border border-gray-200 rounded-lg text-sm text-black focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                                <option value="" class="text-gray-400">Pilih kota</option>
                                @foreach ($airports as $airport)
                                    <option value="{{ $airport->id }}">{{ $airport->city }} ({{ $airport->iata_code }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="lg:col-span-2">
                            <label class="block text-xs font-semibold text-gray-500 mb-1">Tanggal</label>
                            <input type="date" name="departure_date" required value="{{ now()->format('Y-m-d') }}" 
                                   class="w-full h-11 px-3 bg-gray-50 border border-gray-200 rounded-lg text-sm text-black focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                        </div>
                        <div class="lg:col-span-2">
                            <label class="block text-xs font-semibold text-gray-500 mb-1">Penumpang</label>
                            <select name="passengers" 
                                    class="w-full h-11 px-3 bg-gray-50 border border-gray-200 rounded-lg text-sm text-black focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                                @for($i=1;$i<=6;$i++)
                                    <option value="{{ $i }}">{{ $i }} org</option>
                                @endfor
                            </select>
                        </div>
                        <div class="lg:col-span-2 flex items-end">
                            <button type="submit" 
                                    class="w-full h-11 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition text-sm flex items-center justify-center gap-1.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                Cari
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

{{-- ============================== --}}
{{-- STATS SECTION (for logged in users) --}}
{{-- ============================== --}}
@auth
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-8 relative z-20 mb-12">
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="card-minimal bg-white rounded-xl border border-gray-200 p-5 flex items-center gap-4 shadow-sm">
            <div class="w-12 h-12 bg-blue-50 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-black">{{ $myBookings ?? 0 }}</p>
                <p class="text-xs text-gray-500">Total Booking</p>
            </div>
        </div>
        <div class="card-minimal bg-white rounded-xl border border-gray-200 p-5 flex items-center gap-4 shadow-sm">
            <div class="w-12 h-12 bg-blue-50 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-black">{{ $myTrips ?? 0 }}</p>
                <p class="text-xs text-gray-500">Selesai</p>
            </div>
        </div>
        <div class="card-minimal bg-white rounded-xl border border-gray-200 p-5 flex items-center gap-4 shadow-sm">
            <div class="w-12 h-12 bg-blue-50 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-black">Rp{{ number_format($mySpent ?? 0, 0, ',', '.') }}</p>
                <p class="text-xs text-gray-500">Pengeluaran</p>
            </div>
        </div>
    </div>
</div>
@endauth

{{-- ============================== --}}
{{-- HOW IT WORKS --}}
{{-- ============================== --}}
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-2xl sm:text-3xl font-bold text-black heading-line">Cara Pesan</h2>
            <p class="text-gray-500 mt-4">Cukup 3 langkah mudah</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="card-minimal text-center p-8 bg-gray-50 rounded-xl">
                <div class="w-14 h-14 bg-blue-600 rounded-lg flex items-center justify-center mx-auto mb-5">
                    <span class="text-white text-xl font-bold">1</span>
                </div>
                <h3 class="font-semibold text-black text-lg mb-2">Cari Penerbangan</h3>
                <p class="text-sm text-gray-500">Masukkan kota asal, tujuan, dan tanggal terbang</p>
            </div>
            <div class="card-minimal text-center p-8 bg-gray-50 rounded-xl">
                <div class="w-14 h-14 bg-blue-600 rounded-lg flex items-center justify-center mx-auto mb-5">
                    <span class="text-white text-xl font-bold">2</span>
                </div>
                <h3 class="font-semibold text-black text-lg mb-2">Pilih & Pesan</h3>
                <p class="text-sm text-gray-500">Pilih kursi favorit dan lakukan pemesanan</p>
            </div>
            <div class="card-minimal text-center p-8 bg-gray-50 rounded-xl">
                <div class="w-14 h-14 bg-blue-600 rounded-lg flex items-center justify-center mx-auto mb-5">
                    <span class="text-white text-xl font-bold">3</span>
                </div>
                <h3 class="font-semibold text-black text-lg mb-2">Terbang</h3>
                <p class="text-sm text-gray-500">Bayar dan dapatkan e-ticket, siap terbang</p>
            </div>
        </div>
    </div>
</section>

{{-- ============================== --}}
{{-- PROMO SECTION --}}
{{-- ============================== --}}
@if(isset($activePromos) && $activePromos->count() > 0)
<section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h2 class="text-2xl sm:text-3xl font-bold text-black heading-line">Promo Spesial</h2>
            </div>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($activePromos as $promo)
            <div class="card-minimal bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
                <div class="flex items-start justify-between mb-3">
                    <div>
                        <h3 class="font-semibold text-black">{{ $promo->name }}</h3>
                        <span class="text-xs text-gray-400">Kode: {{ $promo->code }}</span>
                    </div>
                    <span class="px-3 py-1 bg-blue-600 text-white text-sm font-semibold rounded-lg">
                        {{ $promo->discount_type == 'percentage' ? $promo->discount_value.'%' : 'Rp'.number_format($promo->discount_value,0,',','.') }} OFF
                    </span>
                </div>
                <p class="text-sm text-gray-600 mb-2">{{ $promo->description }}</p>
                <div class="text-xs text-gray-400">
                    <span>Berlaku hingga {{ $promo->valid_until->format('d M Y') }}</span>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ============================== --}}
{{-- FLIGHTS SECTION --}}
{{-- ============================== --}}
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h2 class="text-2xl sm:text-3xl font-bold text-black heading-line">Jadwal Hari Ini</h2>
                <p class="text-gray-500 mt-1">Pesan sebelum kehabisan</p>
            </div>
            @if(isset($availableFlights) && $availableFlights->count() > 0)
                <span class="text-sm font-semibold text-blue-600 bg-blue-50 px-4 py-2 rounded-lg">{{ $availableFlights->count() }} penerbangan</span>
            @endif
        </div>

        @if(isset($availableFlights) && $availableFlights->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($availableFlights as $flight)
            <div class="card-minimal bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm">
                <div class="bg-blue-600 px-5 py-4">
                    <div class="flex items-center justify-between text-white">
                        <div>
                            <p class="font-semibold text-sm">{{ $flight->flight_number }}</p>
                            <p class="text-xs text-blue-200">{{ $flight->airline->name ?? '' }}</p>
                        </div>
                        <span class="bg-white/20 text-xs font-medium px-3 py-1 rounded-full">
                            {{ $flight->available_seats }} kursi
                        </span>
                    </div>
                </div>
                <div class="p-5">
                    <div class="flex items-center justify-between mb-4">
                        <div class="text-center">
                            <p class="text-xl font-bold text-black">{{ \Carbon\Carbon::parse($flight->departure_time)->format('H:i') }}</p>
                            <p class="text-xs text-gray-400">{{ $flight->departureAirport->iata_code ?? '' }}</p>
                        </div>
                        <div class="flex-1 mx-3">
                            <div class="h-px bg-gray-200 relative">
                                <div class="w-3 h-3 bg-blue-600 rounded-full absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2"></div>
                            </div>
                            <p class="text-xs text-gray-400 text-center mt-1">{{ $flight->duration_formatted }}</p>
                        </div>
                        <div class="text-center">
                            <p class="text-xl font-bold text-black">{{ \Carbon\Carbon::parse($flight->arrival_time)->format('H:i') }}</p>
                            <p class="text-xs text-gray-400">{{ $flight->arrivalAirport->iata_code ?? '' }}</p>
                        </div>
                    </div>
                    <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                        <div>
                            <p class="text-xs text-gray-400">Harga/orang</p>
                            <p class="text-xl font-bold text-black">Rp{{ number_format($flight->price, 0, ',', '.') }}</p>
                        </div>
                        <a href="{{ route('customer.booking.create', $flight) }}" 
                           class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition text-sm">
                            Pesan
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="bg-gray-50 rounded-xl border border-gray-200 p-12 text-center">
            <p class="text-black font-semibold text-lg">Belum Ada Penerbangan</p>
            <p class="text-gray-500 text-sm mt-1">Gunakan form pencarian di atas</p>
        </div>
        @endif
    </div>
</section>

{{-- ============================== --}}
{{-- WHY US --}}
{{-- ============================== --}}
<section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-2xl sm:text-3xl font-bold text-black heading-line">Kenapa Pilih Kami?</h2>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-5">
            <div class="card-minimal text-center p-6 bg-white rounded-xl border border-gray-200">
                <div class="w-12 h-12 bg-blue-50 rounded-lg flex items-center justify-center mx-auto mb-4">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <h3 class="font-semibold text-black text-sm mb-1">Respon Cepat</h3>
                <p class="text-xs text-gray-500">Booking cepat & mudah</p>
            </div>
            <div class="card-minimal text-center p-6 bg-white rounded-xl border border-gray-200">
                <div class="w-12 h-12 bg-blue-50 rounded-lg flex items-center justify-center mx-auto mb-4">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                </div>
                <h3 class="font-semibold text-black text-sm mb-1">Aman</h3>
                <p class="text-xs text-gray-500">Transaksi terjamin</p>
            </div>
            <div class="card-minimal text-center p-6 bg-white rounded-xl border border-gray-200">
                <div class="w-12 h-12 bg-blue-50 rounded-lg flex items-center justify-center mx-auto mb-4">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </div>
                <h3 class="font-semibold text-black text-sm mb-1">Harga Terbaik</h3>
                <p class="text-xs text-gray-500">Harga kompetitif</p>
            </div>
            <div class="card-minimal text-center p-6 bg-white rounded-xl border border-gray-200">
                <div class="w-12 h-12 bg-blue-50 rounded-lg flex items-center justify-center mx-auto mb-4">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <h3 class="font-semibold text-black text-sm mb-1">24 Jam</h3>
                <p class="text-xs text-gray-500">Layanan konsumen</p>
            </div>
        </div>
    </div>
</section>

{{-- ============================== --}}
{{-- TESTIMONIALS --}}
{{-- ============================== --}}
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-2xl sm:text-3xl font-bold text-black heading-line">Kata Mereka</h2>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            <div class="card-minimal bg-gray-50 rounded-xl border border-gray-200 p-6">
                <div class="flex items-center gap-1 mb-3">
                    <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                </div>
                <p class="text-sm text-gray-600 mb-4">"Gampang banget pesennya! Dapet tiket murah ke Bali. Recomended!"</p>
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 bg-blue-100 rounded-full flex items-center justify-center font-semibold text-blue-600 text-sm">SA</div>
                    <div>
                        <p class="font-semibold text-black text-sm">Siti Aminah</p>
                        <p class="text-xs text-gray-400">Jakarta → Bali</p>
                    </div>
                </div>
            </div>
            <div class="card-minimal bg-gray-50 rounded-xl border border-gray-200 p-6">
                <div class="flex items-center gap-1 mb-3">
                    <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                </div>
                <p class="text-sm text-gray-600 mb-4">"Pelayanannya ramah! Harganya juga murah. Pasti bakal repeat order!"</p>
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 bg-blue-100 rounded-full flex items-center justify-center font-semibold text-blue-600 text-sm">BR</div>
                    <div>
                        <p class="font-semibold text-black text-sm">Budi Raharjo</p>
                        <p class="text-xs text-gray-400">Jakarta → Surabaya</p>
                    </div>
                </div>
            </div>
            <div class="card-minimal bg-gray-50 rounded-xl border border-gray-200 p-6">
                <div class="flex items-center gap-1 mb-3">
                    <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                </div>
                <p class="text-sm text-gray-600 mb-4">"Prosesnya cepet! E-ticket langsung dikirim ke email. Mantul!"</p>
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 bg-blue-100 rounded-full flex items-center justify-center font-semibold text-blue-600 text-sm">DK</div>
                    <div>
                        <p class="font-semibold text-black text-sm">Dewi Kartika</p>
                        <p class="text-xs text-gray-400">Jakarta → Medan</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ============================== --}}
{{-- CTA BANNER --}}
{{-- ============================== --}}
<section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-blue-600 rounded-xl p-8 md:p-12 text-center">
            <h2 class="text-2xl sm:text-3xl font-bold text-white mb-3">Siap Terbang?</h2>
            <p class="text-blue-100 text-lg max-w-lg mx-auto mb-6">Ayo segera pesan tiketmu dan nikmati pengalaman terbang yang menyenangkan!</p>
            <a href="{{ route('customer.search') }}" 
               class="inline-flex items-center gap-2 px-8 py-3 bg-white text-blue-600 font-semibold rounded-lg hover:bg-blue-50 transition shadow-lg">
                Pesan Sekarang
            </a>
        </div>
    </div>
</section>

{{-- Scripts --}}
<script>
function toggleReturn() {
    const oneWay = document.querySelector('input[name="trip_type"][value="one_way"]').checked;
    const ret = document.getElementById('retDate');
    const ov = document.getElementById('retOverlay');
    if (oneWay) {
        ret.disabled = true;
        ret.classList.add('bg-blue-100','border-blue-200','text-blue-400','cursor-not-allowed');
        ret.classList.remove('bg-blue-50','border-blue-200','text-blue-900','cursor-pointer');
        ov.style.display = 'flex';
        ret.removeAttribute('name');
    } else {
        ret.disabled = false;
        ret.classList.remove('bg-blue-100','border-blue-200','text-blue-400','cursor-not-allowed');
        ret.classList.add('bg-blue-50','border-blue-200','text-blue-900','cursor-pointer');
        ov.style.display = 'none';
        ret.setAttribute('name', 'return_date');
        if (!ret.value) {
            const d = new Date(document.getElementById('depDate').value);
            d.setDate(d.getDate() + 1);
            ret.value = d.toISOString().split('T')[0];
        }
    }
}
</script>
@endsection