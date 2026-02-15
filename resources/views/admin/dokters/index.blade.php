@extends('layouts.admin')

@section('title', 'Data Dokter')

@section('content')
    <div class="mb-8 flex justify-between items-end">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Data Dokter</h1>
            <p class="text-slate-500">Kelola informasi dokter</p>
        </div>
        <a href="{{ route('admin.dokters.create') }}"
            class="bg-blue-600 text-white px-6 py-2 rounded-xl font-bold text-sm hover:bg-blue-700 transition-colors flex items-center">
            <span class="mr-2">➕</span> Tambah Dokter
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <!-- Filter Bar -->
        <div class="p-6 border-b border-slate-100 bg-slate-50/50">
            <form action="{{ route('admin.dokters.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
                <div class="flex-grow">
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Cari Nama, Username, No. HP, atau NIP/NRP..."
                        class="w-full px-4 py-2 rounded-xl border border-slate-200 focus:border-blue-500 outline-none text-sm transition-all">
                </div>
                <button type="submit"
                    class="bg-slate-900 text-white px-8 py-2 rounded-xl font-bold text-sm hover:bg-black transition-colors">
                    Cari
                </button>
            </form>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 text-slate-400 text-xs font-black uppercase tracking-wider">
                        <th class="px-6 py-4">Nama</th>
                        <th class="px-6 py-4">NIP / NRP</th>
                        <th class="px-6 py-4">SIP</th>
                        <th class="px-6 py-4">No. HP</th>
                        <th class="px-6 py-4">Alamat</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($dokters as $dokter)
                        <tr class="hover:bg-slate-50/50 transition-colors group">
                            <td class="px-6 py-4 text-sm font-bold text-slate-900">
                                <div class="flex items-center">
                                    <div
                                        class="h-10 w-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold mr-3 border border-blue-200">
                                        {{ substr($dokter->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="font-bold text-slate-900">{{ $dokter->name }}</p>
                                        <p class="text-xs text-slate-500 font-normal">@ {{ $dokter->username }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm text-slate-600 font-bold tracking-wider">{{ $dokter->nrp ?? '-' }}</p>
                            </td>
                            <td class="px-6 py-4">
                                @if ($dokter->sip_file)
                                    <a href="{{ $dokter->sip_url }}" target="_blank"
                                        class="px-3 py-1 bg-blue-50 text-blue-600 rounded-lg text-xs font-bold hover:bg-blue-100 transition-colors flex items-center w-fit">
                                        <span class="mr-1">📄</span> Lihat
                                    </a>
                                @else
                                    <span class="text-xs text-slate-400 italic">Belum ada</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm text-slate-600">{{ $dokter->phone ?? '-' }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-xs text-slate-500 line-clamp-2 max-w-xs">{{ $dokter->address ?? '-' }}</p>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('admin.dokters.edit', $dokter->id) }}"
                                    class="text-blue-600 font-bold text-xs hover:underline mr-3">Edit</a>
                                <form action="{{ route('admin.dokters.destroy', $dokter->id) }}" method="POST"
                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus dokter ini?');"
                                    class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="text-red-600 font-bold text-xs hover:underline">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-slate-400 italic">Tidak ada data dokter.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="p-6 border-t border-slate-100">
            {{ $dokters->links() }}
        </div>
    </div>
@endsection
