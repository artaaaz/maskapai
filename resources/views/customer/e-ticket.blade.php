<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>E-Ticket - {{ $booking->booking_code }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>* { font-family: 'Plus Jakarta Sans', sans-serif; }@media print { .no-print { display: none !important; } }</style>
</head>
<body class="bg-slate-100">
    <div class="max-w-2xl mx-auto p-4 md:p-8">
        <div class="no-print flex justify-between items-center mb-6">
            <a href="{{ route('customer.booking.show', $booking) }}" class="text-blue-600 hover:underline">&larr; Kembali</a>
            <button onclick="window.print()" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl">Cetak / Simpan PDF</button>
        </div>

        {{-- E-Ticket Card --}}
        <div class="bg-white rounded-3xl shadow-xl overflow-hidden">
            {{-- Header --}}
            <div class="bg-gradient-to-r from-blue-600 to-blue-800 p-6 text-white">
                <div class="flex justify-between items-start">
                    <div>
                        <h1 class="text-2xl font-black">drg<span class="text-yellow-400">.</span>Maskapai</h1>
                        <p class="text-blue-200 text-sm mt-1">E-Ticket / Boarding Pass</p>
                    </div>
                    <div class="text-right">
                        <p class="text-2xl font-black">{{ $booking->flight->airline->code ?? 'DG' }}-{{ $booking->booking_code }}</p>
                        <p class="text-blue-200 text-xs">Booking Code</p>
                    </div>
                </div>
            </div>

            {{-- Status Bar --}}
            <div class="px-6 py-3 border-b border-slate-100 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="px-3 py-1 rounded-full text-xs font-bold 
                        @if($booking->status == 'confirmed') bg-green-100 text-green-700
                        @elseif($booking->status == 'pending') bg-amber-100 text-amber-700
                        @else bg-red-100 text-red-700 @endif">
                        {{ strtoupper($booking->status) }}
                    </span>
                    <span class="text-xs text-slate-400">{{ $booking->trip_type == 'round_trip' ? 'Round Trip' : 'One Way' }}</span>
                </div>
                <span class="text-xs text-slate-400">Issued: {{ $booking->created_at->format('d/m/Y H:i') }}</span>
            </div>

            {{-- Flight Info --}}
            <div class="p-6">
                <div class="flex items-center justify-between mb-6">
                    <div class="text-center">
                        <p class="text-3xl font-black text-slate-800">{{ $booking->flight->departureAirport->iata_code ?? 'CGK' }}</p>
                        <p class="text-xs text-slate-500">{{ $booking->flight->departureAirport->city ?? 'Jakarta' }}</p>
                        <p class="text-sm font-bold text-slate-700 mt-1">{{ $booking->flight->departure_time->format('H:i') }}</p>
                        <p class="text-xs text-slate-400">{{ $booking->flight->departure_time->format('d M Y') }}</p>
                    </div>
                    <div class="flex-1 mx-8">
                        <div class="border-t-2 border-dashed border-slate-300 relative">
                            <div class="absolute -top-2 left-0 w-4 h-4 bg-slate-100 rounded-full"></div>
                            <div class="absolute -top-2 right-0 w-4 h-4 bg-slate-100 rounded-full"></div>
                        </div>
                        <p class="text-center text-xs text-slate-500 mt-2">
                            {{ $booking->flight->duration_formatted }} 
                            <span class="mx-2">&bull;</span>
                            {{ $booking->flight->flight_number }}
                        </p>
                    </div>
                    <div class="text-center">
                        <p class="text-3xl font-black text-slate-800">{{ $booking->flight->arrivalAirport->iata_code ?? 'DPS' }}</p>
                        <p class="text-xs text-slate-500">{{ $booking->flight->arrivalAirport->city ?? 'Bali' }}</p>
                        <p class="text-sm font-bold text-slate-700 mt-1">{{ $booking->flight->arrival_time->format('H:i') }}</p>
                        <p class="text-xs text-slate-400">{{ $booking->flight->arrival_time->format('d M Y') }}</p>
                    </div>
                </div>

                {{-- Airline Details --}}
                <div class="bg-slate-50 rounded-xl p-4 mb-6">
                    <div class="flex items-center gap-4">
                        @if($booking->flight->airline->logo)
                            <img src="{{ $booking->flight->airline->logo }}" class="w-12 h-12 object-contain" alt="logo">
                        @else
                            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center text-blue-600 font-bold">{{ substr($booking->flight->airline->name ?? 'DG', 0, 2) }}</div>
                        @endif
                        <div>
                            <p class="font-bold text-slate-800">{{ $booking->flight->airline->name ?? 'drgMaskapai' }}</p>
                            <p class="text-xs text-slate-500">{{ $booking->flight->airline->code ?? '' }} • {{ $booking->flight->airplane->model ?? '' }}</p>
                        </div>
                        <div class="ml-auto text-right">
                            <p class="text-sm font-semibold text-slate-700">{{ $booking->travel_class ?? 'Economy' }}</p>
                            <p class="text-xs text-slate-400">Kelas</p>
                        </div>
                    </div>
                </div>

                {{-- Passengers --}}
                <h3 class="font-bold text-slate-800 mb-3">Penumpang ({{ $booking->passengers->count() }})</h3>
                @foreach($booking->passengers as $passenger)
                    <div class="flex items-center justify-between py-3 border-b border-slate-100 last:border-0">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 font-bold text-sm">
                                {{ substr($passenger->full_name, 0, 1) }}
                            </div>
                            <div>
                                <p class="font-semibold text-slate-800">{{ $passenger->full_name_with_title }}</p>
                                <p class="text-xs text-slate-400">Paspor: {{ $passenger->passport_number }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="text-sm font-bold text-slate-700">{{ $passenger->seat_number ?? '-' }}</span>
                            <p class="text-xs text-slate-400">Kursi</p>
                        </div>
                    </div>
                @endforeach

                {{-- Payment Info --}}
                @if($booking->payment)
                <div class="mt-6 bg-blue-50 rounded-xl p-4">
                    <h4 class="font-bold text-slate-800 mb-2">Informasi Pembayaran</h4>
                    <table class="w-full text-sm">
                        <tr><td class="py-1 text-slate-500">Total Harga</td><td class="py-1 font-bold text-right">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</td></tr>
                        @if($booking->discount_amount > 0)
                        <tr><td class="py-1 text-slate-500">Diskon</td><td class="py-1 font-bold text-right text-green-600">-Rp {{ number_format($booking->discount_amount, 0, ',', '.') }}</td></tr>
                        @endif
                        <tr><td class="py-1 text-slate-500">Biaya Layanan</td><td class="py-1 font-bold text-right">Rp {{ number_format($booking->convenience_fee, 0, ',', '.') }}</td></tr>
                        <tr><td class="py-1 text-slate-500">Pajak</td><td class="py-1 font-bold text-right">Rp {{ number_format($booking->tax_amount, 0, ',', '.') }}</td></tr>
                        <tr class="border-t border-blue-200"><td class="py-2 text-slate-700 font-bold">Total Dibayar</td><td class="py-2 font-black text-right text-blue-700">Rp {{ number_format($booking->final_price, 0, ',', '.') }}</td></tr>
                        <tr><td class="py-1 text-slate-500">Metode</td><td class="py-1 text-right font-medium">{{ $booking->payment->payment_method ?? '-' }}</td></tr>
                        <tr><td class="py-1 text-slate-500">Status</td><td class="py-1 text-right">
                            <span class="px-2 py-0.5 rounded text-xs font-bold 
                                @if($booking->payment->payment_status == 'paid') bg-green-100 text-green-700
                                @elseif($booking->payment->payment_status == 'pending') bg-amber-100 text-amber-700
                                @else bg-red-100 text-red-700 @endif">
                                {{ ucfirst($booking->payment->payment_status) }}
                            </span>
                        </td></tr>
                    </table>
                </div>
                @endif

                {{-- Barcode / QR (simulasi) --}}
                <div class="mt-6 text-center border-t border-slate-100 pt-6">
                    <div class="inline-block bg-white border-2 border-slate-200 rounded-xl p-4">
                        <div class="flex gap-1 mb-2">
                            @for($i = 0; $i < 20; $i++)
                                <div class="w-1.5 {{ rand(0,1) ? 'bg-slate-800' : 'bg-transparent' }}" style="height: {{ rand(20, 60) }}px;"></div>
                            @endfor
                        </div>
                        <p class="text-xs text-slate-500 font-mono">{{ $booking->booking_code }}</p>
                    </div>
                    <p class="text-xs text-slate-400 mt-2">Tunjukkan e-ticket ini saat check-in di bandara</p>
                </div>

                {{-- Footer --}}
                <div class="mt-6 pt-4 border-t border-slate-100 text-center text-xs text-slate-400">
                    <p>drgMaskapai &copy; {{ date('Y') }}. E-Ticket ini adalah bukti booking yang sah.</p>
                    <p>Harap simpan e-ticket ini untuk keperluan check-in dan boarding.</p>
                </div>
            </div>
        </div>

        {{-- Info Penting --}}
        <div class="mt-6 bg-amber-50 border border-amber-200 rounded-2xl p-4 text-sm text-amber-800">
            <p class="font-bold mb-1">&#9432; Informasi Penting</p>
            <ul class="list-disc list-inside text-xs space-y-0.5">
                <li>Harap datang ke bandara minimal 2 jam sebelum keberangkatan</li>
                <li>Bawa identitas diri (KTP/Paspor) yang masih berlaku</li>
                <li>Check-in online tersedia H-1 hingga 30 menit sebelum keberangkatan</li>
                <li>Bagasi kabin maksimal 7kg & bagasi terdaftar sesuai ketentuan maskapai</li>
            </ul>
        </div>
    </div>
</body>
</html>