<x-admin-layout>
    <x-slot name="header">Edit Maskapai</x-slot>
    <x-slot name="breadcrumb">
        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
        <span class="separator">/</span>
        <a href="{{ route('admin.airlines.index') }}">Maskapai</a>
        <span class="separator">/</span>
        <span>Edit</span>
    </x-slot>

    <div class="card max-w-2xl">
        <div class="card-header">
            <h2>Edit Maskapai</h2>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.airlines.update', $airline) }}" method="POST" enctype="multipart/form-data">
                @csrf @method('PUT')
                <div class="form-group">
                    <label class="form-label">Nama Maskapai</label>
                    <input type="text" name="name" class="form-input @error('name') border-red-500 @enderror" value="{{ old('name', $airline->name) }}" required>
                    @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Kode Maskapai</label>
                    <input type="text" name="code" class="form-input @error('code') border-red-500 @enderror" value="{{ old('code', $airline->code) }}" required>
                    @error('code') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Logo</label>
                    <input type="file" name="logo" class="form-input @error('logo') border-red-500 @enderror">
                    @if($airline->logo) <p class="text-xs text-slate-500 mt-1">Biarkan kosong jika tidak ingin mengubah logo</p> @endif
                    @error('logo') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Deskripsi</label>
                    <textarea name="description" rows="3" class="form-input @error('description') border-red-500 @enderror">{{ old('description', $airline->description) }}</textarea>
                    @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="flex gap-3">
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    <a href="{{ route('admin.airlines.index') }}" class="btn btn-outline">Batal</a>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>