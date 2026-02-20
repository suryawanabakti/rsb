@extends('layouts.admin')

@section('title', 'Data Pasien')

@section('content')
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-slate-900">Data Pasien</h1>
        <p class="text-slate-500">Kelola informasi seluruh pasien terdaftar.</p>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <!-- Filter Bar -->
        <div class="p-6 border-b border-slate-100 bg-slate-50/50">
            <form action="{{ route('admin.patients.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
                <div class="flex-grow">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Nama atau NIK..."
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
                        <th class="px-6 py-4">NIK</th>
                        <th class="px-6 py-4">Gender</th>
                        <th class="px-6 py-4">No. HP</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($patients as $patient)
                        <tr class="hover:bg-slate-50/50 transition-colors group">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div
                                        class="h-10 w-10 rounded-full bg-slate-100 text-slate-500 flex items-center justify-center font-bold mr-3 border border-slate-200">
                                        {{ substr($patient->user->name, 0, 1) }}
                                    </div>
                                    <p class="font-bold text-slate-900">{{ $patient->user->name }}</p>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm font-semibold text-slate-700">{{ $patient->nik }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <span
                                    class="px-2 py-1 rounded-lg text-[10px] font-black tracking-widest {{ $patient->gender == 'L' ? 'bg-blue-100 text-blue-700' : 'bg-pink-100 text-pink-700' }}">
                                    {{ $patient->gender }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm text-slate-600">{{ $patient->user->phone ?? '-' }}</p>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('admin.patients.show', $patient->id) }}"
                                    class="text-blue-600 font-bold text-xs hover:underline">Detail</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-slate-400 italic">Tidak ada data pasien.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="p-6 border-t border-slate-100">
            {{ $patients->links() }}
        </div>
    </div>
@endsection
