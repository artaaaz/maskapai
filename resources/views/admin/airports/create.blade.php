<x-admin-layout>
    <x-slot name="header">Tambah Bandara</x-slot>
    <x-slot name="breadcrumb">
        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
        <span class="separator">/</span>
        <a href="{{ route('admin.airports.index') }}">Bandara</a>
        <span class="separator">/</span>
        <span>Tambah</span>
    </x-slot>
    <div class="card max-w-2xl">
        <div class="card-header"><h2>Tambah Bandara Baru</h2></div>
        <div class="card-body">
            <form action="{{ route('admin.airports.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label class="form-label">Kode IATA</label>
                    <input type="text" name="iata_code" class="form-input @error('iata_code') border-red-500 @enderror" value="{{ old('iata_code') }}" maxlength="3" required>
                    @error('iata_code') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Nama Bandara</label>
                    <input type="text" name="name" class="form-input @error('name') border-red-500 @enderror" value="{{ old('name') }}" required>
                    @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Kota</label>
                    <input type="text" name="city" class="form-input @error('city') border-red-500 @enderror" value="{{ old('city') }}" required>
                    @error('city') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Negara</label>
                    <input type="text" name="country" class="form-input @error('country') border-red-500 @enderror" value="{{ old('country') }}" required>
                    @error('country') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="flex gap-3">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="{{ route('admin.airports.index') }}" class="btn btn-outline">Batal</a>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>