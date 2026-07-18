<x-admin-layout>
    <x-slot name="header">Edit Promo</x-slot>
    <x-slot name="breadcrumb">
        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
        <span class="separator">/</span>
        <a href="{{ route('admin.promos.index') }}">Promo</a>
        <span class="separator">/</span>
        <span>Edit</span>
    </x-slot>
    <div class="card max-w-2xl">
        <div class="card-header"><h2>Edit Promo</h2></div>
        <div class="card-body">
            <form action="{{ route('admin.promos.update', $promo) }}" method="POST">
                @csrf @method('PUT')
                <div class="form-group">
                    <label class="form-label">Kode Promo</label>
                    <input type="text" name="code" class="form-input @error('code') border-red-500 @enderror" value="{{ old('code', $promo->code) }}" required>
                    @error('code') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Nama Promo</label>
                    <input type="text" name="name" class="form-input @error('name') border-red-500 @enderror" value="{{ old('name', $promo->name) }}" required>
                    @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Deskripsi</label>
                    <textarea name="description" rows="2" class="form-input @error('description') border-red-500 @enderror">{{ old('description', $promo->description) }}</textarea>
                    @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="form-group">
                        <label class="form-label">Tipe Diskon</label>
                        <select name="discount_type" class="form-select" required>
                            <option value="percentage" {{ old('discount_type', $promo->discount_type) == 'percentage' ? 'selected' : '' }}>Persentase (%)</option>
                            <option value="nominal" {{ old('discount_type', $promo->discount_type) == 'nominal' ? 'selected' : '' }}>Nominal (Rp)</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Nilai Diskon</label>
                        <input type="number" name="discount_value" class="form-input @error('discount_value') border-red-500 @enderror" value="{{ old('discount_value', $promo->discount_value) }}" min="0" required>
                        @error('discount_value') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Min. Transaksi</label>
                        <input type="number" name="min_transaction" class="form-input" value="{{ old('min_transaction', $promo->min_transaction) }}" min="0">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Maks. Diskon</label>
                        <input type="number" name="max_discount" class="form-input" value="{{ old('max_discount', $promo->max_discount) }}" min="0">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Berlaku Dari</label>
                        <input type="date" name="valid_from" class="form-input @error('valid_from') border-red-500 @enderror" value="{{ old('valid_from', $promo->valid_from ? $promo->valid_from->format('Y-m-d') : '') }}" required>
                        @error('valid_from') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Berlaku Sampai</label>
                        <input type="date" name="valid_until" class="form-input @error('valid_until') border-red-500 @enderror" value="{{ old('valid_until', $promo->valid_until ? $promo->valid_until->format('Y-m-d') : '') }}" required>
                        @error('valid_until') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Batas Pemakaian</label>
                        <input type="number" name="usage_limit" class="form-input" value="{{ old('usage_limit', $promo->usage_limit) }}" min="0">
                        <p class="text-xs text-slate-500 mt-1">0 = tidak terbatas</p>
                    </div>
                </div>
                <div class="flex gap-3 pt-4">
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    <a href="{{ route('admin.promos.index') }}" class="btn btn-outline">Batal</a>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>