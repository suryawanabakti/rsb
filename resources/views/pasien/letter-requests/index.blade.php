@extends('layouts.pasien')

@section('title', 'Riwayat Permohonan')

@section('content')
    <div class="mb-8 flex justify-between items-end">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Riwayat Permohonan</h1>
            <p class="text-slate-500">Daftar semua permohonan surat yang pernah Anda ajukan</p>
        </div>
        <a href="{{ route('pasien.letter-requests.create') }}"
            class="bg-blue-600 text-white px-6 py-2 rounded-xl font-bold text-sm hover:bg-blue-700 transition-colors flex items-center shadow-lg shadow-blue-200">
            <span class="mr-2">✉️</span> Buat Baru
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 text-slate-400 text-xs font-black uppercase tracking-wider">
                        <th class="px-6 py-4">Jenis Surat</th>
                        <th class="px-6 py-4">Tanggal Pengajuan</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($requests as $request)
                        <tr class="hover:bg-slate-50/50 transition-colors group">
                            <td class="px-6 py-4">
                                <p class="font-bold text-slate-900">{{ $request->letterType->name }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm text-slate-600">{{ $request->created_at->isoFormat('D MMMM Y') }}</p>
                                <p class="text-xs text-slate-400">{{ $request->created_at->format('H:i') }} WITA</p>
                            </td>
                            <td class="px-6 py-4">
                                <span
                                    class="inline-block px-3 py-1 rounded-full text-[10px] font-black tracking-wide
                                    @if ($request->status == 'submitted' || $request->status == 'pending') bg-amber-100 text-amber-700 
                                    @elseif($request->status == 'approved' || $request->status == 'completed') bg-emerald-100 text-emerald-700
                                    @elseif($request->status == 'rejected') bg-red-100 text-red-700
                                    @else bg-blue-100 text-blue-700 @endif">
                                    {{ strtoupper($request->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('pasien.letter-requests.show', $request->id) }}"
                                    class="text-blue-600 font-bold text-xs hover:underline">Detail</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-slate-400 italic">Belum ada riwayat
                                permohonan.
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
