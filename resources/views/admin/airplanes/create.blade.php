<x-admin-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-black">Tambah Pesawat</h2>
            <a href="{{ route('admin.airplanes.index') }}" class="px-6 py-2 bg-slate-600 hover:bg-slate-700 text-white font-bold rounded-lg transition-colors">
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-lg p-8">
                <form action="{{ route('admin.airplanes.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">
                                Maskapai <span class="text-red-500">*</span>
                            </label>
                            <select name="airline_id" class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 @error('airline_id') border-red-500 @enderror" required>
                                <option value="">Pilih Maskapai</option>
                                @foreach($airlines as $airline)
                                    <option value="{{ $airline->id }}" {{ old('airline_id') == $airline->id ? 'selected' : '' }}>
                                        {{ $airline->name }} ({{ $airline->code }})
                                    </option>
                                @endforeach
                            </select>
                            @error('airline_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">
                                Model Pesawat <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   name="model" 
                                   value="{{ old('model') }}" 
                                   placeholder="Contoh: Boeing 737-800, Airbus A320"
                                   class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 @error('model') border-red-500 @enderror" 
                                   required>
                            @error('model')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">
                                Nomor Registrasi <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   name="registration_number" 
                                   value="{{ old('registration_number') }}" 
                                   placeholder="Contoh: PK-GPA, PK-GIB"
                                   class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 @error('registration_number') border-red-500 @enderror" 
                                   required>
                            @error('registration_number')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">
                                Kapasitas (Jumlah Kursi) <span class="text-red-500">*</span>
                            </label>
                            <input type="number" 
                                   name="capacity" 
                                   value="{{ old('capacity') }}" 
                                   min="1" 
                                   placeholder="Contoh: 180"
                                   class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 @error('capacity') border-red-500 @enderror" 
                                   required>
                            @error('capacity')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">
                                Deskripsi
                            </label>
                            <textarea name="description" 
                                      rows="4" 
                                      placeholder="Deskripsi pesawat (opsional)"
                                      class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">
                                Foto Pesawat
                            </label>
                            <input type="file" 
                                   name="photos" 
                                   accept="image/*"
                                   class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 @error('photos') border-red-500 @enderror">
                            @error('photos')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-slate-500 text-xs mt-1">Format: JPG, PNG. Max: 2MB</p>
                        </div>
                    </div>

                    <div class="flex gap-3 mt-8 pt-6 border-t border-slate-200">
                        <button type="submit" class="px-8 py-3 bg-purple-600 hover:bg-purple-700 text-white font-bold rounded-lg transition-colors">
                            Simpan
                        </button>
                        <a href="{{ route('admin.airplanes.index') }}" class="px-8 py-3 bg-slate-300 hover:bg-slate-400 text-slate-700 font-bold rounded-lg transition-colors">
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-admin-layout>