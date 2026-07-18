<x-admin-layout>
    <x-slot name="header">Tambah Pesawat</x-slot>
    <x-slot name="breadcrumb">
        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
        <span class="separator">/</span>
        <a href="{{ route('admin.airplanes.index') }}">Pesawat</a>
        <span class="separator">/</span>
        <span>Tambah</span>
    </x-slot>
    <div class="card max-w-2xl">
        <div class="card-header"><h2>Tambah Pesawat Baru</h2></div>
        <div class="card-body">
            <form action="{{ route('admin.airplanes.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label class="form-label">Maskapai</label>
                    <select name="airline_id" class="form-select @error('airline_id') border-red-500 @enderror" required>
                        <option value="">Pilih Maskapai</option>
                        @foreach(\App\Models\Airline::all() as $airline)
                            <option value="{{ $airline->id }}" {{ old('airline_id') == $airline->id ? 'selected' : '' }}>{{ $airline->name }} ({{ $airline->code }})</option>
                        @endforeach
                    </select>
                    @error('airline_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Model Pesawat</label>
                    <input type="text" name="model" class="form-input @error('model') border-red-500 @enderror" value="{{ old('model') }}" required>
                    @error('model') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Nomor Registrasi</label>
                    <input type="text" name="registration_number" class="form-input @error('registration_number') border-red-500 @enderror" value="{{ old('registration_number') }}" required>
                    @error('registration_number') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Kapasitas Kursi</label>
                    <input type="number" name="capacity" class="form-input @error('capacity') border-red-500 @enderror" value="{{ old('capacity') }}" min="1" required>
                    @error('capacity') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="flex gap-3">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="{{ route('admin.airplanes.index') }}" class="btn btn-outline">Batal</a>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>