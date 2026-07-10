    <x-admin-layout>
        <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <h2 class="text-xl font-bold text-black">Kelola Maskapai</h2>
                <a href="{{ route('admin.airlines.create') }}" class="px-4 py-1 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg">+ Tambah Maskapai</a>
            </div>
        </div>
    </x-slot>>

    <div class="py-6">
        <div class="w-full px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase">Logo</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase">Nama</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase">Kode</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase">Deskripsi</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($airlines as $airline)
                            <tr class="hover:bg-slate-50">
                                <td class="px-6 py-4">
                                    @if($airline->logo)
                                        <img src="{{ asset('storage/' . $airline->logo) }}" class="w-12 h-12 object-contain rounded-lg">
                                    @else
                                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                            <span class="text-blue-600 font-bold">{{ substr($airline->name, 0, 2) }}</span>
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4"><span class="text-sm font-bold text-slate-900">{{ $airline->name }}</span></td>
                                <td class="px-6 py-4"><span class="px-2 py-1 bg-blue-100 text-blue-700 text-xs font-bold rounded">{{ $airline->code }}</span></td>
                                <td class="px-6 py-4"><span class="text-sm text-slate-600">{{ Str::limit($airline->description, 50) }}</span></td>
                                <td class="px-6 py-4">
                                    <div class="flex gap-2">
                                        <a href="{{ route('admin.airlines.edit', $airline) }}" class="px-3 py-1 bg-blue-500 hover:bg-blue-600 text-white text-xs font-semibold rounded">Edit</a>
                                        <form action="{{ route('admin.airlines.destroy', $airline) }}" method="POST" class="inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="px-3 py-1 bg-red-500 hover:bg-red-600 text-white text-xs font-semibold rounded" onclick="return confirm('Yakin hapus?')">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="px-6 py-8 text-center text-slate-500 text-sm">Belum ada data maskapai</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>