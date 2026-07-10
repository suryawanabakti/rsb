@extends('layouts.admin')

@section('title', 'Edit Pasien')

@section('content')
    <div class="mb-8">
        <a href="{{ route('admin.patients.show', $patient->id) }}"
            class="text-slate-500 hover:text-slate-700 text-sm mb-4 inline-block">&larr; Kembali</a>
        <h1 class="text-2xl font-bold text-slate-900">Edit Data Pasien</h1>
        <p class="text-slate-500">Silakan perbarui informasi pasien berikut.</p>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-8 max-w-2xl">
        <form action="{{ route('admin.patients.update', $patient->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-6">
                <label for="name" class="block text-sm font-bold text-slate-700 mb-2">Nama Lengkap</label>
                <input type="text" name="name" id="name" value="{{ old('name', $patient->user->name) }}" required
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-blue-500 outline-none transition-all @error('name') border-red-500 @enderror">
                @error('name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="username" class="block text-sm font-bold text-slate-700 mb-2">Username</label>
                <input type="text" name="username" id="username" value="{{ old('username', $patient->user->username) }}"
                    required
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-blue-500 outline-none transition-all @error('username') border-red-500 @enderror">
                @error('username')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="phone" class="block text-sm font-bold text-slate-700 mb-2">No. HP</label>
                <input type="text" name="phone" id="phone" value="{{ old('phone', $patient->user->phone) }}"
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-blue-500 outline-none transition-all @error('phone') border-red-500 @enderror">
                @error('phone')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <hr class="my-8 border-slate-100">

            <div class="mb-6">
                <label for="nik" class="block text-sm font-bold text-slate-700 mb-2">NIK</label>
                <input type="text" name="nik" id="nik" value="{{ old('nik', $patient->nik) }}" required
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-blue-500 outline-none transition-all @error('nik') border-red-500 @enderror">
                @error('nik')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="birth_date" class="block text-sm font-bold text-slate-700 mb-2">Tanggal Lahir</label>
                <input type="date" name="birth_date" id="birth_date"
                    value="{{ old('birth_date', $patient->birth_date ? \Carbon\Carbon::parse($patient->birth_date)->format('Y-m-d') : '') }}"
                    required
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-blue-500 outline-none transition-all @error('birth_date') border-red-500 @enderror">
                @error('birth_date')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="gender" class="block text-sm font-bold text-slate-700 mb-2">Jenis Kelamin</label>
                <select name="gender" id="gender" required
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-blue-500 outline-none transition-all @error('gender') border-red-500 @enderror">
                    <option value="L" {{ old('gender', $patient->gender) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                    <option value="P" {{ old('gender', $patient->gender) == 'P' ? 'selected' : '' }}>Perempuan</option>
                </select>
                @error('gender')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="address" class="block text-sm font-bold text-slate-700 mb-2">Alamat</label>
                <textarea name="address" id="address" rows="3" required
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-blue-500 outline-none transition-all @error('address') border-red-500 @enderror">{{ old('address', $patient->address) }}</textarea>
                @error('address')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <hr class="my-8 border-slate-100">

            <div class="mb-6">
                <label for="pangkat" class="block text-sm font-bold text-slate-700 mb-2">Pangkat/Golongan</label>
                <input type="text" name="pangkat" id="pangkat" value="{{ old('pangkat', $patient->pangkat) }}"
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-blue-500 outline-none transition-all @error('pangkat') border-red-500 @enderror">
                @error('pangkat')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="nrp_nip" class="block text-sm font-bold text-slate-700 mb-2">NRP / NIP</label>
                <input type="text" name="nrp_nip" id="nrp_nip" value="{{ old('nrp_nip', $patient->nrp_nip) }}"
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-blue-500 outline-none transition-all @error('nrp_nip') border-red-500 @enderror">
                @error('nrp_nip')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="pendidikan_terakhir" class="block text-sm font-bold text-slate-700 mb-2">Pendidikan Terakhir</label>
                <input type="text" name="pendidikan_terakhir" id="pendidikan_terakhir"
                    value="{{ old('pendidikan_terakhir', $patient->pendidikan_terakhir) }}"
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-blue-500 outline-none transition-all @error('pendidikan_terakhir') border-red-500 @enderror">
                @error('pendidikan_terakhir')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="jabatan_kesatuan" class="block text-sm font-bold text-slate-700 mb-2">Jabatan / Kesatuan</label>
                <input type="text" name="jabatan_kesatuan" id="jabatan_kesatuan"
                    value="{{ old('jabatan_kesatuan', $patient->jabatan_kesatuan) }}"
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-blue-500 outline-none transition-all @error('jabatan_kesatuan') border-red-500 @enderror">
                @error('jabatan_kesatuan')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit"
                class="w-full bg-blue-600 text-white font-bold py-4 rounded-xl hover:bg-blue-700 transition-colors shadow-lg shadow-blue-200">
                Perbarui Data Pasien
            </button>
        </form>
    </div>
@endsection