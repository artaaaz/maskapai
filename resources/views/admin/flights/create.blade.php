<x-admin-layout>
    <x-slot name="header">Tambah Penerbangan</x-slot>
    <x-slot name="breadcrumb">
        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
        <span class="separator">/</span>
        <a href="{{ route('admin.flights.index') }}">Penerbangan</a>
        <span class="separator">/</span>
        <span>Tambah</span>
    </x-slot>
    <div class="card max-w-3xl">
        <div class="card-header"><h2>Tambah Penerbangan Baru</h2></div>
        <div class="card-body">
            <form action="{{ route('admin.flights.store') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="form-group">
                        <label class="form-label">Maskapai</label>
                        <select name="airline_id" class="form-select @error('airline_id') border-red-500 @enderror" required>
                            <option value="">Pilih Maskapai</option>
                            @foreach(\App\Models\Airline::all() as $airline)
                                <option value="{{ $airline->id }}" {{ old('airline_id') == $airline->id ? 'selected' : '' }}>{{ $airline->name }}</option>
                            @endforeach
                        </select>
                        @error('airline_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Flight Number</label>
                        <input type="text" name="flight_number" class="form-input @error('flight_number') border-red-500 @enderror" value="{{ old('flight_number') }}" required>
                        @error('flight_number') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Bandara Asal</label>
                        <select name="departure_airport_id" class="form-select @error('departure_airport_id') border-red-500 @enderror" required>
                            <option value="">Pilih Bandara</option>
                            @foreach(\App\Models\Airport::all() as $airport)
                                <option value="{{ $airport->id }}" {{ old('departure_airport_id') == $airport->id ? 'selected' : '' }}>{{ $airport->city }} ({{ $airport->iata_code }})</option>
                            @endforeach
                        </select>
                        @error('departure_airport_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Bandara Tujuan</label>
                        <select name="arrival_airport_id" class="form-select @error('arrival_airport_id') border-red-500 @enderror" required>
                            <option value="">Pilih Bandara</option>
                            @foreach(\App\Models\Airport::all() as $airport)
                                <option value="{{ $airport->id }}" {{ old('arrival_airport_id') == $airport->id ? 'selected' : '' }}>{{ $airport->city }} ({{ $airport->iata_code }})</option>
                            @endforeach
                        </select>
                        @error('arrival_airport_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Pesawat</label>
                        <select name="airplane_id" class="form-select @error('airplane_id') border-red-500 @enderror" required>
                            <option value="">Pilih Pesawat</option>
                            @foreach(\App\Models\Airplane::all() as $airplane)
                                <option value="{{ $airplane->id }}" {{ old('airplane_id') == $airplane->id ? 'selected' : '' }}>{{ $airplane->model }} - {{ $airplane->registration_number }}</option>
                            @endforeach
                        </select>
                        @error('airplane_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Harga Economy (Starting Price)</label>
                        <input type="number" name="price" class="form-input @error('price') border-red-500 @enderror" value="{{ old('price') }}" min="0" required>
                        <p class="text-xs text-slate-400 mt-1">Harga ini akan menjadi harga Economy. Kelas Economy akan otomatis dibuat.</p>
                        @error('price') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Kursi Tersedia (Kuota Economy)</label>
                        <input type="number" name="available_seats" class="form-input @error('available_seats') border-red-500 @enderror" value="{{ old('available_seats') }}" min="0" required>
                        <p class="text-xs text-slate-400 mt-1">Jumlah kursi untuk kelas Economy</p>
                        @error('available_seats') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Waktu Keberangkatan</label>
                        <input type="datetime-local" name="departure_time" class="form-input @error('departure_time') border-red-500 @enderror" value="{{ old('departure_time') }}" required>
                        @error('departure_time') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Waktu Kedatangan</label>
                        <input type="datetime-local" name="arrival_time" class="form-input @error('arrival_time') border-red-500 @enderror" value="{{ old('arrival_time') }}" required>
                        @error('arrival_time') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
                <div class="flex gap-3 pt-4">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="{{ route('admin.flights.index') }}" class="btn btn-outline">Batal</a>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>