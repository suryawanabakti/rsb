@extends('layouts.admin')

@section('title', 'Dashboard Dokter')

@section('content')
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-slate-900">Dashboard Dokter</h1>
        <p class="text-slate-500">Ringkasan validasi hasil pemeriksaan & jadwal praktik</p>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
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
                <div class="p-3 bg-emerald-50 text-emerald-600 rounded-xl">✅</div>
                <span class="text-xs font-bold text-slate-400">VALIDATED</span>
            </div>
            <h3 class="text-3xl font-black text-emerald-600">{{ $stats['validated_by_me'] }}</h3>
            <p class="text-sm text-slate-500 mt-1">Divalidasi oleh Anda</p>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-purple-50 text-purple-600 rounded-xl">🧪</div>
                <span class="text-xs font-bold text-slate-400">TOTAL</span>
            </div>
            <h3 class="text-3xl font-black text-slate-900">{{ $stats['total_results'] }}</h3>
            <p class="text-sm text-slate-500 mt-1">Total Hasil Lab</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Pending Results -->
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                <h3 class="font-bold text-slate-900 text-lg">Menunggu Validasi</h3>
                <a href="{{ route('dokter.lab-results.index', ['status' => 'pending']) }}"
                    class="text-sm text-purple-600 font-semibold hover:underline">Lihat Semua</a>
            </div>
            <div class="divide-y divide-slate-100">
                @forelse($pendingResults as $result)
                    <a href="{{ route('dokter.lab-results.show', $result->id) }}"
                        class="block p-6 hover:bg-slate-50 transition-colors">
                        <div class="flex items-center">
                            <div
                                class="h-10 w-10 rounded-full bg-amber-100 flex items-center justify-center text-amber-600 mr-4 font-bold flex-shrink-0">
                                🧪
                            </div>
                            <div class="flex-grow">
                                <p class="font-bold text-slate-900">{{ $result->test_name }}</p>
                                <p class="text-sm text-slate-500">
                                    {{ $result->patient->user->name ?? '-' }} • Input oleh:
                                    {{ $result->inputter->name ?? '-' }}
                                </p>
                            </div>
                            <div class="text-right flex-shrink-0">
                                <span
                                    class="inline-block px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-700">
                                    PENDING
                                </span>
                                <p class="text-xs text-slate-400 mt-1">{{ $result->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="p-12 text-center text-slate-400 italic">
                        🎉 Tidak ada hasil menunggu validasi.
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Today's Schedule -->
        <div class="space-y-6">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                <h3 class="font-bold text-slate-900 text-lg mb-4">📅 Jadwal Hari Ini</h3>
                @forelse($todaySchedules as $schedule)
                    <div class="flex items-center bg-purple-50 p-4 rounded-xl border border-purple-200 mb-3">
                        <div
                            class="h-10 w-10 rounded-xl bg-purple-100 flex items-center justify-center text-purple-600 mr-4 font-bold flex-shrink-0">
                            🕐
                        </div>
                        <div>
                            <p class="font-bold text-purple-900">
                                {{ substr($schedule->start_time, 0, 5) }} - {{ substr($schedule->end_time, 0, 5) }}
                            </p>
                            <p class="text-sm text-purple-600">{{ $schedule->room ?: 'Ruangan belum ditentukan' }}</p>
                        </div>
                    </div>
                @empty
                    <p class="text-slate-400 italic text-sm">Tidak ada jadwal hari ini.</p>
                @endforelse

                <a href="{{ route('dokter.schedules.index') }}"
                    class="block text-center mt-4 text-purple-600 font-semibold hover:underline text-sm">
                    Lihat Jadwal Lengkap →
                </a>
            </div>
        </div>
    </div>
@endsection
