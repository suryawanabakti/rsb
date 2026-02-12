@extends('layouts.admin')

@section('title', 'Detail Hasil Pemeriksaan')

@section('content')
    <div class="mb-8">
        <a href="{{ route('petugas-lab.lab-results.index') }}"
            class="text-emerald-600 font-semibold hover:underline text-sm">← Kembali ke Daftar</a>
        <h1 class="text-2xl font-bold text-slate-900 mt-2">{{ $result->test_name }}</h1>
        <div class="flex items-center mt-2 space-x-3">
            <span
                class="inline-block px-3 py-1 rounded-full text-xs font-bold {{ $result->status === 'pending' ? 'bg-amber-100 text-amber-700' : 'bg-emerald-100 text-emerald-700' }}">
                {{ $result->status === 'pending' ? '⏳ Menunggu Validasi' : '✅ Tervalidasi' }}
            </span>

            @if ($result->status === 'pending')
                <a href="{{ route('petugas-lab.lab-results.edit', $result->id) }}"
                    class="inline-flex items-center px-3 py-1 bg-blue-50 text-blue-600 rounded-lg text-xs font-bold hover:bg-blue-100 transition-colors">
                    ✏️ Edit
                </a>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Result Data Table -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="p-6 border-b border-slate-100">
                    <h3 class="font-bold text-slate-900 text-lg">Hasil Pemeriksaan</h3>
                </div>
                <table class="w-full">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100">
                            <th class="text-left px-6 py-3 text-xs font-bold text-slate-500 uppercase">Parameter</th>
                            <th class="text-center px-6 py-3 text-xs font-bold text-slate-500 uppercase">Nilai</th>
                            <th class="text-center px-6 py-3 text-xs font-bold text-slate-500 uppercase">Satuan</th>
                            <th class="text-right px-6 py-3 text-xs font-bold text-slate-500 uppercase">Nilai Normal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($result->result_data ?? [] as $item)
                            <tr class="hover:bg-slate-50">
                                <td class="px-6 py-3 font-medium text-slate-900">{{ $item['name'] ?? '-' }}</td>
                                <td class="px-6 py-3 text-center font-bold text-slate-900">{{ $item['value'] ?? '-' }}</td>
                                <td class="px-6 py-3 text-center text-slate-500 text-sm">{{ $item['unit'] ?? '-' }}</td>
                                <td class="px-6 py-3 text-right text-slate-500 text-sm">{{ $item['normal_range'] ?? '-' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if ($result->notes)
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                    <h3 class="font-bold text-slate-900 text-lg mb-2">Catatan</h3>
                    <p class="text-slate-700">{{ $result->notes }}</p>
                </div>
            @endif
        </div>

        <!-- Sidebar Info -->
        <div class="space-y-6">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                <h3 class="font-bold text-slate-900 mb-4">Informasi Pasien</h3>
                <div class="space-y-3">
                    <div>
                        <p class="text-xs text-slate-400 uppercase font-bold">Nama</p>
                        <p class="text-slate-900 font-bold">{{ $result->patient->user->name ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400 uppercase font-bold">NIK</p>
                        <p class="text-slate-700">{{ $result->patient->nik ?? '-' }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                <h3 class="font-bold text-slate-900 mb-4">Detail Pemeriksaan</h3>
                <div class="space-y-3">
                    <div>
                        <p class="text-xs text-slate-400 uppercase font-bold">Tanggal</p>
                        <p class="text-slate-700">{{ \Carbon\Carbon::parse($result->test_date)->format('d F Y') }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400 uppercase font-bold">Diinput oleh</p>
                        <p class="text-slate-700">{{ $result->inputter->name ?? '-' }}</p>
                    </div>
                    @if ($result->status === 'validated')
                        <div>
                            <p class="text-xs text-slate-400 uppercase font-bold">Divalidasi oleh</p>
                            <p class="text-emerald-700 font-bold">{{ $result->validator->name ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-400 uppercase font-bold">Tanggal Validasi</p>
                            <p class="text-slate-700">
                                {{ $result->validated_at ? \Carbon\Carbon::parse($result->validated_at)->format('d F Y H:i') : '-' }}
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
