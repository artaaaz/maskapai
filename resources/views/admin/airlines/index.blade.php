<x-admin-layout>
    <x-slot name="header">Maskapai</x-slot>
    <x-slot name="breadcrumb">
        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
        <span class="separator">/</span>
        <span>Maskapai</span>
    </x-slot>

    <div class="card">
        <div class="card-header">
            <h2>Daftar Maskapai</h2>
            <a href="{{ route('admin.airlines.create') }}" class="btn btn-success">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Tambah Maskapai
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
                        <th>Logo</th>
                        <th>Nama Maskapai</th>
                        <th>Kode</th>
                        <th>Deskripsi</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($airlines as $airline)
                    <tr>
                        <td>
                            @if($airline->logo)
                                <img src="{{ asset('storage/' . $airline->logo) }}" class="w-10 h-10 object-contain rounded-lg" alt="{{ $airline->name }}">
                            @else
                                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                    <span class="text-blue-600 font-bold text-sm">{{ substr($airline->name, 0, 2) }}</span>
                                </div>
                            @endif
                        </td>
                        <td>
                            <span class="font-semibold text-slate-900">{{ $airline->name }}</span>
                        </td>
                        <td>
                            <span class="badge badge-blue">{{ $airline->code }}</span>
                        </td>
                        <td>
                            <span class="text-sm text-slate-500">{{ Str::limit($airline->description, 50) }}</span>
                        </td>
                        <td class="text-right">
                            <div class="action-group justify-end">
                                <a href="{{ route('admin.airlines.edit', $airline) }}" class="btn btn-primary btn-sm">
                                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                    Edit
                                </a>
                                <form action="{{ route('admin.airlines.destroy', $airline) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus maskapai ini?')">
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
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                </div>
                                <h3>Belum Ada Maskapai</h3>
                                <p>Tambahkan maskapai baru untuk mulai mengelola data penerbangan.</p>
                                <a href="{{ route('admin.airlines.create') }}" class="btn btn-success mt-4">+ Tambah Maskapai</a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-admin-layout>