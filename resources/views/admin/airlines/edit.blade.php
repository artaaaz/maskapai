    <x-admin-layout>
        <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <h2 class="text-xl font-bold text-black">Edit Maskapai</h2>
                <a href="{{ route('admin.airlines.index') }}" class="px-4 py-1 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg">Kembali</a>
            </div>
        </div>
    </x-slot>>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <form action="{{ route('admin.airlines.update', $airline) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-4">
                        <label class="block text-sm font-bold text-slate-700 mb-2">Nama Maskapai *</label>
                        <input type="text" name="name" value="{{ old('name', $airline->name) }}" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('name') border-red-500 @enderror" required>
                        @error('name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-bold text-slate-700 mb-2">Kode Maskapai *</label>
                        <input type="text" name="code" value="{{ old('code', $airline->code) }}" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('code') border-red-500 @enderror" required>
                        @error('code')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-bold text-slate-700 mb-2">Deskripsi</label>
                        <textarea name="description" rows="3" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('description') border-red-500 @enderror">{{ old('description', $airline->description) }}</textarea>
                        @error('description')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-bold text-slate-700 mb-2">Logo</label>
                        @if($airline->logo)
                            <div class="mb-2">
                                <img src="{{ asset('storage/' . $airline->logo) }}" alt="{{ $airline->name }}" class="w-20 h-20 object-contain">
                            </div>
                        @endif
                        <input type="file" name="logo" accept="image/*" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('logo')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex gap-3">
                        <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-colors">Update</button>
                        <a href="{{ route('admin.airlines.index') }}" class="px-6 py-2 bg-slate-300 hover:bg-slate-400 text-slate-700 font-semibold rounded-lg transition-colors">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-admin-layout>