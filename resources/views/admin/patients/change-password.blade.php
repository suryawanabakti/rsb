@extends('layouts.admin')

@section('title', 'Ubah Password Pasien')

@section('content')
    <div class="mb-8">
        <a href="{{ route('admin.patients.show', $patient->id) }}"
            class="text-slate-500 hover:text-slate-700 text-sm mb-4 inline-block">&larr; Kembali</a>
        <h1 class="text-2xl font-bold text-slate-900">Ubah Password Pasien</h1>
        <p class="text-slate-500">{{ $patient->user->name }} &mdash; @ {{ $patient->user->username }}</p>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-8 max-w-lg">
        <form action="{{ route('admin.patients.update-password', $patient->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-6">
                <label for="password" class="block text-sm font-bold text-slate-700 mb-2">Password Baru</label>
                <input type="password" name="password" id="password" required
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-blue-500 outline-none transition-all @error('password') border-red-500 @enderror"
                    placeholder="Minimal 8 karakter">
                @error('password')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="password_confirmation" class="block text-sm font-bold text-slate-700 mb-2">Konfirmasi Password Baru</label>
                <input type="password" name="password_confirmation" id="password_confirmation" required
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-blue-500 outline-none transition-all"
                    placeholder="Ulangi password baru">
            </div>

            <button type="submit"
                class="w-full bg-blue-600 text-white font-bold py-4 rounded-xl hover:bg-blue-700 transition-colors shadow-lg shadow-blue-200">
                Simpan Password Baru
            </button>
        </form>
    </div>
@endsection