@extends('layouts.customer')

@section('content')
<div class="bg-slate-50 min-h-screen py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center py-12">
            <div class="animate-spin rounded-full h-16 w-16 border-t-2 border-b-2 border-blue-600 mx-auto mb-4"></div>
            <h2 class="text-2xl font-bold text-slate-800 mb-2">Memproses Pembayaran...</h2>
            <p class="text-slate-600">Mohon tunggu, popup pembayaran akan segera muncul</p>
        </div>
    </div>
</div>

@if(config('services.midtrans.client_key'))
<script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('services.midtrans.client_key') }}"></script>
@endif

{{-- MIDTRANS SNAP JS --}}
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
    });
});

// Promo Modal
function togglePromoModal() {
    const modal = document.getElementById('promoModal');
    modal.classList.toggle('hidden');
    modal.classList.toggle('flex');
}

function selectPromo(code) {
    document.getElementById('promoInput').value = code;
    togglePromoModal();
    applyPromo();
}

function applyPromo() {
    const code = document.getElementById('promoInput').value;
    const messageDiv = document.getElementById('promoMessage');
    
    if (!code) {
        messageDiv.innerHTML = '<p class="text-red-600">Masukkan kode promo terlebih dahulu</p>';
        return;
    }
    
    messageDiv.innerHTML = '<p class="text-green-600">✓ Kode promo berhasil diterapkan!</p>';
}

// PAYMENT FORM SUBMIT WITH MIDTRANS
document.getElementById('paymentForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const submitBtn = document.getElementById('submitBtn');
    const originalBtnText = submitBtn.innerHTML;
    
    // Show loading
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
            // LANGSUNG BUKA POPUP MIDTRANS
            snap.pay(data.snap_token, {
                onSuccess: function(result) {
                    // Payment success
                    alert('✅ Pembayaran berhasil!');
                    window.location.href = '{{ route("customer.booking.show", $booking) }}?payment=success';
                },
                onPending: function(result) {
                    // Payment pending (untuk VA)
                    alert('⏳ Pembayaran sedang diproses. Silakan selesaikan pembayaran Anda.');
                    window.location.href = '{{ route("customer.booking.show", $booking) }}?payment=pending';
                },
                onError: function(result) {
                    // Payment error
                    alert('❌ Terjadi kesalahan dalam pembayaran. Silakan coba lagi.');
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalBtnText;
                },
                onClose: function() {
                    // User menutup popup tanpa bayar
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
</script>
@endsection