@extends('layouts.admin')

@section('title', 'Detail Pasien')

@section('content')
    <div class="mb-8">
        <a href="{{ route('admin.patients.index') }}"
            class="text-blue-600 font-semibold text-sm hover:underline flex items-center mb-2">
            ← Kembali ke Daftar Pasien
        </a>
        <h1 class="text-2xl font-bold text-slate-900">Detail Pasien</h1>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Profile Info -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-8">
            <div class="flex flex-col items-center mb-8">
                <div
                    class="h-24 w-24 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center text-3xl font-bold border-4 border-white shadow-lg mb-4">
                    {{ substr($patient->user->name, 0, 1) }}
                </div>
                <h3 class="text-xl font-bold text-slate-900">{{ $patient->user->name }}</h3>
                <p class="text-sm text-slate-500">NIK: {{ $patient->nik }}</p>
            </div>

            <div class="space-y-4 pt-6 border-t border-slate-50">
                <div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Kontak</p>
                    <p class="font-bold text-slate-700">{{ $patient->user->phone ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Tempat/Tgl Lahir</p>
                    <p class="font-bold text-slate-700">{{ $patient->address }},
                        {{ \Carbon\Carbon::parse($patient->birth_date)->format('d/m/Y') }}</p>
                </div>
                <div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Jenis Kelamin</p>
                    <p class="font-bold text-slate-700">{{ $patient->gender == 'L' ? 'Laki-laki' : 'Perempuan' }}</p>
                </div>
            </div>
        </div>

        <!-- Request History -->
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="p-6 border-b border-slate-100">
                <h3 class="font-bold text-slate-900">Riwayat Permohonan</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-slate-50 text-[10px] font-black text-slate-400 uppercase tracking-widest">
                        <tr>
                            <th class="px-6 py-4">ID</th>
                            <th class="px-6 py-4">Jenis Surat</th>
                            <th class="px-6 py-4">Tanggal</th>
                            <th class="px-6 py-4">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm">
                        @forelse($patient->letterRequests as $req)
                            <tr>
                                <td class="px-6 py-4">#{{ $req->id }}</td>
                                <td class="px-6 py-4 font-bold">{{ $req->letterType->name }}</td>
                                <td class="px-6 py-4 text-slate-500">{{ $req->created_at->format('d/m/Y') }}</td>
                                <td class="px-6 py-4">
                                    <span
                                        class="px-2 py-1 rounded-full text-[10px] font-black
                                @if ($req->status == 'submitted') bg-amber-100 text-amber-700 
                                @elseif($req->status == 'approved') bg-emerald-100 text-emerald-700
                                @else bg-slate-100 text-slate-700 @endif">
                                        {{ strtoupper($req->status) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center text-slate-400 italic">Belum ada riwayat
                                    permohonan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
