<x-mail::message>
# Verifikasi Alamat Email

Halo **{{ $user->name ?? 'Pengguna' }}**,

Terima kasih telah mendaftar di **drgMaskapai**! Silakan verifikasi alamat email Anda dengan mengklik tombol di bawah ini:

<x-mail::button :url="$verificationUrl ?? '#'">
Verifikasi Email
</x-mail::button>

Tautan verifikasi ini akan kedaluwarsa dalam waktu 60 menit.

Jika Anda tidak merasa mendaftar akun drgMaskapai, abaikan email ini.

Salam hangat,<br>
**Tim drgMaskapai**

<x-slot:subcopy>
Jika Anda mengalami kesulitan menekan tombol "Verifikasi Email", salin dan tempel URL berikut ke browser Anda:  
{{ $verificationUrl ?? '#' }}
</x-slot:subcopy>
</x-mail::message>