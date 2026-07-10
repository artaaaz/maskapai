<x-admin-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-bold text-white">Edit Bandara</h2>
            <a href="{{ route('admin.airports.index') }}" class="px-4 py-2 bg-slate-600 hover:bg-slate-700 text-white font-semibold rounded-lg">Kembali</a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <form action="{{ route('admin.airports.update', $airport) }}" method="POST">
                    @csrf @method('PUT')
                    <div class="mb-4">
                        <label class="block text-sm font-bold text-slate-700 mb-2">Kode IATA *</label>
                        <input type="text" name="iata_code" value="{{ old('iata_code', $airport->iata_code) }}" maxlength="3" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 @error('iata_code') border-red-500 @enderror" required>
                        @error('iata_code')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-bold text-slate-700 mb-2">Nama Bandara *</label>
                        <input type="text" name="name" value="{{ old('name', $airport->name) }}" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 @error('name') border-red-500 @enderror" required>
                        @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-bold text-slate-700 mb-2">Kota *</label>
                        <input type="text" name="city" value="{{ old('city', $airport->city) }}" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 @error('city') border-red-500 @enderror" required>
                        @error('city')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-bold text-slate-700 mb-2">Negara *</label>
                        <input type="text" name="country" value="{{ old('country', $airport->country) }}" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 @error('country') border-red-500 @enderror" required>
                        @error('country')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div class="flex gap-3">
                        <button type="submit" class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg">Update</button>
                        <a href="{{ route('admin.airports.index') }}" class="px-6 py-2 bg-slate-300 hover:bg-slate-400 text-slate-700 font-semibold rounded-lg">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-admin-layout>