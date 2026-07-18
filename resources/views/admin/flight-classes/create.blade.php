<x-admin-layout>
    <x-slot name="header">Tambah Kelas Penerbangan</x-slot>
    <x-slot name="breadcrumb">
        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
        <span class="separator">/</span>
        <a href="{{ route('admin.flights.index') }}">Jadwal Penerbangan</a>
        <span class="separator">/</span>
        <a href="{{ route('admin.flights.flight-classes.index', $flight) }}">Flight Classes</a>
        <span class="separator">/</span>
        <span>Tambah</span>
    </x-slot>

    <div class="card">
        <div class="card-header">
            <h2>Tambah Kelas: {{ $flight->flight_number }} ({{ $flight->route }})</h2>
        </div>
        <div class="card-body">
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <form action="{{ route('admin.flights.flight-classes.store', $flight) }}" method="POST">
                @csrf
                <div class="form-group">
                    <label class="form-label">Nama Kelas</label>
                    <select name="class_name" required class="form-select">
                        @foreach($classNames as $cn)
                        <option value="{{ $cn }}" {{ old('class_name') === $cn ? 'selected' : '' }}>{{ ucfirst(str_replace('_', ' ', $cn)) }}</option>
                        @endforeach
                    </select>
                    @error('class_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Harga (Rp)</label>
                    <input type="number" name="price" value="{{ old('price') }}" required min="0" class="form-input">
                    @error('price') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Kuota Kursi</label>
                    <input type="number" name="seat_quota" value="{{ old('seat_quota') }}" required min="0" class="form-input">
                    @error('seat_quota') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <button type="submit" class="btn btn-success">Simpan</button>
                    <a href="{{ route('admin.flights.flight-classes.index', $flight) }}" class="btn btn-outline">Batal</a>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>