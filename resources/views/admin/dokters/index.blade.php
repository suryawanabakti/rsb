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
                        placeholder="Cari Nama, Username, atau No. HP..."
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
                        <th class="px-6 py-4">Nama Lengkap</th>
                        <th class="px-6 py-4">Username</th>
                        <th class="px-6 py-4">No. HP</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($dokters as $dokter)
                        <tr class="hover:bg-slate-50/50 transition-colors group">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div
                                        class="h-10 w-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold mr-3 border border-blue-200">
                                        {{ substr($dokter->name, 0, 1) }}
                                    </div>
                                    <p class="font-bold text-slate-900">{{ $dokter->name }}</p>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm font-semibold text-slate-700">{{ $dokter->username }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm text-slate-600">{{ $dokter->phone ?? '-' }}</p>
                            </td>
                            <td class="px-6 py-4 text-right">
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
                            <td colspan="4" class="px-6 py-12 text-center text-slate-400 italic">Tidak ada data dokter.
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
