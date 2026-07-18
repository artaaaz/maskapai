@extends('layouts.customer')

@section('content')
<x-layouts.customer>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <h1 class="text-3xl font-bold text-slate-800 mb-8">Booking Saya</h1>

        @if($bookings->count() > 0)
            <div class="space-y-4">
                @foreach($bookings as $booking)
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <div class="flex items-center justify-between flex-wrap gap-4">
                        <div class="flex items-center gap-4">
                            <div class="w-16 h-16 bg-blue-100 rounded-xl flex items-center justify-center">
                                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="font-bold text-slate-800">{{ $booking->booking_code }}</p>
                                <p class="text-sm text-slate-500">{{ $booking->flight->airline->name }} - {{ $booking->flight->flight_number }}</p>
                                <p class="text-xs text-slate-400">{{ $booking->flight->departureAirport->iata_code }} → {{ $booking->flight->arrivalAirport->iata_code }}</p>
                            </div>
                        </div>

                        <div class="text-right">
                            @php
                                $statusLabels = [
                                    'pending' => ['class' => 'bg-amber-100 text-amber-700', 'label' => 'Pending Payment'],
                                    'confirmed' => ['class' => 'bg-green-100 text-green-700', 'label' => 'Confirmed'],
                                    'in_progress' => ['class' => 'bg-blue-100 text-blue-700', 'label' => 'In Progress'],
                                    'completed' => ['class' => 'bg-emerald-100 text-emerald-700', 'label' => 'Completed'],
                                    'cancelled' => ['class' => 'bg-red-100 text-red-700', 'label' => 'Cancelled'],
                                ];
                                $badge = $statusLabels[$booking->status] ?? ['class' => 'bg-slate-100 text-slate-700', 'label' => 'Unknown'];
                            @endphp
                            <span class="px-3 py-1 text-xs font-bold rounded-full {{ $badge['class'] }}">{{ $badge['label'] }}</span>
                            <p class="text-lg font-bold text-slate-800 mt-2">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</p>
                            <p class="text-xs text-slate-500">{{ $booking->total_passengers }} penumpang</p>
                        </div>
                    </div>

                    <div class="mt-4 pt-4 border-t border-slate-100">
                        <a href="{{ route('customer.booking.show', $booking) }}" class="text-blue-600 font-bold text-sm hover:underline">
                            Lihat Detail →
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <div class="bg-white rounded-2xl shadow-lg p-12 text-center">
                <div class="w-20 h-20 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-slate-800 mb-2">Belum Ada Booking</h3>
                <p class="text-slate-500 mb-6">Yuk pesan penerbangan pertamamu!</p>
                <a href="{{ route('customer.flights.results') }}" class="inline-block px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg transition-colors">
                    Cari Penerbangan
                </a>
            </div>
        @endif
    </div>
</x-layouts.customer>
@endsection