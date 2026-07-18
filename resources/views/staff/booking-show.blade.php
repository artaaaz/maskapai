<x-staff-layout title="Detail Booking - drgMaskapai" header="Detail Booking">
    <x-slot name="headerRight">
        <a href="{{ route('staff.bookings') }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-xl text-sm transition">&larr; Kembali</a>
    </x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Left: Info Booking & Customer --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                <h3 class="text-lg font-bold text-slate-800 mb-4">Informasi Booking</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs text-slate-400 font-medium uppercase">Kode Booking</p>
                        <p class="text-sm font-bold text-slate-800">{{ $booking->booking_code }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400 font-medium uppercase">Status</p>
                        <span class="px-3 py-1 text-xs font-bold rounded-full {{ $booking->status_badge['class'] }}">{{ $booking->status_badge['label'] }}</span>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400 font-medium uppercase">Tanggal Booking</p>
                        <p class="text-sm font-bold text-slate-800">{{ $booking->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400 font-medium uppercase">Total Penumpang</p>
                        <p class="text-sm font-bold text-slate-800">{{ $booking->total_passengers }} orang</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400 font-medium uppercase">Tipe Perjalanan</p>
                        <p class="text-sm font-bold text-slate-800">{{ $booking->trip_type === 'round_trip' ? 'Pulang Pergi' : 'Sekali Jalan' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400 font-medium uppercase">Kelas</p>
                        <p class="text-sm font-bold text-slate-800 capitalize">{{ str_replace('_', ' ', $booking->travel_class ?? 'economy') }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                <h3 class="text-lg font-bold text-slate-800 mb-4">Data Pemesan</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs text-slate-400 font-medium uppercase">Nama</p>
                        <p class="text-sm font-bold text-slate-800">{{ $booking->user->name ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400 font-medium uppercase">Email</p>
                        <p class="text-sm font-bold text-slate-800">{{ $booking->user->email ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                <h3 class="text-lg font-bold text-slate-800 mb-4">Data Penerbangan</h3>
                <div class="space-y-4">
                    <div class="flex items-center gap-4 p-4 bg-blue-50 rounded-xl">
                        @if($booking->flight->airline->logo)
                            <img src="{{ asset('storage/' . $booking->flight->airline->logo) }}" class="w-10 h-10 rounded-lg object-cover">
                        @else
                            <div class="w-10 h-10 bg-emerald-600 rounded-lg flex items-center justify-center text-white font-bold text-sm">{{ substr($booking->flight->airline->name ?? 'DRG', 0, 2) }}</div>
                        @endif
                        <div>
                            <p class="font-bold text-slate-800">{{ $booking->flight->airline->name }} - {{ $booking->flight->flight_number }}</p>
                            <p class="text-sm text-slate-500">{{ $booking->flight->departureAirport->city }} ({{ $booking->flight->departureAirport->iata_code }}) → {{ $booking->flight->arrivalAirport->city }} ({{ $booking->flight->arrivalAirport->iata_code }})</p>
                            <p class="text-xs text-slate-400">{{ $booking->flight->departure_time->format('d M Y H:i') }} - {{ $booking->flight->arrival_time->format('H:i') }}</p>
                        </div>
                    </div>
                    @if($booking->returnFlight)
                    <div class="flex items-center gap-4 p-4 bg-emerald-50 rounded-xl">
                        <div class="w-10 h-10 bg-emerald-600 rounded-lg flex items-center justify-center text-white font-bold text-sm">PP</div>
                        <div>
                            <p class="font-bold text-slate-800">{{ $booking->returnFlight->airline->name }} - {{ $booking->returnFlight->flight_number }}</p>
                            <p class="text-sm text-slate-500">{{ $booking->returnFlight->departureAirport->city }} ({{ $booking->returnFlight->departureAirport->iata_code }}) → {{ $booking->returnFlight->arrivalAirport->city }} ({{ $booking->returnFlight->arrivalAirport->iata_code }})</p>
                            <p class="text-xs text-slate-400">{{ $booking->returnFlight->departure_time->format('d M Y H:i') }} - {{ $booking->returnFlight->arrival_time->format('H:i') }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                <h3 class="text-lg font-bold text-slate-800 mb-4">Data Penumpang</h3>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-bold text-slate-500 uppercase">Nama</th>
                                <th class="px-4 py-2 text-left text-xs font-bold text-slate-500 uppercase">Kursi</th>
                                <th class="px-4 py-2 text-left text-xs font-bold text-slate-500 uppercase">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($booking->passengers as $p)
                            <tr>
                                <td class="px-4 py-3 text-sm font-medium text-slate-800">{{ $p->full_name_with_title }}</td>
                                <td class="px-4 py-3 text-sm text-slate-600">{{ $p->seat_number ?? '-' }}</td>
                                <td class="px-4 py-3">
                                    <span class="px-2 py-1 rounded-lg text-xs font-medium {{ $p->check_in_status['class'] }}">{{ $p->check_in_status['label'] }}</span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Right: Payment & Actions --}}
        <div class="space-y-6">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                <h3 class="text-lg font-bold text-slate-800 mb-4">Pembayaran</h3>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-sm text-slate-500">Total Harga</span>
                        <span class="text-sm font-bold text-slate-800">Rp{{ number_format($booking->total_price, 0, ',', '.') }}</span>
                    </div>
                    @if($booking->discount_amount > 0)
                    <div class="flex justify-between">
                        <span class="text-sm text-slate-500">Diskon</span>
                        <span class="text-sm font-bold text-green-600">-Rp{{ number_format($booking->discount_amount, 0, ',', '.') }}</span>
                    </div>
                    @endif
                    <hr class="border-slate-200">
                    <div class="flex justify-between">
                        <span class="text-sm font-bold text-slate-800">Total Dibayar</span>
                        <span class="text-lg font-black text-emerald-600">Rp{{ number_format(($booking->total_price - $booking->discount_amount), 0, ',', '.') }}</span>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400 font-medium uppercase mb-1">Status Pembayaran</p>
                        @if($booking->payment)
                            @php
                                $payStatusColors = ['paid' => 'bg-green-100 text-green-700', 'pending' => 'bg-amber-100 text-amber-700', 'failed' => 'bg-red-100 text-red-700'];
                                $payColor = $payStatusColors[$booking->payment->payment_status] ?? 'bg-slate-100 text-slate-700';
                            @endphp
                            <span class="px-3 py-1 text-xs font-bold rounded-full {{ $payColor }}">{{ ucfirst($booking->payment->payment_status) }}</span>
                            @if($booking->payment->paid_at)
                                <p class="text-xs text-slate-400 mt-1">{{ $booking->payment->paid_at->format('d/m/Y H:i') }}</p>
                            @endif
                        @else
                            <span class="px-3 py-1 text-xs font-bold rounded-full bg-slate-100 text-slate-600">Belum Dibayar</span>
                        @endif
                    </div>
                    <div>
                        <p class="text-xs text-slate-400 font-medium uppercase mb-1">Metode</p>
                        <p class="text-sm font-bold text-slate-800">{{ $booking->payment->payment_method ?? '-' }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                <h3 class="text-lg font-bold text-slate-800 mb-4">Aksi</h3>
                <div class="space-y-3">
                    @if($booking->status === 'pending')
                        <form action="{{ route('staff.booking.updateStatus', $booking) }}" method="POST">
                            @csrf @method('PUT')
                            <input type="hidden" name="status" value="confirmed">
                            <button type="submit" class="w-full py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl transition text-sm" onclick="return confirm('Konfirmasi booking ini?')">✅ Konfirmasi Booking</button>
                        </form>
                        <form action="{{ route('staff.booking.updateStatus', $booking) }}" method="POST">
                            @csrf @method('PUT')
                            <input type="hidden" name="status" value="cancelled">
                            <button type="submit" class="w-full py-2.5 bg-red-500 hover:bg-red-600 text-white font-bold rounded-xl transition text-sm" onclick="return confirm('Batalkan booking ini?')">❌ Batalkan Booking</button>
                        </form>
                    @endif
                    @if($booking->payment && $booking->payment->payment_status === 'pending')
                        <form action="{{ route('staff.booking.verifyPayment', $booking) }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl transition text-sm" onclick="return confirm('Verifikasi pembayaran booking ini?')">💳 Verify Payment</button>
                        </form>
                    @endif
                    @if($booking->status === 'confirmed' && $booking->payment?->payment_status === 'paid')
                        <a href="{{ route('staff.bookings') }}" class="block w-full py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl transition text-sm text-center">Kembali ke Daftar</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-staff-layout>