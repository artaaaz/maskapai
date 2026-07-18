<x-mail::message>
# Konfirmasi Pemesanan Tiket

Halo **{{ $booking->user->name ?? 'Pelanggan' }}**,

Terima kasih telah melakukan pemesanan tiket melalui **drgMaskapai**. Pemesanan Anda telah berhasil dikonfirmasi.

## Detail Penerbangan

- **Kode Booking:** {{ $booking->booking_code ?? '-' }}
- **Maskapai:** {{ $booking->flight->airline->name ?? '-' }}
- **Rute:** {{ $booking->flight->originAirport->city ?? '-' }} → {{ $booking->flight->destinationAirport->city ?? '-' }}
- **Tanggal:** {{ $booking->flight->departure_time ?? '-' }}
- **Jumlah Penumpang:** {{ $booking->passengers_count ?? $booking->passengers->count() ?? '-' }} orang

<x-mail::button :url="{{ route('customer.bookings') }}">
Lihat Detail Pemesanan
</x-mail::button>

Silakan lakukan pembayaran sesuai dengan instruksi yang telah dikirimkan terpisah.  
Jika Anda memiliki pertanyaan, jangan ragu untuk menghubungi customer service kami.

Terima kasih telah memilih drgMaskapai untuk perjalanan Anda.

Salam hangat,<br>
**Tim drgMaskapai**

<x-slot:subcopy>
Email ini dikirim secara otomatis. Harap tidak membalas email ini.
</x-slot:subcopy>
</x-mail::message>