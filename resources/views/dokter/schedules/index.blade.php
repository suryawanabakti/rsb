@extends('layouts.admin')

@section('title', 'Jadwal Praktik')

@section('content')
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-slate-900">📅 Jadwal Praktik</h1>
        <p class="text-slate-500">Jadwal praktik mingguan Anda</p>
    </div>

    @php
        $dayLabels = [
            'senin' => 'Senin',
            'selasa' => 'Selasa',
            'rabu' => 'Rabu',
            'kamis' => 'Kamis',
            'jumat' => 'Jumat',
            'sabtu' => 'Sabtu',
            'minggu' => 'Minggu',
        ];
        $dayOrder = ['senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu', 'minggu'];
    @endphp

    @if ($schedules->isEmpty())
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-12 text-center">
            <p class="text-5xl mb-4">📋</p>
            <p class="text-lg font-bold text-slate-400">Belum Ada Jadwal</p>
            <p class="text-sm text-slate-400 mt-1">Jadwal akan diatur oleh admin</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($dayOrder as $day)
                @if ($schedules->has($day))
                    @php $isToday = $today === $day; @endphp
                    <div
                        class="bg-white rounded-2xl shadow-sm border {{ $isToday ? 'border-purple-300 ring-2 ring-purple-100' : 'border-slate-100' }} p-6">
                        <div class="flex items-center mb-4">
                            <div class="h-3 w-3 rounded-full mr-2 {{ $isToday ? 'bg-purple-600' : 'bg-slate-300' }}"></div>
                            <h3
                                class="text-sm font-black uppercase tracking-widest {{ $isToday ? 'text-purple-600' : 'text-slate-400' }}">
                                {{ $dayLabels[$day] }}
                                @if ($isToday)
                                    <span
                                        class="ml-2 px-2 py-0.5 bg-purple-100 text-purple-700 text-[10px] rounded-full normal-case font-bold">Hari
                                        Ini</span>
                                @endif
                            </h3>
                        </div>

                        @foreach ($schedules[$day] as $schedule)
                            <div
                                class="flex items-center {{ $isToday ? 'bg-purple-50 border-purple-200' : 'bg-slate-50 border-slate-100' }} border p-4 rounded-xl mb-2">
                                <div
                                    class="h-10 w-10 rounded-xl {{ $isToday ? 'bg-purple-100' : 'bg-slate-100' }} flex items-center justify-center mr-3">
                                    🕐
                                </div>
                                <div>
                                    <p class="font-bold {{ $isToday ? 'text-purple-900' : 'text-slate-900' }}">
                                        {{ substr($schedule->start_time, 0, 5) }} - {{ substr($schedule->end_time, 0, 5) }}
                                    </p>
                                    <p class="text-sm {{ $isToday ? 'text-purple-600' : 'text-slate-500' }}">
                                        {{ $schedule->room ?: 'Ruangan belum ditentukan' }}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            @endforeach
        </div>
    @endif
@endsection
