@extends('layouts.admin')

@section('title', 'Jadwal Dokter')

@section('content')
    <div class="mb-8 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Jadwal Praktik Dokter</h1>
            <p class="text-slate-500">Kelola jadwal kehadiran dokter setiap hari</p>
        </div>
        <a href="{{ route('admin.doctor-schedules.create') }}"
            class="bg-blue-600 text-white px-6 py-2.5 rounded-xl font-bold text-sm hover:bg-blue-700 transition-all shadow-lg shadow-blue-200 flex items-center">
            <span class="mr-2 text-lg">+</span> Tambah Jadwal
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 text-slate-400 text-xs font-black uppercase tracking-wider">
                        <th class="px-6 py-4">Dokter</th>
                        <th class="px-6 py-4">Hari</th>
                        <th class="px-6 py-4">Jam Praktik</th>
                        <th class="px-6 py-4">Ruangan</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($schedules as $schedule)
                        <tr class="hover:bg-slate-50/50 transition-colors group">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div
                                        class="h-8 w-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold mr-3 border border-blue-200 text-xs">
                                        {{ substr($schedule->doctor->name, 0, 1) }}
                                    </div>
                                    <p class="font-bold text-slate-900 text-sm">{{ $schedule->doctor->name }}</p>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span
                                    class="px-3 py-1 bg-slate-100 text-slate-700 rounded-lg text-xs font-bold uppercase tracking-wider">
                                    {{ $schedule->day_of_week }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm font-bold text-slate-700">
                                    {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} -
                                    {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}
                                </p>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-600 font-medium">
                                {{ $schedule->room ?? '-' }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if ($schedule->is_active)
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">
                                        Aktif
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        Nonaktif
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('admin.doctor-schedules.edit', $schedule->id) }}"
                                    class="text-blue-600 font-bold text-xs hover:underline mr-4">Edit</a>
                                <form action="{{ route('admin.doctor-schedules.destroy', $schedule->id) }}" method="POST"
                                    onsubmit="return confirm('Hapus jadwal ini?');" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="text-red-600 font-bold text-xs hover:underline">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-slate-400 italic">
                                Belum ada jadwal yang diatur.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-6 border-t border-slate-100">
            {{ $schedules->links() }}
        </div>
    </div>
@endsection
