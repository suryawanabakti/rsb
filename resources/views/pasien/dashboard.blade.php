@extends('layouts.pasien')

@section('title', 'Dashboard')

@section('content')
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-slate-900">Halo, {{ Auth::user()->name }} 👋</h1>
        <p class="text-slate-500">Selamat datang di panel pasien RS. Bhayangkara Makassar</p>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-blue-50 text-blue-600 rounded-xl">✉️</div>
                <span class="text-xs font-bold text-slate-400">TOTAL</span>
            </div>
            <h3 class="text-3xl font-black text-slate-900">{{ $stats['total'] }}</h3>
            <p class="text-sm text-slate-500 mt-1">Permohonan Surat</p>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-amber-50 text-amber-600 rounded-xl">⏳</div>
                <span class="text-xs font-bold text-slate-400">PENDING</span>
            </div>
            <h3 class="text-3xl font-black text-slate-900">{{ $stats['pending'] }}</h3>
            <p class="text-sm text-slate-500 mt-1">Sedang Diproses</p>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-emerald-50 text-emerald-600 rounded-xl">✅</div>
                <span class="text-xs font-bold text-slate-400">SELESAI</span>
            </div>
            <h3 class="text-3xl font-black text-slate-900">{{ $stats['processed'] + $stats['completed'] }}</h3>
            <p class="text-sm text-slate-500 mt-1">Surat Selesai</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Recent Requests -->
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                <h3 class="font-bold text-slate-900 text-lg">Permohonan Terbaru</h3>
                <a href="{{ route('pasien.letter-requests.index') }}"
                    class="text-sm text-blue-600 font-semibold hover:underline">Lihat Semua</a>
            </div>
            <div class="divide-y divide-slate-100">
                @forelse($recentRequests as $request)
                    <div class="p-6 hover:bg-slate-50 transition-colors flex items-center justify-between">
                        <div>
                            <p class="font-bold text-slate-900">{{ $request->letterType->name }}</p>
                            <p class="text-xs text-slate-400 mt-1">{{ $request->created_at->diffForHumans() }}</p>
                        </div>
                        <div>
                            <span
                                class="inline-block px-3 py-1 rounded-full text-xs font-bold
                            @if ($request->status == 'submitted' || $request->status == 'pending') bg-amber-100 text-amber-700 
                            @elseif($request->status == 'approved' || $request->status == 'completed') bg-emerald-100 text-emerald-700
                            @elseif($request->status == 'rejected') bg-red-100 text-red-700
                            @else bg-blue-100 text-blue-700 @endif">
                                {{ strtoupper($request->status) }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="p-12 text-center text-slate-400 italic">
                        Belum ada permohonan surat.
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="space-y-6">
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
                <h3 class="font-bold text-slate-900 text-lg mb-4">Aksi Cepat</h3>
                <div class="space-y-2">
                    <a href="{{ route('pasien.letter-requests.create') }}"
                        class="block w-full text-center py-3 px-4 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl transition-all shadow-lg shadow-blue-200">
                        ✉️ Buat Permohonan Baru
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
