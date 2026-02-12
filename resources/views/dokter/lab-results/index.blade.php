@extends('layouts.admin')

@section('title', 'Validasi Hasil Lab')

@section('content')
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-slate-900">Validasi Hasil Pemeriksaan Lab</h1>
        <p class="text-slate-500">Daftar semua hasil pemeriksaan laboratorium</p>
    </div>

    <!-- Filter Tabs -->
    <div class="flex space-x-2 mb-6">
        <a href="{{ route('dokter.lab-results.index') }}"
            class="px-5 py-2 rounded-xl text-sm font-bold transition-colors {{ $currentFilter === 'all' ? 'bg-purple-600 text-white shadow-sm' : 'bg-white text-slate-600 hover:bg-slate-50 border border-slate-200' }}">
            Semua
        </a>
        <a href="{{ route('dokter.lab-results.index', ['status' => 'pending']) }}"
            class="px-5 py-2 rounded-xl text-sm font-bold transition-colors {{ $currentFilter === 'pending' ? 'bg-amber-500 text-white shadow-sm' : 'bg-white text-slate-600 hover:bg-slate-50 border border-slate-200' }}">
            ⏳ Menunggu
        </a>
        <a href="{{ route('dokter.lab-results.index', ['status' => 'validated']) }}"
            class="px-5 py-2 rounded-xl text-sm font-bold transition-colors {{ $currentFilter === 'validated' ? 'bg-emerald-600 text-white shadow-sm' : 'bg-white text-slate-600 hover:bg-slate-50 border border-slate-200' }}">
            ✅ Tervalidasi
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <table class="w-full">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-100">
                    <th class="text-left px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">No</th>
                    <th class="text-left px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Nama
                        Pemeriksaan</th>
                    <th class="text-left px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Pasien</th>
                    <th class="text-left px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Input oleh
                    </th>
                    <th class="text-left px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Tanggal</th>
                    <th class="text-left px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Status</th>
                    <th class="text-left px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($results as $i => $result)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4 text-sm text-slate-500">{{ $results->firstItem() + $i }}</td>
                        <td class="px-6 py-4">
                            <p class="font-bold text-slate-900">{{ $result->test_name }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-slate-700">{{ $result->patient->user->name ?? '-' }}</p>
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-500">{{ $result->inputter->name ?? '-' }}</td>
                        <td class="px-6 py-4 text-sm text-slate-500">
                            {{ \Carbon\Carbon::parse($result->test_date)->format('d M Y') }}
                        </td>
                        <td class="px-6 py-4">
                            <span
                                class="inline-block px-3 py-1 rounded-full text-xs font-bold {{ $result->status === 'pending' ? 'bg-amber-100 text-amber-700' : 'bg-emerald-100 text-emerald-700' }}">
                                {{ strtoupper($result->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <a href="{{ route('dokter.lab-results.show', $result->id) }}"
                                class="text-purple-600 font-semibold hover:underline text-sm">Detail</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-slate-400 italic">
                            Tidak ada data hasil pemeriksaan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if ($results->hasPages())
            <div class="px-6 py-4 border-t border-slate-100">
                {{ $results->withQueryString()->links() }}
            </div>
        @endif
    </div>
@endsection
