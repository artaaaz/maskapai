<x-admin-layout>
    <x-slot name="header">Kelas Penerbangan</x-slot>
    <x-slot name="breadcrumb">
        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
        <span class="separator">/</span>
        <a href="{{ route('admin.flights.index') }}">Jadwal Penerbangan</a>
        <span class="separator">/</span>
        <span>Flight Classes</span>
    </x-slot>

    <div class="card">
        <div class="card-header">
            <h2>Kelas Penerbangan: {{ $flight->flight_number }} ({{ $flight->route }})</h2>
            <a href="{{ route('admin.flights.flight-classes.create', $flight) }}" class="btn btn-success">+ Tambah Kelas</a>
        </div>

        @if(session('success'))
            <div class="alert alert-success mx-6 mt-6">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger mx-6 mt-6">{{ session('error') }}</div>
        @endif

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Kelas</th>
                        <th>Harga</th>
                        <th>Kuota</th>
                        <th>Tersedia</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($flight->flightClasses as $fc)
                    <tr>
                        <td><span class="font-bold capitalize">{{ str_replace('_', ' ', $fc->class_name) }}</span></td>
                        <td><span class="font-bold text-slate-900">Rp {{ number_format($fc->price, 0, ',', '.') }}</span></td>
                        <td>{{ $fc->seat_quota }}</td>
                        <td>
                            <span class="badge {{ $fc->available_quota > 0 ? 'badge-green' : 'badge-red' }}">
                                {{ $fc->available_quota }}
                            </span>
                        </td>
                        <td class="text-right">
                            <div class="action-group justify-end">
                                <a href="{{ route('admin.flights.flight-classes.edit', [$flight, $fc]) }}" class="btn btn-primary btn-sm">Edit</a>
                                <form action="{{ route('admin.flights.flight-classes.destroy', [$flight, $fc]) }}" method="POST" class="inline" onsubmit="return confirm('Hapus kelas ini?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-danger btn-sm">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5">
                            <div class="empty-state">
                                <div class="empty-icon">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </div>
                                <h3>Belum Ada Kelas Penerbangan</h3>
                                <p>Tambahkan kelas untuk flight {{ $flight->flight_number }}.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-admin-layout>