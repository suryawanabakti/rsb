@extends('layouts.pasien')

@section('title', 'Hasil Pemeriksaan Lab')

@section('content')
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-slate-900">Hasil Pemeriksaan Lab</h1>
        <p class="text-slate-500">Daftar semua hasil pemeriksaan laboratorium Anda</p>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 text-slate-400 text-xs font-black uppercase tracking-wider">
                        <th class="px-6 py-4">Nama Pemeriksaan</th>
                        <th class="px-6 py-4">Tanggal Pemeriksaan</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($results as $result)
                        <tr class="hover:bg-slate-50/50 transition-colors group">
                            <td class="px-6 py-4">
                                <p class="font-bold text-slate-900">{{ $result->test_name }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm text-slate-600">{{ $result->test_date->isoFormat('D MMMM Y') }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <span
                                    class="inline-block px-3 py-1 rounded-full text-[10px] font-black tracking-wide
                                    @if ($result->status == 'pending') bg-amber-100 text-amber-700 
                                    @elseif($result->status == 'validated') bg-emerald-100 text-emerald-700
                                    @else bg-slate-100 text-slate-700 @endif">
                                    {{ strtoupper($result->status == 'validated' ? 'Selesai' : $result->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('pasien.lab-results.show', $result->id) }}"
                                    class="text-blue-600 font-bold text-xs hover:underline">Lihat Detail</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-slate-400 italic">Belum ada hasil
                                pemeriksaan lab.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-6 border-t border-slate-100">
            {{ $results->links() }}
        </div>
    </div>
@endsection
