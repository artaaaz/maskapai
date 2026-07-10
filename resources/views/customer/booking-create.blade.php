@extends('layouts.customer')

@section('content')
<div class="bg-slate-100 min-h-screen py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- Error Messages --}}
        @if($errors->any())
        <div class="bg-red-50 border-2 border-red-400 text-red-700 px-6 py-4 rounded-xl mb-6">
            <div class="flex items-start gap-3">
                <svg class="w-6 h-6 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div class="flex-1">
                    <h3 class="font-bold mb-2">Ada kesalahan pada form:</h3>
                    <ul class="list-disc list-inside text-sm">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        @endif

        @if(session('error'))
        <div class="bg-red-50 border-2 border-red-400 text-red-700 px-6 py-4 rounded-xl mb-6">
            <div class="flex items-center gap-3">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <p class="font-bold">{{ session('error') }}</p>
            </div>
        </div>
        @endif

        {{-- Header Info --}}
        @auth
        <div class="bg-green-50 rounded-xl shadow-sm p-4 mb-6 flex items-center gap-3">
            <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div class="flex-1">
                <p class="text-sm font-semibold text-slate-800">Hi, {{ Auth::user()->name }}! Kamu sudah login dan bisa langsung booking.</p>
                <p class="text-xs text-green-600">Dapatkan Blibli Tiket Points dari transaksi ini!</p>
            </div>
        </div>
        @else
        <div class="bg-blue-50 rounded-xl shadow-sm p-4 mb-6 flex items-center gap-3">
            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div class="flex-1">
                <p class="text-sm font-semibold text-slate-800">Log in atau buat akun sekarang untuk dapetin Blibli Tiket Points dari transaksi ini!</p>
                <a href="{{ route('customer.login') }}" class="text-blue-600 text-sm font-bold hover:underline">Log in sekarang</a>
            </div>
        </div>
        @endauth

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            {{-- LEFT COLUMN - Forms --}}
            <div class="lg:col-span-2 space-y-6">
                
                {{-- FORM TAG DIMULAI DI SINI --}}
                <form action="{{ route('customer.booking.store') }}" method="POST" id="bookingForm">
                    @csrf
                    <input type="hidden" name="flight_id" value="{{ $flight->id }}">

                    {{-- Detail Pemesan --}}
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <h2 class="text-xl font-bold text-slate-800 mb-2">Detail Pemesan</h2>
                        <p class="text-sm text-slate-500 mb-6">Detail kontak ini akan digunakan untuk pengiriman e-tiket dan keperluan refund/reschedule.</p>

                        <div class="space-y-4">
                            {{-- Title --}}
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Title</label>
                                <div class="flex gap-4">
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="radio" name="booker_title" value="Mr" class="w-4 h-4 text-blue-600" checked>
                                        <span class="text-sm text-slate-700">Tuan</span>
                                    </label>
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="radio" name="booker_title" value="Mrs" class="w-4 h-4 text-blue-600">
                                        <span class="text-sm text-slate-700">Nyonya</span>
                                    </label>
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="radio" name="booker_title" value="Ms" class="w-4 h-4 text-blue-600">
                                        <span class="text-sm text-slate-700">Nona</span>
                                    </label>
                                </div>
                            </div>

                            {{-- Nama Lengkap --}}
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Nama Lengkap</label>
                                <input type="text" name="booker_name" value="{{ old('booker_name', Auth::user()->name) }}" 
                                       class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" 
                                       placeholder="Nama Lengkap" required>
                            </div>

                            {{-- No Telepon --}}
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Nomor Telepon</label>
                                <div class="flex gap-2">
                                    <div class="flex items-center px-4 py-3 border border-slate-300 rounded-lg bg-slate-50">
                                        <span class="text-sm text-slate-700">🇮🇩 +62</span>
                                    </div>
                                    <input type="tel" name="booker_phone" value="{{ old('booker_phone') }}" 
                                           class="flex-1 px-4 py-3 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" 
                                           placeholder="81234567890" required>
                                </div>
                            </div>

                            {{-- Email --}}
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Alamat Email</label>
                                <input type="email" name="booker_email" value="{{ old('booker_email', Auth::user()->email) }}" 
                                       class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" 
                                       placeholder="email@example.com" required>
                            </div>
                        </div>
                    </div>

                    {{-- Detail Penumpang --}}
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <h2 class="text-xl font-bold text-slate-800 mb-6">Detail Penumpang</h2>

                        {{-- Passenger 1 --}}
                        <div class="passenger-form mb-6">
                            <div class="flex items-center justify-between mb-4 pb-4 border-b border-slate-200">
                                <h3 class="font-bold text-slate-800">Penumpang 1 (Dewasa)</h3>
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <span class="text-sm text-slate-600">Sama dengan pemesan</span>
                                    <input type="checkbox" class="same-as-booker w-4 h-4 text-blue-600 rounded" checked>
                                </label>
                            </div>

                            <div class="space-y-4">
                                {{-- Title --}}
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-2">Title</label>
                                    <div class="flex gap-4">
                                        <label class="flex items-center gap-2 cursor-pointer">
                                            <input type="radio" name="passengers[0][title]" value="Mr" class="w-4 h-4 text-blue-600" checked>
                                            <span class="text-sm text-slate-700">Tuan</span>
                                        </label>
                                        <label class="flex items-center gap-2 cursor-pointer">
                                            <input type="radio" name="passengers[0][title]" value="Mrs" class="w-4 h-4 text-blue-600">
                                            <span class="text-sm text-slate-700">Nyonya</span>
                                        </label>
                                        <label class="flex items-center gap-2 cursor-pointer">
                                            <input type="radio" name="passengers[0][title]" value="Ms" class="w-4 h-4 text-blue-600">
                                            <span class="text-sm text-slate-700">Nona</span>
                                        </label>
                                    </div>
                                </div>

                                {{-- Nama --}}
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-2">Nama Lengkap</label>
                                    <input type="text" name="passengers[0][full_name]" class="passenger-name w-full px-4 py-3 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" 
                                           placeholder="Nama Lengkap" required>
                                    <p class="text-xs text-slate-500 mt-1">Isi sesuai KTP/Paspor/SIM (tanpa tanda baca dan gelar)</p>
                                </div>

                                {{-- Gender & Birth Date --}}
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-semibold text-slate-700 mb-2">Jenis Kelamin</label>
                                        <select name="passengers[0][gender]" class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                                            <option value="">Pilih</option>
                                            <option value="male">Laki-laki</option>
                                            <option value="female">Perempuan</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-slate-700 mb-2">Tanggal Lahir</label>
                                        <input type="date" name="passengers[0][birth_date]" class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                                    </div>
                                </div>

                                {{-- Passport --}}
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-2">Nomor Paspor / KTP</label>
                                    <input type="text" name="passengers[0][passport_number]" class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" 
                                           placeholder="Nomor Paspor/KTP" required>
                                </div>

                                {{-- Phone & Email --}}
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-semibold text-slate-700 mb-2">Nomor Telepon</label>
                                        <input type="tel" name="passengers[0][phone]" class="passenger-phone w-full px-4 py-3 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" 
                                               placeholder="81234567890">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-slate-700 mb-2">Email</label>
                                        <input type="email" name="passengers[0][email]" class="passenger-email w-full px-4 py-3 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" 
                                               placeholder="email@example.com">
                                    </div>
                                </div>

                                {{-- Frequent Flyer --}}
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-2">Frequent Flyer</label>
                                    <input type="text" name="passengers[0][frequent_flyer_number]" class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" 
                                           placeholder="Informasi Frequent Flyer">
                                    <p class="text-xs text-slate-500 mt-1">Masukkan Frequent Flyer penumpang untuk dapatkan poin penerbangan.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Perlindungan Ekstra --}}
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <h2 class="text-xl font-bold text-slate-800 mb-4">Perlindungan Ekstra</h2>
                        <p class="text-sm text-slate-500 mb-4">Lindungi perjalananmu dengan asuransi pilihan</p>

                        <div class="space-y-4">
                            @foreach($insuranceOptions as $insurance)
                            <label class="block bg-white border-2 border-slate-200 rounded-xl p-5 cursor-pointer hover:border-blue-300 transition-colors insurance-card">
                                <div class="flex items-start gap-4">
                                    <input type="checkbox" name="selected_insurances[]" value="{{ $insurance->id }}" 
                                           class="insurance-checkbox mt-1 w-5 h-5 text-blue-600 rounded">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-2 mb-2">
                                            <span class="text-2xl">{{ $insurance->icon }}</span>
                                            <h4 class="font-bold text-slate-800">{{ $insurance->name }}</h4>
                                        </div>
                                        <div class="bg-blue-50 rounded-lg p-3 mb-3">
                                            <p class="text-sm text-slate-700">✓ {{ $insurance->description }}</p>
                                        </div>
                                        <div class="flex items-center justify-between">
                                            <p class="text-red-600 font-bold">IDR {{ number_format($insurance->price, 0, ',', '.') }}<span class="text-slate-500 text-sm font-normal">/pax</span></p>
                                            <button type="button" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-lg transition-colors toggle-insurance">
                                                + Tambahkan
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- Yang akan Kamu Dapatkan --}}
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <h2 class="text-xl font-bold text-slate-800 mb-4">Yang akan Kamu Dapatkan</h2>
                        <div class="bg-white border-2 border-slate-200 rounded-xl p-5">
                            <div class="flex items-center gap-3">
                                <span class="text-2xl">🎉</span>
                                <div>
                                    <h4 class="font-bold text-slate-800">Payday Deals</h4>
                                    <p class="text-sm text-slate-500">Gajian! Pilihan tiket yang tepat bikin terbang jadi lebih hemat.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Tarif Aman --}}
                    <div class="bg-blue-50 rounded-lg p-4 flex items-center gap-3">
                        <svg class="w-5 h-5 text-blue-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        <p class="text-sm text-slate-700">Tarif diamankan. Segera pesan sebelum berubah.</p>
                    </div>

                    {{-- Total Pembayaran --}}
                    <div class="bg-white border-2 border-slate-200 rounded-xl p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="font-bold text-slate-800">Total Pembayaran</h3>
                            <div class="text-right">
                                <p class="text-sm text-slate-500 line-through" id="originalPrice">IDR {{ number_format($flight->price + 25000 + ($flight->price * 0.11), 0, ',', '.') }}</p>
                                <p class="text-2xl font-bold text-slate-800" id="totalPrice">IDR {{ number_format($flight->price + 25000 + ($flight->price * 0.11), 0, ',', '.') }}</p>
                            </div>
                        </div>
                        <button type="submit" class="w-full py-4 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl transition-colors text-lg">
                            Lanjut Bayar
                        </button>
                        <div class="mt-3 bg-green-50 rounded-lg p-3 flex items-center justify-center gap-2">
                            <span class="text-green-600 text-sm font-semibold">🎉 Hore! Kamu hemat dan dapat cashback poin</span>
                        </div>
                    </div>

                {{-- FORM TAG DITUTUP DI SINI --}}
                </form>
            </div>

            {{-- RIGHT COLUMN - Flight Summary (DI LUAR FORM) --}}
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-sm p-6 sticky top-6">
                    <h3 class="font-bold text-slate-800 mb-4 flex items-center gap-2">
                        <span>{{ $flight->departureAirport->city }}</span>
                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                        </svg>
                        <span>{{ $flight->arrivalAirport->city }}</span>
                    </h3>

                    {{-- Flight Details --}}
                    <div class="space-y-4 mb-6">
                        <div class="border border-slate-200 rounded-lg p-4">
                            <div class="flex items-center gap-2 mb-3">
                                <span class="px-2 py-1 bg-blue-100 text-blue-700 text-xs font-bold rounded">Pergi</span>
                                <span class="text-sm font-semibold text-slate-700">{{ $flight->departure_time->format('D, d M Y') }}</span>
                                <a href="#" class="ml-auto text-blue-600 text-sm font-bold hover:underline">Detail</a>
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-slate-100 rounded-full flex items-center justify-center">
                                        <span class="text-lg">✈️</span>
                                    </div>
                                    <div>
                                        <p class="text-lg font-bold text-slate-800">{{ $flight->departure_time->format('H:i') }}</p>
                                        <p class="text-xs text-slate-500">{{ $flight->departureAirport->iata_code }}</p>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <p class="text-xs text-slate-500">{{ $flight->duration_formatted }}</p>
                                    <p class="text-xs text-slate-400">Langsung</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-lg font-bold text-slate-800">{{ $flight->arrival_time->format('H:i') }}</p>
                                    <p class="text-xs text-slate-500">{{ $flight->arrivalAirport->iata_code }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="flex gap-2 text-xs">
                            <span class="text-green-600 font-semibold">✓ Bisa Refund</span>
                            <span class="text-slate-300">•</span>
                            <span class="text-green-600 font-semibold">✓ Bisa Reschedule</span>
                        </div>
                    </div>

                    {{-- Price Breakdown --}}
                    <div class="border-t border-slate-200 pt-4 space-y-3">
                        <div class="flex justify-between text-sm">
                            <span class="text-slate-600">Harga Tiket (1 penumpang)</span>
                            <span class="font-semibold">IDR {{ number_format($flight->price, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-slate-600">Convenience Fee</span>
                            <span class="font-semibold">IDR 25.000</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-slate-600">Pajak (PPN 11%)</span>
                            <span class="font-semibold text-green-600">Termasuk</span>
                        </div>
                        <div class="flex justify-between text-sm" id="insuranceRow" style="display: none;">
                            <span class="text-slate-600">Asuransi</span>
                            <span class="font-semibold" id="insuranceTotal">IDR 0</span>
                        </div>
                        <div class="border-t border-slate-200 pt-3 flex justify-between">
                            <span class="font-bold text-slate-800">Total Pembayaran</span>
                            <div class="text-right">
                                <p class="text-xs text-slate-500 line-through" id="sidebarOriginalPrice">IDR {{ number_format($flight->price + 25000 + ($flight->price * 0.11), 0, ',', '.') }}</p>
                                <p class="text-xl font-bold text-slate-800" id="sidebarTotalPrice">IDR {{ number_format($flight->price + 25000 + ($flight->price * 0.11), 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Points --}}
                    <div class="mt-4 pt-4 border-t border-slate-200 text-center">
                        <p class="text-sm text-slate-600">Dapat <span class="font-bold text-blue-600">{{ floor(($flight->price + 25000 + ($flight->price * 0.11)) / 1000) }}</span> Blibli Tiket Points</p>
                    </div>

                    {{-- Terms --}}
                    <div class="mt-4 pt-4 border-t border-slate-200">
                        <p class="text-xs text-slate-500 text-center">
                            Dengan menekan tombol bayar, kamu menyetujui <a href="#" class="text-blue-600 hover:underline">Syarat & Ketentuan</a> dan <a href="#" class="text-blue-600 hover:underline">Kebijakan Privasi</a> drgMaskapai
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Toggle "Sama dengan pemesan"
    document.querySelectorAll('.same-as-booker').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const form = this.closest('.passenger-form');
            const nameInput = form.querySelector('.passenger-name');
            const phoneInput = form.querySelector('.passenger-phone');
            const emailInput = form.querySelector('.passenger-email');
            
            if (this.checked) {
                nameInput.value = document.querySelector('input[name="booker_name"]').value;
                phoneInput.value = document.querySelector('input[name="booker_phone"]').value;
                emailInput.value = document.querySelector('input[name="booker_email"]').value;
                nameInput.readOnly = true;
                phoneInput.readOnly = true;
                emailInput.readOnly = true;
                nameInput.classList.add('bg-slate-50');
                phoneInput.classList.add('bg-slate-50');
                emailInput.classList.add('bg-slate-50');
            } else {
                nameInput.readOnly = false;
                phoneInput.readOnly = false;
                emailInput.readOnly = false;
                nameInput.classList.remove('bg-slate-50');
                phoneInput.classList.remove('bg-slate-50');
                emailInput.classList.remove('bg-slate-50');
                nameInput.value = '';
                phoneInput.value = '';
                emailInput.value = '';
            }
        });
    });

    // Toggle Insurance
    document.querySelectorAll('.toggle-insurance').forEach(button => {
        button.addEventListener('click', function() {
            const card = this.closest('.insurance-card');
            const checkbox = card.querySelector('.insurance-checkbox');
            checkbox.checked = !checkbox.checked;
            updateInsuranceUI(card, checkbox.checked);
            calculateTotal();
        });
    });

    document.querySelectorAll('.insurance-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const card = this.closest('.insurance-card');
            updateInsuranceUI(card, this.checked);
            calculateTotal();
        });
    });

    function updateInsuranceUI(card, isChecked) {
        if (isChecked) {
            card.classList.add('border-blue-500', 'bg-blue-50');
            card.classList.remove('border-slate-200');
            const button = card.querySelector('.toggle-insurance');
            button.textContent = '✓ Ditambahkan';
            button.classList.remove('bg-blue-600', 'hover:bg-blue-700');
            button.classList.add('bg-green-600', 'hover:bg-green-700');
        } else {
            card.classList.remove('border-blue-500', 'bg-blue-50');
            card.classList.add('border-slate-200');
            const button = card.querySelector('.toggle-insurance');
            button.textContent = '+ Tambahkan';
            button.classList.remove('bg-green-600', 'hover:bg-green-700');
            button.classList.add('bg-blue-600', 'hover:bg-blue-700');
        }
    }

    function calculateTotal() {
        const basePrice = {{ $flight->price }};
        const convenienceFee = 25000;
        const tax = basePrice * 0.11;
        let insuranceTotal = 0;

        document.querySelectorAll('.insurance-checkbox:checked').forEach(checkbox => {
            const priceText = checkbox.closest('.insurance-card').querySelector('.text-red-600').textContent;
            const price = parseInt(priceText.replace(/[^0-9]/g, ''));
            insuranceTotal += price;
        });

        const total = basePrice + convenienceFee + tax + insuranceTotal;

        // Update UI
        document.getElementById('totalPrice').textContent = 'IDR ' + total.toLocaleString('id-ID');
        document.getElementById('sidebarTotalPrice').textContent = 'IDR ' + total.toLocaleString('id-ID');
        
        if (insuranceTotal > 0) {
            document.getElementById('insuranceRow').style.display = 'flex';
            document.getElementById('insuranceTotal').textContent = 'IDR ' + insuranceTotal.toLocaleString('id-ID');
        } else {
            document.getElementById('insuranceRow').style.display = 'none';
        }
    }

    // Auto-fill booker data to passenger on load
    document.addEventListener('DOMContentLoaded', function() {
        const bookerName = document.querySelector('input[name="booker_name"]').value;
        const bookerPhone = document.querySelector('input[name="booker_phone"]').value;
        const bookerEmail = document.querySelector('input[name="booker_email"]').value;
        
        document.querySelectorAll('.passenger-name').forEach(input => input.value = bookerName);
        document.querySelectorAll('.passenger-phone').forEach(input => input.value = bookerPhone);
        document.querySelectorAll('.passenger-email').forEach(input => input.value = bookerEmail);
    });
</script>
@endsection