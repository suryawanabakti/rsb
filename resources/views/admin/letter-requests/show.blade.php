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

            <!-- Patient Request Details -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-8">
                <h3 class="font-bold text-slate-900 text-lg mb-6 flex items-center">
                    <span class="mr-2">📝</span> Detail Permohonan Pasien
                </h3>
                <div class="space-y-6">
                    <div>
                        <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-1">Keperluan (Tujuan)</p>
                        <p class="font-bold text-slate-900 bg-slate-50 p-4 rounded-xl border border-slate-100">{{ $letterRequest->keperluan ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-1">Catatan Tambahan Pasien</p>
                        <p class="text-slate-600 bg-slate-50 p-4 rounded-xl border border-slate-100 text-sm italic">{{ $letterRequest->notes ?? '-' }}</p>
                    </div>
                </div>
            </div>

            <!-- Examination Data Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-8">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="font-bold text-slate-900 text-lg flex items-center">
                        <span class="mr-2">🔬</span> Data Hasil Pemeriksaan
                    </h3>
                </div>

                <form action="{{ route('admin.letter-requests.update-pemeriksaan', $letterRequest->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')

                    <div
                        class="mb-8 grid grid-cols-1 md:grid-cols-2 gap-4 p-4 bg-indigo-50 rounded-xl border border-indigo-100">
                        <div class="md:col-span-2 flex items-start space-x-4">
                            <div class="flex-shrink-0">
                                <label class="block text-xs font-black text-indigo-400 uppercase tracking-widest mb-2">Pas Foto 4x6</label>
                                @if ($letterRequest->photo_4x6_url)
                                    <img src="{{ $letterRequest->photo_4x6_url }}" alt="Pas Foto 4x6"
                                        class="w-24 h-32 object-cover rounded-lg border-2 border-indigo-200 shadow-sm">
                                @else
                                    <div class="w-24 h-32 bg-indigo-100 rounded-lg border-2 border-dashed border-indigo-300 flex items-center justify-center">
                                        <span class="text-[10px] text-indigo-400 font-bold text-center px-1">Belum ada foto</span>
                                    </div>
                                @endif
                            </div>
                            <div class="flex-grow">
                                <label class="block text-xs font-black text-indigo-400 uppercase tracking-widest mb-2">Upload / Ganti Pas Foto</label>
                                <input type="file" name="photo_4x6" accept=".jpg,.jpeg,.png"
                                    class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-bold file:bg-indigo-100 file:text-indigo-700 hover:file:bg-indigo-200">
                                <p class="text-[10px] text-indigo-300 mt-1">Format JPG/PNG, Maks 2MB</p>
                                @error('photo_4x6')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-black text-indigo-400 uppercase tracking-widest mb-2">Pilih
                                Dokter Pemeriksa (Tanda Tangan)</label>
                            <select name="dokter_pemeriksa_id" required
                                class="w-full px-4 py-3 rounded-lg border border-indigo-200 focus:border-indigo-500 outline-none text-sm font-bold bg-white">
                                <option value="">-- Pilih Dokter --</option>
                                @foreach ($doctors as $doc)
                                    <option value="{{ $doc->id }}"
                                        {{ ($letterRequest->dokter_pemeriksa_id ?? auth()->id()) == $doc->id ? 'selected' : '' }}>
                                        {{ $doc->name }} (NRP: {{ $doc->nrp ?? '-' }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-black text-indigo-400 uppercase tracking-widest mb-2">Nomor
                                Surat</label>
                            <input type="text" name="nomor_surat"
                                value="{{ $letterRequest->nomor_surat ?? 'SKBN/' . $letterRequest->id . '/' . now()->format('m/Y') . '/Rumkit' }}"
                                class="w-full px-4 py-3 rounded-lg border border-indigo-200 focus:border-indigo-500 outline-none text-sm font-bold bg-white"
                                placeholder="Contoh: SKBN/123/04/2026/Rumkit">
                        </div>
                    </div>

                    @if ($letterRequest->letterType->slug == 'skbn')
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @php
                                $parameters = [
                                    'amp' => 'Amphetamin (AMP)',
                                    'met' => 'Methamphetamin (MET)',
                                    'mop' => 'Morphine (MOP)',
                                    'thc' => 'Mariyuana (THC)',
                                    'bzo' => 'Benzodiazepine (BZO)',
                                    'coc' => 'Cocaine (COC)',
                                ];
                            @endphp
                            @foreach ($parameters as $key => $label)
                                <div>
                                    <label
                                        class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">{{ $label }}</label>
                                    <select name="pemeriksaan_data[{{ $key }}]"
                                        class="w-full px-4 py-2 rounded-lg border border-slate-200 focus:border-blue-500 outline-none text-sm font-bold {{ ($letterRequest->pemeriksaan_data[$key] ?? 'NEGATIF') == 'POSITIF' ? 'text-red-600' : 'text-blue-600' }}">
                                        <option value="NEGATIF"
                                            {{ ($letterRequest->pemeriksaan_data[$key] ?? 'NEGATIF') == 'NEGATIF' ? 'selected' : '' }}>
                                            NEGATIF</option>
                                        <option value="POSITIF"
                                            {{ ($letterRequest->pemeriksaan_data[$key] ?? 'NEGATIF') == 'POSITIF' ? 'selected' : '' }}>
                                            POSITIF</option>
                                    </select>
                                </div>
                            @endforeach
                        </div>
                    @elseif($letterRequest->letterType->slug == 'skbj')
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">TD
                                    (Tensi)</label>
                                <div class="flex items-center">
                                    <input type="text" name="pemeriksaan_data[td]"
                                        value="{{ $letterRequest->pemeriksaan_data['td'] ?? '120/80' }}"
                                        class="w-full px-4 py-2 rounded-lg border border-slate-200 focus:border-blue-500 outline-none text-sm font-bold">
                                    <span class="ml-2 text-xs text-slate-400 font-bold">mmHg</span>
                                </div>
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">TB
                                    (Tinggi Badan)</label>
                                <div class="flex items-center">
                                    <input type="text" name="pemeriksaan_data[tb]"
                                        value="{{ $letterRequest->pemeriksaan_data['tb'] ?? '164' }}"
                                        class="w-full px-4 py-2 rounded-lg border border-slate-200 focus:border-blue-500 outline-none text-sm font-bold">
                                    <span class="ml-2 text-xs text-slate-400 font-bold">CM</span>
                                </div>
                            </div>
                            <div>
                                <label
                                    class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">BB
                                    (Berat Badan)</label>
                                <div class="flex items-center">
                                    <input type="text" name="pemeriksaan_data[bb]"
                                        value="{{ $letterRequest->pemeriksaan_data['bb'] ?? '78' }}"
                                        class="w-full px-4 py-2 rounded-lg border border-slate-200 focus:border-blue-500 outline-none text-sm font-bold">
                                    <span class="ml-2 text-xs text-slate-400 font-bold">KG</span>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="mt-8 pt-6 border-t border-slate-100 flex justify-end">
                        <button type="submit"
                            class="bg-indigo-600 text-white px-8 py-3 rounded-xl font-bold shadow-lg hover:bg-indigo-700 transition-all transform hover:scale-105">
                            💾 Simpan & Update Preview
                        </button>
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

        </div>

        <!-- Actions Sidebar -->
        <div class="space-y-6">
            <!-- Print Button Section -->

            <div class="bg-indigo-600 rounded-2xl shadow-xl p-6 text-white">
                <h3 class="font-bold text-lg mb-4">Cetak Surat Resmi</h3>
                <p class="text-indigo-100 text-sm mb-6">Gunakan format profesional untuk mencetak surat ini langsung
                    dari sistem.</p>
                @if ($letterRequest->letterType->slug == 'skbn')
                    <div class="grid grid-cols-1 gap-2">
                        <a href="{{ route('admin.letter-requests.download-word', $letterRequest->id) }}"
                            class="text-center bg-indigo-500 text-white font-bold py-3 px-4 rounded-xl shadow-lg hover:bg-indigo-400 transition-all duration-300">
                            📝 Word
                        </a>
                    </div>
                @elseif($letterRequest->letterType->slug == 'skbj')
                    <div class="grid grid-cols-1 gap-2">
                        <a href="{{ route('admin.letter-requests.download-word', $letterRequest->id) }}"
                            class="text-center bg-indigo-500 text-white font-bold py-3 px-4 rounded-xl shadow-lg hover:bg-indigo-400 transition-all duration-300">
                            📝 Word
                        </a>
                    </div>
                @else
                    <button disabled
                        class="block w-full text-center bg-slate-300 text-slate-500 font-bold py-3 px-4 rounded-xl cursor-not-allowed">
                        Format Belum Tersedia
                    </button>
                @endif
            </div>


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
                    @if ($letterRequest->dokterPemeriksa)
                        <div class="pt-4 border-t border-slate-700">
                            <p class="text-[10px] text-slate-500 font-bold uppercase tracking-widest mb-3">Dokter Pemeriksa</p>
                            <div class="flex items-center space-x-3">
                                @if ($letterRequest->photo_4x6_url)
                                    <img src="{{ $letterRequest->photo_4x6_url }}" alt="Pas Foto"
                                        class="w-14 h-18 object-cover rounded-lg border-2 border-indigo-400 flex-shrink-0">
                                @endif
                                <div>
                                    <p class="font-bold text-sm">{{ $letterRequest->dokterPemeriksa->name }}</p>
                                    <p class="text-[10px] text-slate-400">NRP: {{ $letterRequest->dokterPemeriksa->nrp ?? '-' }}</p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
