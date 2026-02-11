@extends('layouts.admin')

@section('title', 'Manajemen Jenis Surat')

@section('content')
    <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Jenis Surat</h1>
            <p class="text-slate-500">Kelola daftar jenis surat yang tersedia untuk pasien</p>
        </div>
        <button @click="$dispatch('open-modal-add')"
            class="bg-blue-600 text-white px-6 py-3 rounded-xl font-bold shadow-lg shadow-blue-200 hover:bg-blue-700 transition-all flex items-center justify-center">
            <span class="mr-2">➕</span> Tambah Jenis Surat
        </button>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 text-slate-400 text-xs font-black uppercase tracking-wider">
                        <th class="px-6 py-4">Nama Jenis Surat</th>
                        <th class="px-6 py-4">Deskripsi</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($types as $type)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-4">
                                <p class="font-bold text-slate-900">{{ $type->name }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm text-slate-500 max-w-xs truncate" title="{{ $type->description }}">
                                    {{ $type->description }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <span
                                    class="inline-block px-3 py-1 rounded-full text-[10px] font-black tracking-widest {{ $type->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' }}">
                                    {{ $type->is_active ? 'AKTIF' : 'NON-AKTIF' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right space-x-2">
                                <button class="text-blue-600 font-bold text-xs hover:underline">Edit</button>
                                <form action="{{ route('admin.letter-types.destroy', $type->id) }}" method="POST"
                                    class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-500 font-bold text-xs hover:underline"
                                        onclick="return confirm('Hapus jenis surat ini?')">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-slate-400 italic">Belum ada jenis surat.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Add (Simplified for this demo, usually would use a separate component or Blade include) -->
    <div x-data="{ open: false }" @open-modal-add.window="open = true" x-show="open" x-cloak
        class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-sm transition-opacity">
        <div @click.away="open = false"
            class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden transform transition-all scale-100">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50">
                <h3 class="font-bold text-slate-900">Tambah Jenis Surat</h3>
                <button @click="open = false" class="text-slate-400 hover:text-slate-600">&times;</button>
            </div>
            <form action="{{ route('admin.letter-types.store') }}" method="POST" class="p-6 space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Surat</label>
                    <input type="text" name="name" required
                        class="w-full px-4 py-2 rounded-xl border border-slate-200 outline-none focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Deskripsi</label>
                    <textarea name="description" rows="3"
                        class="w-full px-4 py-2 rounded-xl border border-slate-200 outline-none focus:border-blue-500"></textarea>
                </div>
                <button type="submit"
                    class="w-full bg-blue-600 text-white font-bold py-3 rounded-xl shadow-lg hover:bg-blue-700 transition-all mt-4">
                    Simpan Jenis Surat
                </button>
            </form>
        </div>
    </div>
@endsection
