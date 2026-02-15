@extends('layouts.admin')

@section('title', 'Tambah Petugas Lab')

@section('content')
    <div class="mb-8">
        <a href="{{ route('admin.petugas-labs.index') }}"
            class="text-slate-500 hover:text-slate-700 text-sm mb-4 inline-block">&larr; Kembali</a>
        <h1 class="text-2xl font-bold text-slate-900">Tambah Petugas Lab Baru</h1>
        <p class="text-slate-500">Silakan isi formulir berikut untuk menambahkan petugas lab baru.</p>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-8 max-w-2xl">
        <form action="{{ route('admin.petugas-labs.store') }}" method="POST">
            @csrf

            <div class="mb-6">
                <label for="name" class="block text-sm font-bold text-slate-700 mb-2">Nama Lengkap</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-emerald-500 outline-none transition-all @error('name') border-red-500 @enderror">
                @error('name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="username" class="block text-sm font-bold text-slate-700 mb-2">Username</label>
                <input type="text" name="username" id="username" value="{{ old('username') }}" required
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-emerald-500 outline-none transition-all @error('username') border-red-500 @enderror">
                @error('username')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="nrp" class="block text-sm font-bold text-slate-700 mb-2">NIP / NRP (Opsional)</label>
                    <input type="text" name="nrp" id="nrp" value="{{ old('nrp') }}"
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-emerald-500 outline-none transition-all @error('nrp') border-red-500 @enderror"
                        placeholder="Contoh: 198001012005011001">
                    @error('nrp')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="phone" class="block text-sm font-bold text-slate-700 mb-2">No. HP (Opsional)</label>
                    <input type="text" name="phone" id="phone" value="{{ old('phone') }}"
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-emerald-500 outline-none transition-all @error('phone') border-red-500 @enderror"
                        placeholder="0812xxxxxxxx">
                    @error('phone')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mb-6">
                <label for="address" class="block text-sm font-bold text-slate-700 mb-2">Alamat Lengkap (Opsional)</label>
                <textarea name="address" id="address" rows="3"
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-emerald-500 outline-none transition-all @error('address') border-red-500 @enderror"
                    placeholder="Masukkan alamat lengkap petugas..."></textarea>
                @error('address')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div>
                    <label for="password" class="block text-sm font-bold text-slate-700 mb-2">Password</label>
                    <input type="password" name="password" id="password" required
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-emerald-500 outline-none transition-all @error('password') border-red-500 @enderror">
                    @error('password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="password_confirmation" class="block text-sm font-bold text-slate-700 mb-2">Konfirmasi
                        Password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" required
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-emerald-500 outline-none transition-all">
                </div>
            </div>

            <button type="submit"
                class="w-full bg-emerald-600 text-white font-bold py-4 rounded-xl hover:bg-emerald-700 transition-colors shadow-lg shadow-emerald-200">
                Simpan Data Petugas
            </button>
        </form>
    </div>
@endsection
