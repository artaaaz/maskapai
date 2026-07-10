@extends('layouts.customer')

@section('content')
<div class="bg-slate-50 min-h-screen py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- Success Message --}}
        @if(session('success'))
        <div class="bg-green-50 border-2 border-green-400 text-green-700 px-6 py-4 rounded-xl mb-6">
            <div class="flex items-center gap-3">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <p class="font-bold">{{ session('success') }}</p>
            </div>
        </div>
        @endif

        {{-- Header --}}
        <div class="text-center mb-8">
            <h1 class="text-3xl font-black text-slate-800 mb-2">Instruksi Pembayaran</h1>
            <p class="text-slate-500">Selesaikan pembayaran Anda sebelum waktu habis</p>
        </div>

        {{-- Countdown Timer --}}
        <div class="bg-gradient-to-r from-red-500 to-pink-600 rounded-2xl shadow-lg p-6 mb-6 text-white">
            <div class="flex items-center justify-between flex-wrap gap-4">
                <div class="flex items-center gap-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <p class="font-bold text-lg">Selesaikan dalam</p>
                        <p class="text-white/80 text-sm">Sebelum pembayaran kadaluarsa</p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <div class="bg-white/20 rounded-lg px-4 py-2 text-center">
                        <p class="text-2xl font-black" id="hours">23</p>
                        <p class="text-xs">Jam</p>
                    </div>
                    <div class="bg-white/20 rounded-lg px-4 py-2 text-center">
                        <p class="text-2xl font-black" id="minutes">59</p>
                        <p class="text-xs">Menit</p>
                    </div>
                    <div class="bg-white/20 rounded-lg px-4 py-2 text-center">
                        <p class="text-2xl font-black" id="seconds">59</p>
                        <p class="text-xs">Detik</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Payment Details --}}
        <div class="bg-white rounded-2xl shadow-lg p-8 mb-6">
            <div class="flex items-center gap-4 mb-6 pb-6 border-b border-slate-200">
                @if($booking->payment->payment_method === 'bca_va')
                    <div class="w-16 h-16 bg-blue-600 rounded-xl flex items-center justify-center text-white font-bold">BCA</div>
                @elseif($booking->payment->payment_method === 'mandiri_va')
                    <div class="w-16 h-16 bg-blue-400 rounded-xl flex items-center justify-center text-white font-bold">MDR</div>
                @elseif($booking->payment->payment_method === 'bni_va')
                    <div class="w-16 h-16 bg-orange-500 rounded-xl flex items-center justify-center text-white font-bold">BNI</div>
                @elseif($booking->payment->payment_method === 'bri_va')
                    <div class="w-16 h-16 bg-blue-500 rounded-xl flex items-center justify-center text-white font-bold">BRI</div>
                @else
                    <div class="w-16 h-16 bg-green-500 rounded-xl flex items-center justify-center text-white font-bold">PAY</div>
                @endif
                <div>
                    <h2 class="text-2xl font-bold text-slate-800">{{ $booking->payment->payment_gateway }}</h2>
                    <p class="text-slate-500">Kode Booking: <span class="font-bold">{{ $booking->booking_code }}</span></p>
                </div>
            </div>

            {{-- Amount to Pay --}}
            <div class="bg-blue-50 border-2 border-blue-200 rounded-xl p-6 mb-6">
                <p class="text-sm text-blue-700 mb-2">Total Pembayaran</p>
                <p class="text-4xl font-black text-blue-600">IDR {{ number_format($booking->payment->amount, 0, ',', '.') }}</p>
            </div>

            {{-- Virtual Account Number --}}
            @if($booking->payment->virtual_account_number)
            <div class="mb-6">
                <h3 class="text-lg font-bold text-slate-800 mb-3">Nomor Virtual Account</h3>
                <div class="bg-slate-50 border-2 border-slate-200 rounded-xl p-6">
                    <div class="flex items-center justify-between">
                        <p class="text-3xl font-black text-slate-800 tracking-wider">{{ $booking->payment->virtual_account_number }}</p>
                        <button onclick="copyToClipboard('{{ $booking->payment->virtual_account_number }}')" 
                                class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg transition-colors">
                            📋 Salin
                        </button>
                    </div>
                </div>
            </div>
            @endif

            {{-- Payment Instructions --}}
            <div class="mb-6">
                <h3 class="text-lg font-bold text-slate-800 mb-3">Cara Pembayaran</h3>
                <div class="space-y-3">
                    <div class="flex gap-3">
                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 font-bold flex-shrink-0">1</div>
                        <p class="text-slate-700">Buka aplikasi mobile banking atau kunjungi ATM terdekat</p>
                    </div>
                    <div class="flex gap-3">
                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 font-bold flex-shrink-0">2</div>
                        <p class="text-slate-700">Pilih menu <strong>Transfer</strong> → <strong>Virtual Account</strong></p>
                    </div>
                    <div class="flex gap-3">
                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 font-bold flex-shrink-0">3</div>
                        <p class="text-slate-700">Masukkan nomor virtual account: <strong>{{ $booking->payment->virtual_account_number ?? 'N/A' }}</strong></p>
                    </div>
                    <div class="flex gap-3">
                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 font-bold flex-shrink-0">4</div>
                        <p class="text-slate-700">Pastikan nominal: <strong>IDR {{ number_format($booking->payment->amount, 0, ',', '.') }}</strong></p>
                    </div>
                    <div class="flex gap-3">
                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 font-bold flex-shrink-0">5</div>
                        <p class="text-slate-700">Konfirmasi pembayaran dan simpan bukti transfer</p>
                    </div>
                </div>
            </div>

            {{-- Test Button (Development Only) --}}
            <div class="bg-yellow-50 border-2 border-yellow-300 rounded-xl p-4 mb-6">
                <p class="text-sm text-yellow-800 font-bold mb-2">⚠️ Mode Development</p>
                <p class="text-xs text-yellow-700 mb-3">
                    Klik tombol di bawah untuk simulasi pembayaran berhasil (hanya untuk testing).
                </p>
                <form action="{{ route('customer.payment.test-success', $booking) }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full py-3 bg-yellow-600 hover:bg-yellow-700 text-white font-bold rounded-xl transition-colors">
                        ✓ Simulasi Pembayaran Berhasil
                    </button>
                </form>
            </div>

            {{-- Action Buttons --}}
            <div class="flex gap-3">
                <a href="{{ route('customer.bookings') }}" 
                   class="flex-1 py-3 bg-slate-200 hover:bg-slate-300 text-slate-800 font-bold rounded-xl text-center transition-colors">
                    Lihat Pesanan Saya
                </a>
                <button onclick="window.location.reload()" 
                        class="flex-1 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl transition-colors">
                    Cek Status Pembayaran
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    // Countdown Timer
    const expiredAt = new Date('{{ $booking->payment->expired_at }}');
    
    function updateCountdown() {
        const now = new Date();
        const diff = expiredAt - now;
        
        if (diff <= 0) {
            document.getElementById('hours').textContent = '00';
            document.getElementById('minutes').textContent = '00';
            document.getElementById('seconds').textContent = '00';
            return;
        }
        
        const hours = Math.floor(diff / (1000 * 60 * 60));
        const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((diff % (1000 * 60)) / 1000);
        
        document.getElementById('hours').textContent = hours.toString().padStart(2, '0');
        document.getElementById('minutes').textContent = minutes.toString().padStart(2, '0');
        document.getElementById('seconds').textContent = seconds.toString().padStart(2, '0');
    }
    
    setInterval(updateCountdown, 1000);
    updateCountdown();

    // Copy to clipboard
    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(() => {
            alert('Nomor VA berhasil disalin!');
        });
    }
</script>
@endsection