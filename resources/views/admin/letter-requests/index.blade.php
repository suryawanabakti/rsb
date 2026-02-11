@extends('layouts.admin')

@section('title', 'Daftar Permohonan')

@section('content')
    <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Permohonan Surat</h1>
            <p class="text-slate-500">Kelola pengajuan surat dari pasien</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <!-- Filter Bar -->
        <div class="p-6 border-b border-slate-100 bg-slate-50/50">
            <form action="{{ route('admin.letter-requests.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
                <div class="flex-grow">
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Cari Nama, Username, atau NIK..."
                        class="w-full px-4 py-2 rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all outline-none text-sm">
                </div>
                <div class="w-full md:w-48">
                    <select name="status" onchange="this.form.submit()"
                        class="w-full px-4 py-2 rounded-xl border border-slate-200 focus:border-blue-500 transition-all outline-none text-sm">
                        <option value="">Semua Status</option>
                        <option value="submitted" {{ request('status') == 'submitted' ? 'selected' : '' }}>SUBMITTED
                        </option>
                        <option value="verified" {{ request('status') == 'verified' ? 'selected' : '' }}>VERIFIED</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>APPROVED</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>REJECTED</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>COMPLETED
                        </option>
                    </select>
                </div>
                <button type="submit"
                    class="bg-blue-600 text-white px-6 py-2 rounded-xl font-bold text-sm hover:bg-blue-700 transition-colors">
                    Filter
                </button>
                @if (request()->anyFilled(['search', 'status']))
                    <a href="{{ route('admin.letter-requests.index') }}"
                        class="px-6 py-2 text-slate-500 font-bold text-sm flex items-center justify-center hover:text-slate-700">
                        Reset
                    </a>
                @endif
            </form>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 text-slate-400 text-xs font-black uppercase tracking-wider">
                        <th class="px-6 py-4">Pasien</th>
                        <th class="px-6 py-4">Jenis Surat</th>
                        <th class="px-6 py-4">Tanggal Diajukan</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($requests as $request)
                        <tr class="hover:bg-slate-50/50 transition-colors group">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div
                                        class="h-10 w-10 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center font-bold mr-3 border border-blue-100">
                                        {{ substr($request->patient->user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="font-bold text-slate-900 group-hover:text-blue-600 transition-colors">
                                            {{ $request->patient->user->name }}</p>
                                        <p class="text-xs text-slate-400">NIK: {{ $request->patient->nik }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm font-semibold text-slate-700">{{ $request->letterType->name }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm text-slate-600">{{ $request->created_at->format('d M Y') }}</p>
                                <p class="text-xs text-slate-400">{{ $request->created_at->format('H:i') }} WIB</p>
                            </td>
                            <td class="px-6 py-4">
                                <span
                                    class="inline-block px-3 py-1 rounded-full text-[10px] font-black tracking-widest
                            @if ($request->status == 'submitted') bg-amber-100 text-amber-700 
                            @elseif($request->status == 'verified') bg-blue-100 text-blue-700
                            @elseif($request->status == 'approved') bg-emerald-100 text-emerald-700
                            @elseif($request->status == 'rejected') bg-red-100 text-red-700
                            @else bg-slate-100 text-slate-700 @endif">
                                    {{ strtoupper($request->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('admin.letter-requests.show', $request->id) }}"
                                    class="inline-flex items-center justify-center p-2 rounded-xl bg-slate-100 text-slate-600 hover:bg-blue-600 hover:text-white transition-all shadow-sm">
                                    👁️
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-slate-400 italic">
                                Tidak ada data permohonan yang ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="p-6 border-t border-slate-100">
            {{ $requests->links() }}
        </div>
    </div>
@endsection
