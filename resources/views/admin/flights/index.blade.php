<x-admin-layout>
    <x-slot name="header">Jadwal Penerbangan</x-slot>
    <x-slot name="breadcrumb">
        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
        <span class="separator">/</span>
        <span>Jadwal Penerbangan</span>
    </x-slot>

    <div class="card">
        <div class="card-header">
            <h2>Daftar Penerbangan</h2>
            <a href="{{ route('admin.flights.create') }}" class="btn btn-success">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Tambah Penerbangan
            </a>
        </div>

        @if(session('success'))
            <div class="alert alert-success mx-6 mt-6">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                {{ session('success') }}
            </div>
        @endif

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Flight No</th>
                        <th>Maskapai</th>
                        <th>Rute</th>
                        <th>Waktu</th>
                        <th>Harga</th>
                        <th>Kursi</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($flights as $flight)
                    <tr>
                        <td>
                            <span class="font-semibold text-slate-900">{{ $flight->flight_number }}</span>
                        </td>
                        <td>
                            <span class="text-sm text-slate-600">{{ $flight->airline->name }}</span>
                        </td>
                        <td>
                            <span class="font-semibold text-slate-800 text-sm">{{ $flight->departureAirport->iata_code }} → {{ $flight->arrivalAirport->iata_code }}</span>
                            <p class="text-xs text-slate-500">{{ $flight->departureAirport->city }} → {{ $flight->arrivalAirport->city }}</p>
                        </td>
                        <td>
                            <p class="text-xs text-slate-800">{{ $flight->departure_time->format('d M Y, H:i') }}</p>
                            <p class="text-xs text-slate-500">s/d {{ $flight->arrival_time->format('d M Y, H:i') }}</p>
                        </td>
                        <td>
                            @php $ecoClass = $flight->flightClasses->firstWhere('class_name', 'economy'); @endphp
                            <span class="font-bold text-slate-900">Rp{{ number_format($ecoClass?->price ?? $flight->price, 0, ',', '.') }}</span>
                            <p class="text-xs text-slate-400">Starting Price (Economy)</p>
                        </td>
                        <td>
                            @php $ecoClass = $flight->flightClasses->firstWhere('class_name', 'economy'); @endphp
                            <span class="badge badge-gray">{{ $ecoClass?->seat_quota ?? $flight->available_seats }} kursi</span>
                            @if($flight->flightClasses->count() > 1)
                                <p class="text-xs text-slate-400 mt-1">{{ $flight->flightClasses->count() }} kelas</p>
                            @endif
                        </td>
                        <td class="text-right">
                            <div class="action-group justify-end">
                                <a href="{{ route('admin.flights.flight-classes.index', $flight) }}" class="btn btn-info btn-sm" style="background:#6366f1;color:white;">
                                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                                    Classes
                                </a>
                                <a href="{{ route('admin.flights.edit', $flight) }}" class="btn btn-primary btn-sm">
                                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                    Edit
                                </a>
                                <form action="{{ route('admin.flights.destroy', $flight) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus penerbangan ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7">
                            <div class="empty-state">
                                <div class="empty-icon">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <h3>Belum Ada Penerbangan</h3>
                                <p>Tambahkan jadwal penerbangan baru.</p>
                                <a href="{{ route('admin.flights.create') }}" class="btn btn-success mt-4">+ Tambah Penerbangan</a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-admin-layout>