<x-admin-layout>
    <x-slot name="header">Pesawat</x-slot>
    <x-slot name="breadcrumb">
        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
        <span class="separator">/</span>
        <span>Pesawat</span>
    </x-slot>

    <div class="card">
        <div class="card-header">
            <h2>Daftar Pesawat</h2>
            <a href="{{ route('admin.airplanes.create') }}" class="btn btn-success">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Tambah Pesawat
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
                        <th>Maskapai</th>
                        <th>Model</th>
                        <th>Registrasi</th>
                        <th>Kapasitas</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($airplanes as $airplane)
                    <tr>
                        <td>
                            <span class="font-semibold text-slate-900">{{ $airplane->airline->name }}</span>
                        </td>
                        <td>
                            <span class="text-sm text-slate-600">{{ $airplane->model }}</span>
                        </td>
                        <td>
                            <span class="badge badge-purple">{{ $airplane->registration_number }}</span>
                        </td>
                        <td>
                            <span class="text-sm text-slate-600">{{ $airplane->capacity }} kursi</span>
                        </td>
                        <td class="text-right">
                            <div class="action-group justify-end">
                                <a href="{{ route('admin.airplanes.edit', $airplane) }}" class="btn btn-primary btn-sm">
                                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                    Edit
                                </a>
                                <form action="{{ route('admin.airplanes.destroy', $airplane) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus pesawat ini?')">
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
                        <td colspan="5">
                            <div class="empty-state">
                                <div class="empty-icon">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"></path>
                                    </svg>
                                </div>
                                <h3>Belum Ada Pesawat</h3>
                                <p>Tambahkan pesawat baru untuk mulai mengelola data penerbangan.</p>
                                <a href="{{ route('admin.airplanes.create') }}" class="btn btn-success mt-4">+ Tambah Pesawat</a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-admin-layout>