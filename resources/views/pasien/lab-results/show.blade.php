@extends('layouts.pasien')

@section('title', 'Detail Hasil Lab')

@section('content')
    <div class="mb-8">
        <a href="{{ route('pasien.lab-results.index') }}"
            class="text-slate-500 hover:text-slate-700 text-sm mb-4 inline-block">&larr; Kembali</a>
        <h1 class="text-2xl font-bold text-slate-900">Detail Hasil Lab</h1>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2">
            <!-- Test Info -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden mb-6">
                <div class="p-6 border-b border-slate-100 bg-slate-50/50">
                    <h3 class="font-bold text-slate-900 text-lg">Informasi Pemeriksaan</h3>
                </div>
                <div class="p-6 space-y-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <p class="text-xs text-slate-500 font-bold uppercase tracking-wider">Nama Pemeriksaan</p>
                            <p class="font-bold text-slate-900 mt-1 text-lg">{{ $labResult->test_name }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500 font-bold uppercase tracking-wider">Tanggal Pemeriksaan</p>
                            <p class="font-semibold text-slate-900 mt-1">{{ $labResult->test_date->isoFormat('D MMMM Y') }}
                            </p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500 font-bold uppercase tracking-wider">Status</p>
                            <span
                                class="inline-block mt-1 px-3 py-1 rounded-full text-xs font-bold
                                @if ($labResult->status == 'pending') bg-amber-100 text-amber-700 
                                @elseif($labResult->status == 'validated') bg-emerald-100 text-emerald-700
                                @else bg-slate-100 text-slate-700 @endif">
                                {{ strtoupper($labResult->status == 'validated' ? 'Selesai' : $labResult->status) }}
                            </span>
                        </div>
                        @if ($labResult->validator)
                            <div>
                                <p class="text-xs text-slate-500 font-bold uppercase tracking-wider">Divalidasi Oleh</p>
                                <p class="font-semibold text-slate-900 mt-1">dr. {{ $labResult->validator->name }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Results Table -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="p-6 border-b border-slate-100 bg-slate-50/50">
                    <h3 class="font-bold text-slate-900 text-lg">Hasil Laboratorium</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 text-slate-400 text-[10px] font-black uppercase tracking-wider">
                                <th class="px-6 py-4">Parameter</th>
                                <th class="px-6 py-4">Hasil</th>
                                <th class="px-6 py-4">Satuan</th>
                                <th class="px-6 py-4">Nilai Rujukan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach ($labResult->result_data as $item)
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="px-6 py-4">
                                        <p class="font-bold text-slate-700 text-sm">{{ $item['name'] }}</p>
                                    </td>
                                    <td class="px-6 py-4">
                                        <p class="font-black text-slate-900 text-sm">{{ $item['value'] }}</p>
                                    </td>
                                    <td class="px-6 py-4">
                                        <p class="text-slate-500 text-xs">{{ $item['unit'] ?? '-' }}</p>
                                    </td>
                                    <td class="px-6 py-4">
                                        <p class="text-slate-500 text-xs italic">{{ $item['normal_range'] ?? '-' }}</p>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden sticky top-24">
                <div class="p-6 border-b border-slate-100">
                    <h3 class="font-bold text-slate-900">Catatan Dokter</h3>
                </div>
                <div class="p-6">
                    <div class="bg-blue-50/50 p-4 rounded-xl border border-blue-100">
                        <p class="text-slate-600 text-sm leading-relaxed italic">
                            {{ $labResult->notes ?: 'Tidak ada catatan tambahan.' }}
                        </p>
                    </div>

                    <div class="mt-8 pt-8 border-t border-slate-100 text-center">
                        {{-- <button onclick="window.print()"
                            class="w-full bg-slate-900 text-white font-bold py-3 rounded-xl hover:bg-slate-800 transition-colors mb-4 flex items-center justify-center">
                            <span class="mr-2">🖨️</span> Cetak Hasil
                        </button> --}}
                        <p class="text-[10px] text-slate-400 leading-relaxed uppercase font-bold tracking-widest">Dokumen
                            ini diterbitkan secara elektronik oleh RS. Bhayangkara Makassar</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
