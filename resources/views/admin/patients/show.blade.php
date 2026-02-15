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
        <!-- Profile Info & Basic Details -->
        <div class="space-y-8">
            <!-- Profile Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-8">
                <div class="flex flex-col items-center mb-6">
                    <div
                        class="h-24 w-24 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center text-3xl font-bold border-4 border-white shadow-lg mb-4">
                        {{ substr($patient->user->name, 0, 1) }}
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 text-center">{{ $patient->user->name }}</h3>
                    <p class="text-sm text-slate-500 italic mt-1">@ {{ $patient->user->username }}</p>
                </div>

                <div class="space-y-4 pt-6 border-t border-slate-50">
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">NIK</p>
                        <p class="font-bold text-slate-700">{{ $patient->nik }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">No. HP</p>
                        <p class="font-bold text-slate-700">{{ $patient->user->phone ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Tempat/Tgl Lahir</p>
                        <p class="font-bold text-slate-700">
                            {{ $patient->address ?? '-' }},
                            {{ $patient->birth_date ? \Carbon\Carbon::parse($patient->birth_date)->format('d/m/Y') : '-' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Jenis Kelamin</p>
                        <p class="font-bold text-slate-700">{{ $patient->gender == 'L' ? 'Laki-laki' : 'Perempuan' }}</p>
                    </div>
                </div>
            </div>

            <!-- Extra Info Form -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-8">
                <h3 class="font-bold text-slate-900 mb-6 flex items-center">
                    <span class="mr-2">📝</span> Informasi Tambahan
                </h3>
                <form action="{{ route('admin.patients.update-extra-info', $patient->id) }}" method="POST">
                    @csrf
                    @method('PATCH')

                    <div class="space-y-5">
                        <div>
                            <label
                                class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Pangkat/Golongan</label>
                            <input type="text" name="pangkat" value="{{ old('pangkat', $patient->pangkat) }}"
                                class="w-full px-4 py-2 rounded-xl border border-slate-200 focus:border-blue-500 outline-none text-sm transition-all focus:ring-4 focus:ring-blue-500/5">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">NRP /
                                NIP</label>
                            <input type="text" name="nrp_nip" value="{{ old('nrp_nip', $patient->nrp_nip) }}"
                                class="w-full px-4 py-2 rounded-xl border border-slate-200 focus:border-blue-500 outline-none text-sm transition-all focus:ring-4 focus:ring-blue-500/5">
                        </div>
                        <div>
                            <label
                                class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Pendidikan
                                Terakhir</label>
                            <input type="text" name="pendidikan_terakhir"
                                value="{{ old('pendidikan_terakhir', $patient->pendidikan_terakhir) }}"
                                class="w-full px-4 py-2 rounded-xl border border-slate-200 focus:border-blue-500 outline-none text-sm transition-all focus:ring-4 focus:ring-blue-500/5">
                        </div>
                        <div>
                            <label
                                class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Jabatan /
                                Kesatuan</label>
                            <input type="text" name="jabatan_kesatuan"
                                value="{{ old('jabatan_kesatuan', $patient->jabatan_kesatuan) }}"
                                class="w-full px-4 py-2 rounded-xl border border-slate-200 focus:border-blue-500 outline-none text-sm transition-all focus:ring-4 focus:ring-blue-500/5">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Alamat
                                Domisili</label>
                            <textarea name="address" required rows="3"
                                class="w-full px-4 py-2 rounded-xl border border-slate-200 focus:border-blue-500 outline-none text-sm transition-all focus:ring-4 focus:ring-blue-500/5">{{ old('address', $patient->address) }}</textarea>
                        </div>

                        <button type="submit"
                            class="w-full bg-slate-900 text-white font-bold py-3 rounded-xl hover:bg-black transition-all shadow-lg shadow-slate-200 mt-4 active:scale-95">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Request History -->
        <div class="lg:col-span-2 space-y-8">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                    <h3 class="font-bold text-slate-900">Riwayat Permohonan</h3>
                    <span class="px-3 py-1 bg-white border border-slate-200 rounded-lg text-xs font-bold text-slate-600">
                        {{ $patient->letterRequests->count() }} Total
                    </span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-slate-50 text-[10px] font-black text-slate-400 uppercase tracking-widest">
                            <tr>
                                <th class="px-6 py-4 border-b border-slate-100">ID</th>
                                <th class="px-6 py-4 border-b border-slate-100">Jenis Surat</th>
                                <th class="px-6 py-4 border-b border-slate-100">Tanggal</th>
                                <th class="px-6 py-4 border-b border-slate-100">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-sm">
                            @forelse($patient->letterRequests as $req)
                                <tr class="hover:bg-slate-50/30 transition-colors">
                                    <td class="px-6 py-4 font-mono text-xs text-slate-400">#{{ $req->id }}</td>
                                    <td class="px-6 py-4">
                                        <p class="font-bold text-slate-900">{{ $req->letterType->name }}</p>
                                    </td>
                                    <td class="px-6 py-4 text-slate-500">{{ $req->created_at->format('d/m/Y') }}</td>
                                    <td class="px-6 py-4">
                                        <span
                                            class="px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest
                                    @if ($req->status == 'submitted') bg-amber-100 text-amber-700 
                                    @elseif($req->status == 'approved') bg-emerald-100 text-emerald-700
                                    @elseif($req->status == 'rejected') bg-red-100 text-red-700
                                    @else bg-slate-100 text-slate-700 @endif">
                                            {{ $req->status }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-12 text-center text-slate-400 italic">
                                        Belum ada riwayat permohonan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
