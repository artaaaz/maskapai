<x-admin-layout>
    <x-slot name="header">Promo</x-slot>
    <x-slot name="breadcrumb">
        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
        <span class="separator">/</span>
        <span>Promo</span>
    </x-slot>

    <div class="card">
        <div class="card-header">
            <h2>Daftar Promo</h2>
            <a href="{{ route('admin.promos.create') }}" class="btn btn-success">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Tambah Promo
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
                        <th>Kode</th>
                        <th>Nama</th>
                        <th>Diskon</th>
                        <th>Berlaku</th>
                        <th>Pemakaian</th>
                        <th>Status</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($promos as $promo)
                    <tr>
                        <td>
                            <span class="badge badge-blue">{{ $promo->code }}</span>
                        </td>
                        <td>
                            <span class="font-semibold text-slate-900">{{ $promo->name }}</span>
                        </td>
                        <td>
                            @if($promo->discount_type == 'percentage')
                                <span class="font-bold text-green-600">{{ $promo->discount_value }}%</span>
                            @else
                                <span class="font-bold text-green-600">Rp{{ number_format($promo->discount_value, 0, ',', '.') }}</span>
                            @endif
                        </td>
                        <td>
                            <span class="text-xs text-slate-500">{{ $promo->valid_from->format('d/m/Y') }} - {{ $promo->valid_until->format('d/m/Y') }}</span>
                        </td>
                        <td>
                            <span class="text-xs text-slate-500">{{ $promo->used_count }}{{ ($promo->usage_limit ?? 0) > 0 ? '/' . $promo->usage_limit : '' }}</span>
                        </td>
                        <td>
                            @php
                                $badge = $promo->status_badge ?? [
                                    'label' => 'Unknown',
                                    'class' => 'bg-gray-100 text-gray-600'
                                ];
                            @endphp
                            <span class="badge {{ $badge['class'] }}">{{ $badge['label'] }}</span>
                        </td>
                        <td class="text-right">
                            <div class="action-group justify-end">
                                <a href="{{ route('admin.promos.edit', $promo) }}" class="btn btn-primary btn-sm">
                                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                    Edit
                                </a>
                                <form action="{{ route('admin.promos.toggle', $promo) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm {{ $promo->is_active ? 'btn-warning' : 'btn-success' }}">
                                        {{ $promo->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                    </button>
                                </form>
                                <form action="{{ route('admin.promos.destroy', $promo) }}" method="POST" class="inline" onsubmit="return confirm('Hapus promo ini?')">
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
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"></path>
                                    </svg>
                                </div>
                                <h3>Belum Ada Promo</h3>
                                <p>Tambahkan promo baru untuk menarik pelanggan.</p>
                                <a href="{{ route('admin.promos.create') }}" class="btn btn-success mt-4">+ Tambah Promo</a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if(isset($promos) && $promos->hasPages())
        <div class="pagination">
            {{ $promos->links() }}
        </div>
        @endif
    </div>
</x-admin-layout>