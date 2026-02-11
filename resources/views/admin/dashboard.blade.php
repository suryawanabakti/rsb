@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-slate-900">Dashboard Statistik</h1>
        <p class="text-slate-500">Ringkasan operasional sistem saat ini</p>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-blue-50 text-blue-600 rounded-xl">👥</div>
                <span class="text-xs font-bold text-slate-400">TOTAL</span>
            </div>
            <h3 class="text-3xl font-black text-slate-900">{{ $stats['total_patients'] }}</h3>
            <p class="text-sm text-slate-500 mt-1">Pasien Terdaftar</p>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-amber-50 text-amber-600 rounded-xl">⏳</div>
                <span class="text-xs font-bold text-slate-400">PENDING</span>
            </div>
            <h3 class="text-3xl font-black text-slate-900">{{ $stats['pending_requests'] }}</h3>
            <p class="text-sm text-slate-500 mt-1">Permohonan Menunggu</p>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-emerald-50 text-emerald-600 rounded-xl">✨</div>
                <span class="text-xs font-bold text-slate-400">DIPROSES</span>
            </div>
            <h3 class="text-3xl font-black text-slate-900">{{ $stats['approved_requests'] }}</h3>
            <p class="text-sm text-slate-500 mt-1">Disetujui Admin</p>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-indigo-50 text-indigo-600 rounded-xl">✅</div>
                <span class="text-xs font-bold text-slate-400">SELESAI</span>
            </div>
            <h3 class="text-3xl font-black text-slate-900">{{ $stats['completed_requests'] }}</h3>
            <p class="text-sm text-slate-500 mt-1">Iterasi Selesai</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Recent Requests -->
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                <h3 class="font-bold text-slate-900 text-lg">Permohonan Terbaru</h3>
                <a href="{{ route('admin.letter-requests.index') }}"
                    class="text-sm text-blue-600 font-semibold hover:underline">Lihat Semua</a>
            </div>
            <div class="divide-y divide-slate-100">
                @forelse($recentRequests as $request)
                    <div class="p-6 hover:bg-slate-50 transition-colors flex items-center">
                        <div
                            class="h-10 w-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 mr-4 font-bold flex-shrink-0">
                            {{ substr($request->patient->user->name, 0, 1) }}
                        </div>
                        <div class="flex-grow">
                            <p class="font-bold text-slate-900">{{ $request->patient->user->name }}</p>
                            <p class="text-sm text-slate-500">{{ $request->letterType->name }}</p>
                        </div>
                        <div class="text-right flex-shrink-0">
                            <span
                                class="inline-block px-3 py-1 rounded-full text-xs font-bold
                        @if ($request->status == 'submitted') bg-amber-100 text-amber-700 
                        @elseif($request->status == 'approved') bg-emerald-100 text-emerald-700
                        @elseif($request->status == 'rejected') bg-red-100 text-red-700
                        @else bg-blue-100 text-blue-700 @endif">
                                {{ strtoupper($request->status) }}
                            </span>
                            <p class="text-xs text-slate-400 mt-1">{{ $request->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                @empty
                    <div class="p-12 text-center text-slate-400 italic">
                        Belum ada permohonan masuk.
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="space-y-6">
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
                <h3 class="font-bold text-slate-900 text-lg mb-4">Aksi Cepat</h3>
                <div class="space-y-2">
                    <a href="{{ route('admin.letter-types.index') }}"
                        class="block w-full text-center py-3 px-4 bg-slate-50 hover:bg-slate-100 text-slate-700 font-bold rounded-xl transition-all">
                        ➕ Tambah Jenis Surat
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
