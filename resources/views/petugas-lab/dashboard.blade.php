@extends('layouts.admin')

@section('title', 'Dashboard Petugas Lab')

@section('content')
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-slate-900">Dashboard Petugas Lab</h1>
        <p class="text-slate-500">Ringkasan input hasil pemeriksaan laboratorium</p>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-emerald-50 text-emerald-600 rounded-xl">🔬</div>
                <span class="text-xs font-bold text-slate-400">TOTAL</span>
            </div>
            <h3 class="text-3xl font-black text-slate-900">{{ $stats['total_inputted'] }}</h3>
            <p class="text-sm text-slate-500 mt-1">Total Input Hasil</p>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-amber-50 text-amber-600 rounded-xl">⏳</div>
                <span class="text-xs font-bold text-slate-400">PENDING</span>
            </div>
            <h3 class="text-3xl font-black text-amber-600">{{ $stats['pending_validation'] }}</h3>
            <p class="text-sm text-slate-500 mt-1">Menunggu Validasi</p>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-blue-50 text-blue-600 rounded-xl">✅</div>
                <span class="text-xs font-bold text-slate-400">VALIDATED</span>
            </div>
            <h3 class="text-3xl font-black text-emerald-600">{{ $stats['validated'] }}</h3>
            <p class="text-sm text-slate-500 mt-1">Sudah Divalidasi</p>
        </div>
    </div>

    <!-- Recent Results -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex items-center justify-between">
            <h3 class="font-bold text-slate-900 text-lg">Input Terbaru</h3>
            <a href="{{ route('petugas-lab.lab-results.index') }}"
                class="text-sm text-emerald-600 font-semibold hover:underline">Lihat Semua</a>
        </div>
        <div class="divide-y divide-slate-100">
            @forelse($recentResults as $result)
                <a href="{{ route('petugas-lab.lab-results.show', $result->id) }}"
                    class="block p-6 hover:bg-slate-50 transition-colors">
                    <div class="flex items-center">
                        <div
                            class="h-10 w-10 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-600 mr-4 font-bold flex-shrink-0">
                            🧪
                        </div>
                        <div class="flex-grow">
                            <p class="font-bold text-slate-900">{{ $result->test_name }}</p>
                            <p class="text-sm text-slate-500">{{ $result->patient->user->name ?? '-' }}</p>
                        </div>
                        <div class="text-right flex-shrink-0">
                            <span
                                class="inline-block px-3 py-1 rounded-full text-xs font-bold {{ $result->status === 'pending' ? 'bg-amber-100 text-amber-700' : 'bg-emerald-100 text-emerald-700' }}">
                                {{ strtoupper($result->status) }}
                            </span>
                            <p class="text-xs text-slate-400 mt-1">{{ $result->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                </a>
            @empty
                <div class="p-12 text-center text-slate-400 italic">
                    Belum ada input hasil pemeriksaan.
                </div>
            @endforelse
        </div>
    </div>
@endsection
