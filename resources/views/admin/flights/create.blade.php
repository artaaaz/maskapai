<x-admin-layout>
   <x-slot name="header">
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-4">
            <h2 class="text-xl font-bold text-black">Tambah Penerbangan</h2>
            <a href="{{ route('admin.flights.index') }}" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg">Kembali</a>
        </div>
    </div>
</x-slot>>

    <div class="py-6">
        <div class="max-w-4xl mx-auto px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-lg p-8">
                <form action="{{ route('admin.flights.store') }}" method="POST">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Maskapai --}}
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">
                                Maskapai <span class="text-red-500">*</span>
                            </label>
                            <select name="airline_id" class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 @error('airline_id') border-red-500 @enderror" required>
                                <option value="">Pilih Maskapai</option>
                                @foreach($airlines as $airline)
                                    <option value="{{ $airline->id }}" {{ old('airline_id') == $airline->id ? 'selected' : '' }}>
                                        {{ $airline->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('airline_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Pesawat --}}
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">
                                Pesawat <span class="text-red-500">*</span>
                            </label>
                            <select name="airplane_id" class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 @error('airplane_id') border-red-500 @enderror" required>
                                <option value="">Pilih Pesawat</option>
                                @foreach($airplanes as $airplane)
                                    <option value="{{ $airplane->id }}" {{ old('airplane_id') == $airplane->id ? 'selected' : '' }}>
                                        {{ $airplane->model }} ({{ $airplane->registration_number }})
                                    </option>
                                @endforeach
                            </select>
                            @error('airplane_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Bandara Keberangkatan --}}
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">
                                Bandara Keberangkatan <span class="text-red-500">*</span>
                            </label>
                            <select name="departure_airport_id" class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 @error('departure_airport_id') border-red-500 @enderror" required>
                                <option value="">Pilih Bandara</option>
                                @foreach($airports as $airport)
                                    <option value="{{ $airport->id }}" {{ old('departure_airport_id') == $airport->id ? 'selected' : '' }}>
                                        {{ $airport->name }} ({{ $airport->iata_code }})
                                    </option>
                                @endforeach
                            </select>
                            @error('departure_airport_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Bandara Tujuan --}}
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">
                                Bandara Tujuan <span class="text-red-500">*</span>
                            </label>
                            <select name="arrival_airport_id" class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 @error('arrival_airport_id') border-red-500 @enderror" required>
                                <option value="">Pilih Bandara</option>
                                @foreach($airports as $airport)
                                    <option value="{{ $airport->id }}" {{ old('arrival_airport_id') == $airport->id ? 'selected' : '' }}>
                                        {{ $airport->name }} ({{ $airport->iata_code }})
                                    </option>
                                @endforeach
                            </select>
                            @error('arrival_airport_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Nomor Penerbangan --}}
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">
                                Nomor Penerbangan <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   name="flight_number" 
                                   value="{{ old('flight_number') }}" 
                                   placeholder="Contoh: GA123, JT456"
                                   class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 @error('flight_number') border-red-500 @enderror" 
                                   required>
                            @error('flight_number')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Harga Tiket --}}
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">
                                Harga Tiket (Rp) <span class="text-red-500">*</span>
                            </label>
                            <input type="number" 
                                   name="price" 
                                   value="{{ old('price') }}" 
                                   min="0" 
                                   placeholder="Contoh: 500000"
                                   class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 @error('price') border-red-500 @enderror" 
                                   required>
                            @error('price')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Waktu Keberangkatan --}}
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">
                                Waktu Keberangkatan <span class="text-red-500">*</span>
                            </label>
                            <input type="datetime-local" 
                                   name="departure_time" 
                                   value="{{ old('departure_time') }}" 
                                   class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 @error('departure_time') border-red-500 @enderror" 
                                   required>
                            @error('departure_time')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Waktu Kedatangan --}}
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">
                                Waktu Kedatangan <span class="text-red-500">*</span>
                            </label>
                            <input type="datetime-local" 
                                   name="arrival_time" 
                                   value="{{ old('arrival_time') }}" 
                                   class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 @error('arrival_time') border-red-500 @enderror" 
                                   required>
                            @error('arrival_time')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Kursi Tersedia --}}
                        <div class="md:col-span-2">
                            <label class="block text-sm font-bold text-slate-700 mb-2">
                                Kursi Tersedia <span class="text-red-500">*</span>
                            </label>
                            <input type="number" 
                                   name="available_seats" 
                                   value="{{ old('available_seats') }}" 
                                   min="0" 
                                   placeholder="Contoh: 180"
                                   class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 @error('available_seats') border-red-500 @enderror" 
                                   required>
                            @error('available_seats')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex gap-3 mt-8 pt-6 border-t border-slate-200">
                        <button type="submit" class="px-8 py-3 bg-orange-600 hover:bg-orange-700 text-white font-bold rounded-lg transition-colors">
                            Simpan
                        </button>
                        <a href="{{ route('admin.flights.index') }}" class="px-8 py-3 bg-slate-300 hover:bg-slate-400 text-slate-700 font-bold rounded-lg transition-colors">
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-admin-layout>