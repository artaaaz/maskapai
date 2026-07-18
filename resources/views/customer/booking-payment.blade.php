@extends('layouts.customer')

@section('content')
<div class="bg-slate-50 min-h-screen py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- Booking Info --}}
        <div class="bg-white rounded-2xl shadow-lg p-8">
            <h2 class="text-2xl font-bold text-slate-800 mb-6 text-center">Pembayaran Booking</h2>
            
            <div class="space-y-4 mb-8">
                <div class="flex justify-between">
                    <span class="text-slate-600">Kode Booking</span>
                    <span class="font-bold">{{ $booking->booking_code }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-600">Rute</span>
                    <span class="font-semibold">{{ $booking->flight->departureAirport->city }} → {{ $booking->flight->arrivalAirport->city }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-600">Penerbangan</span>
                    <span class="font-semibold">{{ $booking->flight->flight_number }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-600">Tanggal</span>
                    <span class="font-semibold">{{ $booking->flight->departure_time->format('D, d M Y') }}</span>
                </div>
                <div class="border-t border-slate-200 pt-4 flex justify-between items-center">
                    <span class="font-bold text-slate-800 text-lg">Total Pembayaran</span>
                    <span class="text-2xl font-black text-blue-600">IDR {{ number_format($booking->total_price, 0, ',', '.') }}</span>
                </div>
            </div>

            {{-- Button untuk Buka Payment --}}
            <button onclick="openPayment()" 
                    class="w-full py-4 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl transition-all shadow-lg hover:shadow-xl">
                💳 Lanjutkan Pembayaran
            </button>

            <p class="text-xs text-slate-500 text-center mt-4">
                Klik tombol di atas untuk melanjutkan pembayaran
            </p>
        </div>
    </div>
</div>

{{-- MIDTRANS SNAP JS --}}
<script type="text/javascript" 
        src="https://app.sandbox.midtrans.com/snap/snap.js" 
        data-client-key="{{ config('services.midtrans.client_key') }}">
</script>

<script>
function openPayment() {
    console.log('🔵 Opening payment...');
    console.log('🔵 Snap Token:', '{{ $snapToken }}');
    console.log('🔵 Client Key:', '{{ config("services.midtrans.client_key") }}');
    
    // Cek apakah snap tersedia
    if (typeof snap === 'undefined') {
        console.error('❌ Midtrans Snap tidak ter-load!');
        alert('❌ Error: Midtrans Snap tidak ter-load.\n\nSilakan refresh halaman atau cek koneksi internet.');
        return;
    }
    
    // Buka popup Midtrans
    snap.pay('{{ $snapToken }}', {
        onSuccess: function(result) {
            console.log('✅ Payment success:', result);
            alert('✅ Pembayaran berhasil!');
            window.location.href = '{{ route("customer.booking.show", $booking) }}?payment=success';
        },
        onPending: function(result) {
            console.log('⏳ Payment pending:', result);
            alert('⏳ Pembayaran sedang diproses.');
            window.location.href = '{{ route("customer.booking.show", $booking) }}?payment=pending';
        },
        onError: function(result) {
            console.error('❌ Payment error:', result);
            alert('❌ Terjadi kesalahan dalam pembayaran.');
        },
        onClose: function() {
            console.log('⚠️ Popup ditutup');
        }
    });
}

// Auto open setelah 1 detik (optional)
setTimeout(openPayment, 1000);
</script>
@endsection