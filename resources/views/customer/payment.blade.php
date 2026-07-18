@extends('layouts.customer')

@section('content')
<div class="bg-slate-50 min-h-screen py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Loading State (shown initially) --}}
        <div id="paymentLoading" class="text-center py-12">
            <div class="animate-spin rounded-full h-16 w-16 border-t-2 border-b-2 border-blue-600 mx-auto mb-4"></div>
            <h2 class="text-2xl font-bold text-slate-800 mb-2">Memproses Pembayaran...</h2>
            <p class="text-slate-600">Mohon tunggu, popup pembayaran akan segera muncul</p>
        </div>

        {{-- Payment Content (hidden initially) --}}
        <div id="paymentContent" style="display:none;">
            <div class="text-center mb-8">
                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </div>
                <h1 class="text-2xl font-bold text-slate-800">Selesaikan Pembayaran</h1>
                <p class="text-slate-500 mt-1">Pilih metode pembayaran dan selesaikan transaksi Anda</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">
                {{-- Left: Payment Method Selection --}}
                <div class="lg:col-span-3 space-y-5">

                    {{-- Payment Methods --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5">
                        <h3 class="font-bold text-slate-800 mb-4">Metode Pembayaran</h3>
                        <form id="paymentForm" class="space-y-3">
                            @csrf
                            <div class="space-y-2">
                                @php
                                    $methods = [
                                        'bca_va' => ['name' => 'BCA Virtual Account', 'icon' => 'M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A13.916 13.916 0 008 11a4 4 0 118 0c0 1.017-.07 2.019-.203 3m-2.118 6.844A21.88 21.88 0 0015.171 17m3.839 1.132c.645-2.266.99-4.659.99-7.132A8 8 0 008 4.07M3 15.364c.64-1.319 1-2.8 1-4.364 0-1.457.39-2.823 1.07-4'],
                                        'mandiri_va' => ['name' => 'Mandiri Virtual Account', 'icon' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4'],
                                        'bni_va' => ['name' => 'BNI Virtual Account', 'icon' => 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z'],
                                        'gopay' => ['name' => 'GoPay', 'icon' => 'M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z'],
                                        'dana' => ['name' => 'DANA', 'icon' => 'M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z'],
                                        'shopeepay' => ['name' => 'ShopeePay', 'icon' => 'M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z'],
                                    ];
                                @endphp
                                @foreach($methods as $key => $method)
                                <label class="payment-method flex items-center gap-4 p-4 border border-slate-200 rounded-xl cursor-pointer hover:border-blue-300 transition-all {{ $loop->first ? 'border-blue-500 bg-blue-50' : '' }}">
                                    <input type="radio" name="payment_method" value="{{ $key }}" {{ $loop->first ? 'checked' : '' }} class="w-4 h-4 text-blue-600 accent-blue-600 flex-shrink-0">
                                    <div class="w-10 h-10 bg-slate-100 rounded-xl flex items-center justify-center flex-shrink-0">
                                        <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $method['icon'] }}"/></svg>
                                    </div>
                                    <span class="font-medium text-slate-700 text-sm">{{ $method['name'] }}</span>
                                </label>
                                @endforeach
                            </div>

                            <button type="submit" id="submitBtn" class="w-full py-3.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl transition-all shadow-lg shadow-blue-600/20 text-sm flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                Bayar Sekarang
                            </button>
                        </form>
                    </div>
                </div>

                {{-- Right: Order Summary --}}
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5 sticky top-28">
                        <h3 class="font-bold text-slate-800 mb-4">Ringkasan Pesanan</h3>
                        
                        <div class="space-y-3 text-sm">
                            <div class="flex justify-between">
                                <span class="text-slate-500">Kode Booking</span>
                                <span class="font-bold text-slate-800">{{ $booking->booking_code }}</span>
                            </div>
                            <div class="border-t border-slate-100 pt-3">
                                <div class="flex justify-between">
                                    <span class="text-slate-500">Harga Tiket</span>
                                    <span class="font-semibold text-slate-800">Rp{{ number_format($booking->total_price, 0, ',', '.') }}</span>
                                </div>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-500">Biaya Layanan</span>
                                <span class="font-semibold text-slate-800">Rp{{ number_format($booking->convenience_fee, 0, ',', '.') }}</span>
                            </div>
                            <div class="border-t border-slate-100 pt-3">
                                <div class="flex justify-between">
                                    <span class="text-base font-bold text-slate-800">Total</span>
                                    <span class="text-xl font-black text-blue-600" id="finalPriceText">Rp{{ number_format($booking->total_price, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>

                        {{-- Timer --}}
                        <div class="mt-4 pt-4 border-t border-slate-100">
                            <div class="flex items-center justify-center gap-2 text-sm text-slate-500">
                                <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span>Sisa waktu: <span id="hours" class="font-bold text-slate-800">24</span>:<span id="minutes" class="font-bold text-slate-800">00</span>:<span id="seconds" class="font-bold text-slate-800">00</span></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@if(config('services.midtrans.client_key'))
<script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('services.midtrans.client_key') }}"></script>
@endif

<script>
// Countdown Timer
const expiredAt = new Date('{{ $booking->payment->expired_at ?? now()->addHours(24) }}');

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

// Payment Method Selection
document.querySelectorAll('.payment-method').forEach(label => {
    label.addEventListener('click', function() {
        document.querySelectorAll('.payment-method').forEach(l => {
            l.classList.remove('border-blue-500', 'bg-blue-50');
            l.classList.add('border-slate-200');
        });
        this.classList.remove('border-slate-200');
        this.classList.add('border-blue-500', 'bg-blue-50');
        this.querySelector('input[type="radio"]').checked = true;
    });
});

// PAYMENT FORM SUBMIT WITH MIDTRANS
document.getElementById('paymentForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    const submitBtn = document.getElementById('submitBtn');
    const originalBtnText = submitBtn.innerHTML;
    
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<span class="animate-pulse">⏳ Menghubungkan ke Midtrans...</span>';
    
    try {
        const response = await fetch('{{ route("customer.payment.process", $booking) }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        
        const data = await response.json();
        
        if (data.success && data.snap_token) {
            snap.pay(data.snap_token, {
                onSuccess: function(result) {
                    alert('✅ Pembayaran berhasil!');
                    window.location.href = '{{ route("customer.booking.show", $booking) }}?payment=success';
                },
                onPending: function(result) {
                    alert('⏳ Pembayaran sedang diproses. Silakan selesaikan pembayaran Anda.');
                    window.location.href = '{{ route("customer.booking.show", $booking) }}?payment=pending';
                },
                onError: function(result) {
                    alert('❌ Terjadi kesalahan dalam pembayaran. Silakan coba lagi.');
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalBtnText;
                },
                onClose: function() {
                    alert('⚠️ Anda menutup popup pembayaran. Silakan klik tombol bayar untuk melanjutkan.');
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalBtnText;
                }
            });
        } else {
            alert('❌ Error: ' + data.message);
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalBtnText;
        }
    } catch (error) {
        console.error('Error:', error);
        alert('❌ Terjadi kesalahan. Silakan coba lagi.');
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalBtnText;
    }
});

// Init: Show content after page loads
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('paymentLoading').style.display = 'none';
    document.getElementById('paymentContent').style.display = 'block';
});
</script>
@endsection