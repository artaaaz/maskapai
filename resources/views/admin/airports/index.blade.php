<x-admin-layout>
 <x-slot name="header">
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-4">
            <h2 class="text-xl font-bold text-black">Kelola Bandara</h2>
            <a href="{{ route('admin.airports.create') }}" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg">+ Tambah Bandara</a>
        </div>
    </div>
</x-slot>>

    <div class="py-6">
        <div class="w-full px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">{{ session('success') }}</div>
            @endif

            <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase">Kode IATA</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase">Nama Bandara</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase">Kota</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase">Negara</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($airports as $airport)
                            <tr class="hover:bg-slate-50">
                                <td class="px-6 py-4"><span class="px-3 py-1 bg-green-100 text-green-700 text-xs font-bold rounded">{{ $airport->iata_code }}</span></td>
                                <td class="px-6 py-4"><span class="text-sm font-bold text-slate-900">{{ $airport->name }}</span></td>
                                <td class="px-6 py-4"><span class="text-sm text-slate-600">{{ $airport->city }}</span></td>
                                <td class="px-6 py-4"><span class="text-sm text-slate-600">{{ $airport->country }}</span></td>
                                <td class="px-6 py-4">
                                    <div class="flex gap-2">
                                        <a href="{{ route('admin.airports.edit', $airport) }}" class="px-3 py-1 bg-green-500 hover:bg-green-600 text-white text-xs font-semibold rounded">Edit</a>
                                        <form action="{{ route('admin.airports.destroy', $airport) }}" method="POST" class="inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="px-3 py-1 bg-red-500 hover:bg-red-600 text-white text-xs font-semibold rounded" onclick="return confirm('Yakin hapus?')">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="px-6 py-8 text-center text-slate-500 text-sm">Belum ada data bandara</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>