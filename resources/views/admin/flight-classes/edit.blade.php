<x-admin-layout>
    <x-slot name="header">Edit Kelas Penerbangan</x-slot>
    <x-slot name="breadcrumb">
        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
        <span class="separator">/</span>
        <a href="{{ route('admin.flights.index') }}">Jadwal Penerbangan</a>
        <span class="separator">/</span>
        <a href="{{ route('admin.flights.flight-classes.index', $flight) }}">Flight Classes</a>
        <span class="separator">/</span>
        <span>Edit</span>
    </x-slot>

    <div class="card">
        <div class="card-header">
            <h2>Edit Kelas: {{ $flightClass->class_name }} ({{ $flight->flight_number }})</h2>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.flights.flight-classes.update', [$flight, $flightClass]) }}" method="POST">
                @csrf @method('PUT')

                <div class="form-group">
                    <label class="form-label">Nama Kelas</label>
                    <select name="class_name" required class="form-select">
                        @foreach($classNames as $cn)
                        <option value="{{ $cn }}" {{ old('class_name', $flightClass->class_name) === $cn ? 'selected' : '' }}>{{ ucfirst(str_replace('_', ' ', $cn)) }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Harga (Rp)</label>
                    <input type="number" name="price" value="{{ old('price', $flightClass->price) }}" required min="0" class="form-input">
                </div>

                <div class="form-group">
                    <label class="form-label">Kuota Kursi</label>
                    <input type="number" name="seat_quota" value="{{ old('seat_quota', $flightClass->seat_quota) }}" required min="0" class="form-input">
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <button type="submit" class="btn btn-success">Simpan Perubahan</button>
                    <a href="{{ route('admin.flights.flight-classes.index', $flight) }}" class="btn btn-outline">Batal</a>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>