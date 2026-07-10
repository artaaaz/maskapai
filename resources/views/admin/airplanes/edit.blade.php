<x-admin-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-bold text-white">Edit Pesawat</h2>
            <a href="{{ route('admin.airplanes.index') }}" class="px-4 py-2 bg-slate-600 hover:bg-slate-700 text-white font-semibold rounded-lg">Kembali</a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <form action="{{ route('admin.airplanes.update', $airplane) }}" method="POST" enctype="multipart/form-data">
                    @csrf @method('PUT')
                    <div class="mb-4">
                        <label class="block text-sm font-bold text-slate-700 mb-2">Maskapai *</label>
                        <select name="airline_id" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 @error('airline_id') border-red-500 @enderror" required>
                            <option value="">Pilih Maskapai</option>
                            @foreach($airlines as $airline)
                                <option value="{{ $airline->id }}" {{ old('airline_id', $airplane->airline_id) == $airline->id ? 'selected' : '' }}>{{ $airline->name }} ({{ $airline->code }})</option>
                            @endforeach
                        </select>
                        @error('airline_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-bold text-slate-700 mb-2">Model Pesawat *</label>
                        <input type="text" name="model" value="{{ old('model', $airplane->model) }}" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 @error('model') border-red-500 @enderror" required>
                        @error('model')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-bold text-slate-700 mb-2">Nomor Registrasi *</label>
                        <input type="text" name="registration_number" value="{{ old('registration_number', $airplane->registration_number) }}" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 @error('registration_number') border-red-500 @enderror" required>
                        @error('registration_number')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-bold text-slate-700 mb-2">Kapasitas (jumlah kursi) *</label>
                        <input type="number" name="capacity" value="{{ old('capacity', $airplane->capacity) }}" min="1" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 @error('capacity') border-red-500 @enderror" required>
                        @error('capacity')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-bold text-slate-700 mb-2">Deskripsi</label>
                        <textarea name="description" rows="3" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">{{ old('description', $airplane->description) }}</textarea>
                        @error('description')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-bold text-slate-700 mb-2">Foto Pesawat</label>
                        @if($airplane->photos)
                            <div class="mb-2"><img src="{{ asset('storage/' . $airplane->photos) }}" class="w-32 h-20 object-cover rounded"></div>
                        @endif
                        <input type="file" name="photos" accept="image/*" class="w-full px-4 py-2 border border-slate-300 rounded-lg">
                        @error('photos')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div class="flex gap-3">
                        <button type="submit" class="px-6 py-2 bg-purple-600 hover:bg-purple-700 text-white font-semibold rounded-lg">Update</button>
                        <a href="{{ route('admin.airplanes.index') }}" class="px-6 py-2 bg-slate-300 hover:bg-slate-400 text-slate-700 font-semibold rounded-lg">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-admin-layout>