<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Verifikasi Email - drgMaskapai</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>* { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
</head>
<body class="min-h-screen bg-slate-100">
    <div class="min-h-screen flex flex-col lg:flex-row">
        {{-- Hero / Branding --}}
        <div class="lg:w-1/2 relative min-h-[280px] lg:min-h-screen">
            <img src="https://images.unsplash.com/photo-1436491865332-7a61a109cc05?w=1200&h=800&fit=crop"
                 class="absolute inset-0 w-full h-full object-cover" alt="Travel">
            <div class="absolute inset-0 bg-gradient-to-br from-blue-900/80 to-blue-700/70"></div>
            <div class="relative z-10 p-10 lg:p-16 flex flex-col justify-center h-full text-white">
                <div class="flex items-center gap-2 mb-8">
                    <span class="text-3xl font-black">drg<span class="text-yellow-400">.</span>Maskapai</span>
                </div>
                <h1 class="text-4xl lg:text-5xl font-black mb-4 leading-tight">Verifikasi Email Anda</h1>
                <p class="text-blue-100 text-lg">Konfirmasi alamat email untuk mengaktifkan akun dan mulai memesan tiket pesawat.</p>
            </div>
        </div>

        {{-- Form --}}
        <div class="lg:w-1/2 flex items-center justify-center p-6 lg:p-12">
            <div class="w-full max-w-md">
                <div class="bg-white rounded-3xl shadow-xl p-8">
                    {{-- Icon Email --}}
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                    </div>

                    <h2 class="text-2xl font-black text-slate-800 mb-2 text-center">Cek Email Anda</h2>
                    <p class="text-slate-500 mb-6 text-sm text-center">Kami telah mengirimkan tautan verifikasi ke:</p>

                    {{-- User Email Badge --}}
                    <div class="mb-6 p-3 bg-blue-50 border border-blue-200 rounded-xl text-blue-700 text-sm font-semibold text-center">
                        <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        {{ auth()->user() ? auth()->user()->email : 'email Anda' }}
                    </div>

                    {{-- Success Alert --}}
                    @if (session('success'))
                        <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-xl text-green-700 text-sm flex items-start gap-2">
                            <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <span>{{ session('success') }}</span>
                        </div>
                    @endif

                    {{-- Warning Alert --}}
                    @if (session('warning'))
                        <div class="mb-4 p-4 bg-amber-50 border border-amber-200 rounded-xl text-amber-700 text-sm flex items-start gap-2">
                            <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path></svg>
                            <span>{{ session('warning') }}</span>
                        </div>
                    @endif

                    {{-- Status Verifikasi --}}
                    @if (auth()->user() && auth()->user()->hasVerifiedEmail())
                        <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-xl text-green-700 text-sm flex items-start gap-2">
                            <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <span>Email Anda sudah terverifikasi. <a href="{{ route('dashboard') }}" class="font-bold underline">Lanjutkan ke dashboard</a></span>
                        </div>
                    @else
                        {{-- Belum Verifikasi --}}
                        <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-xl text-red-700 text-sm flex items-start gap-2">
                            <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M12 3a9 9 0 100 18 9 9 0 000-18z"></path></svg>
                            <span>Email Anda belum diverifikasi. Silakan buka email yang Anda daftarkan dan klik tautan verifikasi.</span>
                        </div>
                    @endif

                    {{-- Description --}}
                    <p class="text-slate-600 text-sm leading-relaxed mb-6">
                        Sebelum melanjutkan, silakan klik tautan verifikasi yang kami kirimkan ke email Anda. 
                        Jika tidak menemukan email, periksa folder spam atau klik tombol di bawah untuk mengirim ulang.
                    </p>

                    {{-- Success Alert --}}
                    @if (session('status') == 'verification-link-sent')
                        <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-xl text-green-700 text-sm flex items-start gap-2">
                            <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <span>Tautan verifikasi baru telah dikirim ke email Anda.</span>
                        </div>
                    @endif

                    {{-- Actions --}}
                    <div class="space-y-3 mt-6">
                        <form method="POST" action="{{ route('verification.send') }}" id="resendForm">
                            @csrf
                            <button type="submit" id="resendBtn"
                                    class="w-full py-3.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl transition-colors shadow-lg">
                                Kirim Ulang Email Verifikasi
                            </button>
                        </form>

                        <div class="flex justify-center pt-2">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="text-xs font-semibold text-slate-400 hover:text-slate-600 transition-colors">
                                    ← Keluar dari Sesi
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <p class="text-center text-xs text-slate-400 mt-6">
                    &copy; 2026 drgMaskapai. Seluruh hak cipta dilindungi.
                </p>
            </div>
        </div>
    </div>

    <script>
        // Cooldown 60 detik untuk tombol kirim ulang
        (function() {
            const resendBtn = document.getElementById('resendBtn');
            const resendForm = document.getElementById('resendForm');
            if (!resendBtn || !resendForm) return;

            let cooldown = 0;

            // Cek localStorage untuk cooldown
            const lastSent = localStorage.getItem('lastVerificationSent');
            if (lastSent) {
                const elapsed = Math.floor((Date.now() - parseInt(lastSent)) / 1000);
                if (elapsed < 60) {
                    cooldown = 60 - elapsed;
                    startCooldown();
                }
            }

            resendForm.addEventListener('submit', function(e) {
                if (cooldown > 0) {
                    e.preventDefault();
                    return;
                }
                localStorage.setItem('lastVerificationSent', Date.now().toString());
                cooldown = 60;
                startCooldown();
            });

            function startCooldown() {
                resendBtn.disabled = true;
                updateButtonText();
                const interval = setInterval(function() {
                    cooldown--;
                    updateButtonText();
                    if (cooldown <= 0) {
                        clearInterval(interval);
                        resendBtn.disabled = false;
                        resendBtn.textContent = 'Kirim Ulang Email Verifikasi';
                        localStorage.removeItem('lastVerificationSent');
                    }
                }, 1000);
            }

            function updateButtonText() {
                resendBtn.textContent = 'Kirim Ulang (' + cooldown + ' detik)';
            }
        })();
    </script>
</body>
</html>