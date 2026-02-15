@extends('layouts.admin')

@section('title', 'Tambah Jadwal Dokter')

@section('content')
    <div class="mb-8">
        <a href="{{ route('admin.doctor-schedules.index') }}"
            class="text-blue-600 font-bold text-sm hover:underline flex items-center mb-2">
            ← Kembali ke Daftar Jadwal
        </a>
        <h1 class="text-2xl font-bold text-slate-900">Tambah Jadwal Dokter</h1>
        <p class="text-slate-500">Atur jadwal praktik baru untuk dokter</p>
    </div>

    <div class="max-w-4xl bg-white rounded-2xl shadow-sm border border-slate-100 p-8">
        <form action="{{ route('admin.doctor-schedules.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                <!-- Doctor Selection -->
                <div class="md:col-span-2">
                    <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Pilih
                        Dokter</label>
                    <select name="doctor_id" required
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-blue-500 outline-none text-sm transition-all focus:ring-4 focus:ring-blue-500/5">
                        <option value="">Pilih Dokter...</option>
                        @foreach ($doctors as $doctor)
                            <option value="{{ $doctor->id }}" {{ old('doctor_id') == $doctor->id ? 'selected' : '' }}>
                                {{ $doctor->name }} ({{ $doctor->username }})
                            </option>
                        @endforeach
                    </select>
                    @error('doctor_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Day of Week -->
                <div>
                    <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Hari</label>
                    <select name="day_of_week" required
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-blue-500 outline-none text-sm transition-all focus:ring-4 focus:ring-blue-500/5">
                        @foreach (['senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu', 'minggu'] as $day)
                            <option value="{{ $day }}" {{ old('day_of_week') == $day ? 'selected' : '' }}>
                                {{ ucfirst($day) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Room -->
                <div>
                    <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Ruangan
                        (Opsional)</label>
                    <input type="text" name="room" value="{{ old('room') }}"
                        placeholder="Contoh: Poli Umum, Ruang 102"
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-blue-500 outline-none text-sm transition-all focus:ring-4 focus:ring-blue-500/5">
                </div>

                <!-- Start Time -->
                <div>
                    <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Jam Mulai</label>
                    <input type="time" name="start_time" value="{{ old('start_time', '08:00') }}" required
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-blue-500 outline-none text-sm transition-all focus:ring-4 focus:ring-blue-500/5">
                    @error('start_time')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- End Time -->
                <div>
                    <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Jam
                        Selesai</label>
                    <input type="time" name="end_time" value="{{ old('end_time', '12:00') }}" required
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-blue-500 outline-none text-sm transition-all focus:ring-4 focus:ring-blue-500/5">
                    @error('end_time')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2 border-t border-slate-100 pt-6">
                    <label class="flex items-center cursor-pointer">
                        <input type="checkbox" name="is_active" value="1"
                            {{ old('is_active', true) ? 'checked' : '' }} class="sr-only peer">
                        <div
                            class="w-11 h-6 bg-slate-200 rounded-full peer peer-checked:bg-blue-600 after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:after:translate-x-full peer-checked:after:border-white relative transition-colors">
                        </div>
                        <span class="ml-3 text-sm font-bold text-slate-900">Jadwal Aktif</span>
                    </label>
                </div>
            </div>

            <button type="submit"
                class="bg-blue-600 text-white font-bold px-10 py-3 rounded-xl hover:bg-black transition-all shadow-lg shadow-blue-100 active:scale-95">
                Simpan Jadwal
            </button>
        </form>
    </div>
@endsection
