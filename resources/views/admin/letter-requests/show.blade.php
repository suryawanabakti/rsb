@extends('layouts.admin')

@section('title', 'Detail Permohonan')

@section('content')
    <div class="mb-8 flex items-center justify-between">
        <div>
            <a href="{{ route('admin.letter-requests.index') }}"
                class="text-blue-600 font-semibold text-sm hover:underline flex items-center mb-2">
                ← Kembali ke Daftar
            </a>
            <h1 class="text-2xl font-bold text-slate-900">Detail Permohonan #{{ $letterRequest->id }}</h1>
        </div>
        <span
            class="px-4 py-2 rounded-full text-xs font-black tracking-widest
        @if ($letterRequest->status == 'submitted') bg-amber-100 text-amber-700 
        @elseif($letterRequest->status == 'verified') bg-blue-100 text-blue-700
        @elseif($letterRequest->status == 'approved') bg-emerald-100 text-emerald-700
        @elseif($letterRequest->status == 'rejected') bg-red-100 text-red-700
        @else bg-slate-100 text-slate-700 @endif">
            {{ strtoupper($letterRequest->status) }}
        </span>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Info -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Patient Info Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-8">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="font-bold text-slate-900 text-lg flex items-center">
                        <span class="mr-2">👤</span> Informasi Pasien
                    </h3>
                    <button type="button" onclick="document.getElementById('edit-patient-form').classList.toggle('hidden')"
                        class="hidden text-xs font-bold text-blue-600 hover:text-blue-800 uppercase tracking-widest transition-colors">
                        Edit Info Format Surat
                    </button>
                </div>

                <div id="patient-display-info" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-1">Nama Lengkap</p>
                        <p class="font-bold text-slate-900 text-lg">{{ $letterRequest->patient->user->name }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-1">NIK</p>
                        <p class="font-bold text-slate-700">{{ $letterRequest->patient->nik }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-1">Pangkat</p>
                        <p class="font-bold text-slate-700">{{ $letterRequest->patient->pangkat ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-1">NRP / NIP</p>
                        <p class="font-bold text-slate-700">{{ $letterRequest->patient->nrp_nip ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-1">Pendidikan Terakhir</p>
                        <p class="font-bold text-slate-700">{{ $letterRequest->patient->pendidikan_terakhir ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-1">Jabatan / Kesatuan</p>
                        <p class="font-bold text-slate-700">{{ $letterRequest->patient->jabatan_kesatuan ?? '-' }}</p>
                    </div>
                    <div class="md:col-span-2">
                        <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-1">Alamat (Sesuai KTP)</p>
                        <p class="font-bold text-slate-700">{{ $letterRequest->patient->address }}</p>
                    </div>
                </div>

                <!-- Hidden Edit Form -->
                <form id="edit-patient-form"
                    action="{{ route('admin.patients.update-extra-info', $letterRequest->patient->id) }}" method="POST"
                    class="hidden mt-6 pt-6 border-t border-slate-100">
                    @csrf
                    @method('PATCH')
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label
                                class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Pangkat</label>
                            <input type="text" name="pangkat" value="{{ $letterRequest->patient->pangkat }}"
                                class="w-full px-4 py-2 rounded-lg border border-slate-200 focus:border-blue-500 outline-none text-sm">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">NRP /
                                NIP</label>
                            <input type="text" name="nrp_nip" value="{{ $letterRequest->patient->nrp_nip }}"
                                class="w-full px-4 py-2 rounded-lg border border-slate-200 focus:border-blue-500 outline-none text-sm">
                        </div>
                        <div>
                            <label
                                class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Pendidikan
                                Terakhir</label>
                            <input type="text" name="pendidikan_terakhir"
                                value="{{ $letterRequest->patient->pendidikan_terakhir }}"
                                class="w-full px-4 py-2 rounded-lg border border-slate-200 focus:border-blue-500 outline-none text-sm">
                        </div>
                        <div>
                            <label
                                class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Jabatan /
                                Kesatuan</label>
                            <input type="text" name="jabatan_kesatuan"
                                value="{{ $letterRequest->patient->jabatan_kesatuan }}"
                                class="w-full px-4 py-2 rounded-lg border border-slate-200 focus:border-blue-500 outline-none text-sm">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Alamat
                                Lengkap</label>
                            <textarea name="address" rows="2"
                                class="w-full px-4 py-2 rounded-lg border border-slate-200 focus:border-blue-500 outline-none text-sm">{{ $letterRequest->patient->address }}</textarea>
                        </div>
                    </div>
                    <div class="flex justify-end space-x-2">
                        <button type="button"
                            onclick="document.getElementById('edit-patient-form').classList.add('hidden')"
                            class="px-4 py-2 text-sm font-bold text-slate-500 hover:text-slate-700 transition-colors">Batal</button>
                        <button type="submit"
                            class="bg-blue-600 text-white px-6 py-2 rounded-lg text-sm font-bold shadow-md hover:bg-blue-700 transition-all">Simpan
                            Perubahan</button>
                    </div>
                </form>
            </div>

            <!-- Attached Files -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-8">
                <h3 class="font-bold text-slate-900 text-lg mb-6 flex items-center">
                    <span class="mr-2">📎</span> Berkas Lampiran
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @forelse($letterRequest->files as $file)
                        <div class="flex items-center p-4 border rounded-xl hover:bg-slate-50 transition-colors">
                            <div
                                class="h-12 w-12 bg-blue-50 text-blue-600 rounded-lg flex items-center justify-center text-xl mr-4 flex-shrink-0">
                                📄
                            </div>
                            <div class="flex-grow min-w-0">
                                <p class="font-bold text-slate-900 truncate text-sm" title="{{ $file->file_name }}">
                                    {{ $file->file_name }}</p>
                                <p class="text-xs text-slate-400 uppercase">{{ round($file->file_size / 1024, 2) }} KB</p>
                            </div>
                            <a href="{{ asset('storage/' . $file->file_path) }}" target="_blank"
                                class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors">
                                👁️
                            </a>
                        </div>
                    @empty
                        <p class="text-slate-400 italic">Tidak ada berkas yang dilampirkan.</p>
                    @endforelse
                </div>
            </div>

            <!-- Final Letter Section -->
            @if ($letterRequest->final_letter)
                <div class="bg-emerald-50 rounded-2xl border border-emerald-100 p-8">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="font-bold text-emerald-900 text-lg flex items-center">
                            <span class="mr-2">✅</span> Surat Final (Tersedia)
                        </h3>
                        <a href="{{ asset('storage/' . $letterRequest->final_letter) }}" target="_blank"
                            class="bg-emerald-600 text-white px-4 py-2 rounded-xl text-xs font-bold hover:bg-emerald-700 transition-all">
                            Lihat Surat Final
                        </a>
                    </div>
                    <p class="text-emerald-700 text-sm">Surat ini telah diunggah dan dapat diunduh oleh pasien.</p>
                </div>
            @endif
        </div>

        <!-- Actions Sidebar -->
        <div class="space-y-6">
            <!-- Print Button Section -->
            @if (in_array($letterRequest->status, ['verified', 'approved', 'completed']))
                <div class="bg-indigo-600 rounded-2xl shadow-xl p-6 text-white hidden">
                    <h3 class="font-bold text-lg mb-4">Cetak Surat Resmi</h3>
                    <p class="text-indigo-100 text-sm mb-6">Gunakan format profesional untuk mencetak surat ini langsung
                        dari sistem.</p>
                    <a href="{{ route('admin.letter-requests.print-skbn', $letterRequest->id) }}" target="_blank"
                        class="block w-full text-center bg-white text-indigo-600 font-bold py-3 px-4 rounded-xl shadow-lg hover:bg-indigo-50 transition-all duration-300">
                        🖨️ Cetak SKBN
                    </a>
                </div>
            @endif

            <!-- Final Letter Upload Section -->
            @if (in_array($letterRequest->status, ['approved', 'completed']))
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                    <h3 class="font-bold text-slate-900 text-lg mb-4">Unggah Surat Final</h3>
                    <form action="{{ route('admin.letter-requests.upload-final', $letterRequest->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="mb-4">
                            <p class="text-xs text-slate-500 mb-3">Unggah dokumen yang sudah ditandatangani dan dicap
                                (PDF/JPG/PNG).</p>
                            <input type="file" name="final_letter" required
                                class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                        </div>
                        <button type="submit"
                            class="w-full bg-slate-900 hover:bg-black text-white font-bold py-3 px-4 rounded-xl transition-all duration-300">
                            Unggah & Selesaikan
                        </button>
                    </form>
                </div>
            @endif

            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                <h3 class="font-bold text-slate-900 text-lg mb-4">Aksi Admin</h3>
                <form action="{{ route('admin.letter-requests.update-status', $letterRequest->id) }}" method="POST">
                    @csrf
                    @method('PATCH')

                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Update Status</label>
                        <select name="status"
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 outline-none transition-all">
                            <option value="verified" {{ $letterRequest->status == 'verified' ? 'selected' : '' }}>
                                Verifikasi Berkas</option>
                            <option value="approved" {{ $letterRequest->status == 'approved' ? 'selected' : '' }}>Setujui
                                (Diproses)</option>
                            @if ($letterRequest->final_letter)
                                <option value="completed" {{ $letterRequest->status == 'completed' ? 'selected' : '' }}>
                                    Selesai
                                </option>
                            @endif
                            <option value="rejected" {{ $letterRequest->status == 'rejected' ? 'selected' : '' }}>Tolak
                                Permohonan</option>
                        </select>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Catatan Admin</label>
                        <textarea name="admin_notes" rows="4"
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 outline-none transition-all text-sm"
                            placeholder="Contoh: Berkas kurang lengkap, silakan upload ulang.">{{ $letterRequest->admin_notes }}</textarea>
                    </div>

                    <button type="submit"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-xl shadow-lg shadow-blue-200 transition-all duration-300">
                        Simpan Perubahan
                    </button>
                </form>
            </div>

            <div class="bg-slate-900 text-white rounded-2xl shadow-xl p-6">
                <h3 class="font-bold text-indigo-300 text-sm uppercase tracking-widest mb-4">Informasi Tambahan</h3>
                <div class="space-y-4">
                    <div>
                        <p class="text-[10px] text-slate-500 font-bold uppercase tracking-widest">Jenis Surat</p>
                        <p class="font-bold">{{ $letterRequest->letterType->name }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] text-slate-500 font-bold uppercase tracking-widest">Waktu Masuk</p>
                        <p class="font-bold">{{ $letterRequest->created_at->format('d M Y, H:i') }}</p>
                    </div>
                    @if ($letterRequest->processed_at)
                        <div>
                            <p class="text-[10px] text-slate-500 font-bold uppercase tracking-widest">Diproses Pada</p>
                            <p class="font-bold">{{ $letterRequest->processed_at }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
