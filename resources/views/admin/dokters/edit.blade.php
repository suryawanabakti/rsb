@extends('layouts.admin')

@section('title', 'Edit Dokter')

@section('content')
    <div class="mb-8">
        <a href="{{ route('admin.dokters.index') }}"
            class="text-slate-500 hover:text-slate-700 text-sm mb-4 inline-block">&larr; Kembali</a>
        <h1 class="text-2xl font-bold text-slate-900">Edit Data Dokter</h1>
        <p class="text-slate-500">Silakan perbarui informasi dokter berikut.</p>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-8 max-w-2xl">
        <form action="{{ route('admin.dokters.update', $dokter->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-6">
                <label for="name" class="block text-sm font-bold text-slate-700 mb-2">Nama Lengkap</label>
                <input type="text" name="name" id="name" value="{{ old('name', $dokter->name) }}" required
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-blue-500 outline-none transition-all @error('name') border-red-500 @enderror">
                @error('name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="username" class="block text-sm font-bold text-slate-700 mb-2">Username</label>
                <input type="text" name="username" id="username" value="{{ old('username', $dokter->username) }}"
                    required
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-blue-500 outline-none transition-all @error('username') border-red-500 @enderror">
                @error('username')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="sip_file" class="block text-sm font-bold text-slate-700 mb-2">Surat Izin Praktek (SIP)</label>
                @if ($dokter->sip_file)
                    <div class="mb-3 flex items-center p-3 bg-blue-50 rounded-xl border border-blue-100">
                        <span class="text-lg mr-2">📄</span>
                        <div class="flex-grow">
                            <p class="text-xs font-bold text-blue-700 uppercase tracking-wider">SIP Saat Ini</p>
                            <a href="{{ $dokter->sip_url }}" target="_blank"
                                class="text-sm font-semibold text-blue-600 hover:underline">Lihat Dokumen SIP</a>
                        </div>
                    </div>
                @endif
                <div
                    class="p-4 border-2 border-dashed border-slate-200 rounded-2xl hover:border-blue-400 transition-all bg-slate-50/50">
                    <input type="file" name="sip_file" id="sip_file" accept=".pdf,.jpg,.jpeg,.png" class="hidden">
                    <label for="sip_file" class="cursor-pointer flex flex-col items-center justify-center">
                        <span class="text-2xl mb-2">📁</span>
                        <span class="text-sm font-bold text-slate-600"
                            id="file-name">{{ $dokter->sip_file ? 'Ganti file SIP' : 'Klik untuk pilih file' }}</span>
                        <span class="text-xs text-slate-400 mt-1">PDF, JPG, PNG (Maks. 2MB)</span>
                    </label>
                </div>
                @error('sip_file')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
                <script>
                    document.getElementById('sip_file').onchange = function() {
                        document.getElementById('file-name').textContent = this.files[0].name;
                    };
                </script>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="nrp" class="block text-sm font-bold text-slate-700 mb-2">NIP / NRP (Opsional)</label>
                    <input type="text" name="nrp" id="nrp" value="{{ old('nrp', $dokter->nrp) }}"
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-blue-500 outline-none transition-all @error('nrp') border-red-500 @enderror">
                    @error('nrp')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="phone" class="block text-sm font-bold text-slate-700 mb-2">No. HP (Opsional)</label>
                    <input type="text" name="phone" id="phone" value="{{ old('phone', $dokter->phone) }}"
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-blue-500 outline-none transition-all @error('phone') border-red-500 @enderror">
                    @error('phone')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mb-6">
                <label for="address" class="block text-sm font-bold text-slate-700 mb-2">Alamat Lengkap (Opsional)</label>
                <textarea name="address" id="address" rows="3"
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-blue-500 outline-none transition-all @error('address') border-red-500 @enderror">{{ old('address', $dokter->address) }}</textarea>
                @error('address')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="p-6 bg-slate-50 rounded-2xl border border-slate-100 mb-8">
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-4">Ganti Password (Opsional)</p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="password" class="block text-sm font-bold text-slate-700 mb-2">Password Baru</label>
                        <input type="password" name="password" id="password"
                            class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-blue-500 outline-none transition-all @error('password') border-red-500 @enderror"
                            placeholder="Kosongkan jika tidak ingin ganti">
                        @error('password')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="password_confirmation" class="block text-sm font-bold text-slate-700 mb-2">Konfirmasi
                            Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation"
                            class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-blue-500 outline-none transition-all">
                    </div>
                </div>
            </div>

            <button type="submit"
                class="w-full bg-blue-600 text-white font-bold py-4 rounded-xl hover:bg-blue-700 transition-colors shadow-lg shadow-blue-200">
                Perbarui Data Dokter
            </button>
        </form>
    </div>
@endsection
