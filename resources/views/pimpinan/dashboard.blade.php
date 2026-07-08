@extends('layouts.admin')

@section('title', 'Dashboard Pimpinan')

@section('content')
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-slate-900">Dashboard Pimpinan</h1>
        <p class="text-slate-500">Ringkasan operasional RS. Bhayangkara Makassar</p>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-10">
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-blue-50 text-blue-600 rounded-xl">👥</div>
                <span class="text-xs font-bold text-slate-400">TOTAL</span>
            </div>
            <h3 class="text-3xl font-black text-blue-600">{{ $stats['total_patients'] }}</h3>
            <p class="text-sm text-slate-500 mt-1">Total Pasien</p>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-indigo-50 text-indigo-600 rounded-xl">✉️</div>
                <span class="text-xs font-bold text-slate-400">REQUESTS</span>
            </div>
            <h3 class="text-3xl font-black text-indigo-600">{{ $stats['total_letter_requests'] }}</h3>
            <p class="text-sm text-slate-500 mt-1">Total Permohonan</p>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-amber-50 text-amber-600 rounded-xl">⏳</div>
                <span class="text-xs font-bold text-slate-400">PENDING</span>
            </div>
            <h3 class="text-3xl font-black text-amber-600">{{ $stats['pending_letter_requests'] }}</h3>
            <p class="text-sm text-slate-500 mt-1">Menunggu Admin</p>
        </div>

       
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Recent Requests -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                <h3 class="font-bold text-slate-900 text-lg">Permohonan Terbaru</h3>
                <a href="{{ route('pimpinan.reports.index', ['type' => 'letter_requests']) }}"
                    class="text-sm text-indigo-600 font-semibold hover:underline">Lihat Laporan</a>
            </div>
            <div class="divide-y divide-slate-100">
                @forelse($recentRequests as $request)
                    <div class="p-6 hover:bg-slate-50 transition-colors">
                        <div class="flex items-center">
                            <div
                                class="h-10 w-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-600 mr-4 font-bold flex-shrink-0">
                                ✉️
                            </div>
                            <div class="flex-grow">
                                <p class="font-bold text-slate-900">{{ $request->letterType->name }}</p>
                                <p class="text-sm text-slate-500">{{ $request->patient->user->name }}</p>
                            </div>
                            <div class="text-right">
                                <span
                                    class="inline-block px-3 py-1 rounded-full text-[10px] font-black tracking-wide 
                                    @if ($request->status == 'pending') bg-amber-100 text-amber-700
                                    @elseif($request->status == 'processed') bg-blue-100 text-blue-700
                                    @elseif($request->status == 'completed') bg-emerald-100 text-emerald-700
                                    @else bg-red-100 text-red-700 @endif">
                                    {{ strtoupper($request->status) }}
                                </span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-10 text-center text-slate-400 italic">Belum ada data.</div>
                @endforelse
            </div>
        </div>


    </div>
@endsection
