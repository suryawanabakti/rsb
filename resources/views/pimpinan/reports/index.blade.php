@extends('layouts.admin')

@section('title', 'Laporan')

@section('content')
    <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Laporan Operasional</h1>
            <p class="text-slate-500">Filter dan pantau aktivitas sistem</p>
        </div>
        <div class="flex items-center space-x-2">
            <button onclick="window.print()"
                class="bg-white border border-slate-200 text-slate-600 px-4 py-2 rounded-xl font-bold text-sm hover:bg-slate-50 transition-colors">
                🖨️ Cetak
            </button>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 mb-8">
        <form action="{{ route('pimpinan.reports.index') }}" method="GET"
            class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
            <div>
                <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Jenis Laporan</label>
                <select name="type"
                    class="w-full bg-slate-50 border-none rounded-xl px-4 py-3 focus:ring-2 focus:ring-indigo-500 text-sm font-medium">
                    <option value="letter_requests" {{ $type === 'letter_requests' ? 'selected' : '' }}>Permohonan Surat
                    </option>
                    <option value="lab_results" {{ $type === 'lab_results' ? 'selected' : '' }}>Hasil Pemeriksaan Lab
                    </option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Dari Tanggal</label>
                <input type="date" name="start_date" value="{{ $startDate }}"
                    class="w-full bg-slate-50 border-none rounded-xl px-4 py-3 focus:ring-2 focus:ring-indigo-500 text-sm font-medium">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Sampai Tanggal</label>
                <input type="date" name="end_date" value="{{ $endDate }}"
                    class="w-full bg-slate-50 border-none rounded-xl px-4 py-3 focus:ring-2 focus:ring-indigo-500 text-sm font-medium">
            </div>
            <div>
                <button type="submit"
                    class="w-full bg-indigo-600 text-white font-bold py-3 rounded-xl hover:bg-indigo-700 transition-colors shadow-lg shadow-indigo-200">
                    Filter Laporan
                </button>
            </div>
        </form>
    </div>

    <!-- Data Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            @if ($type === 'letter_requests')
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 text-slate-400 text-[10px] font-black uppercase tracking-wider">
                            <th class="px-6 py-4">Pasien</th>
                            <th class="px-6 py-4">Jenis Surat</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4">Tanggal Permohonan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($data as $item)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-6 py-4">
                                    <p class="font-bold text-slate-900">{{ $item->patient->user->name }}</p>
                                    <p class="text-xs text-slate-400">NIK: {{ $item->patient->nik }}</p>
                                </td>
                                <td class="px-6 py-4 font-bold text-slate-600">
                                    {{ $item->letterType->name }}
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="inline-block px-3 py-1 rounded-full text-[10px] font-black tracking-wide 
                                        @if ($item->status == 'pending') bg-amber-100 text-amber-700
                                        @elseif($item->status == 'processed') bg-blue-100 text-blue-700
                                        @elseif($item->status == 'completed') bg-emerald-100 text-emerald-700
                                        @else bg-red-100 text-red-700 @endif">
                                        {{ strtoupper($item->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-500">
                                    {{ $item->created_at->isoFormat('D MMMM Y, HH:mm') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center text-slate-400 italic">Data tidak ditemukan
                                    untuk periode ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            @else
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 text-slate-400 text-[10px] font-black uppercase tracking-wider">
                            <th class="px-6 py-4">Pasien</th>
                            <th class="px-6 py-4">Pemeriksaan</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4">Validator</th>
                            <th class="px-6 py-4">Tanggal Periksa</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($data as $item)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-6 py-4">
                                    <p class="font-bold text-slate-900">{{ $item->patient->user->name }}</p>
                                    <p class="text-xs text-slate-400">NIK: {{ $item->patient->nik }}</p>
                                </td>
                                <td class="px-6 py-4 font-bold text-slate-600">
                                    {{ $item->test_name }}
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="inline-block px-3 py-1 rounded-full text-[10px] font-black tracking-wide 
                                        @if ($item->status == 'pending') bg-amber-100 text-amber-700
                                        @else bg-emerald-100 text-emerald-700 @endif">
                                        {{ strtoupper($item->status == 'validated' ? 'VALID' : $item->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm font-semibold text-slate-600">
                                    {{ $item->validator->name ?? '-' }}
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-500">
                                    {{ \Carbon\Carbon::parse($item->test_date)->isoFormat('D MMMM Y') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-slate-400 italic">Data tidak ditemukan
                                    untuk periode ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            @endif
        </div>
        <div class="px-6 py-4 bg-slate-50/50 border-t border-slate-100">
            {{ $data->links() }}
        </div>
    </div>
@endsection
