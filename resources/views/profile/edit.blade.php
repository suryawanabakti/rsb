@extends(Auth::user()->role === 'pasien' ? 'layouts.pasien' : 'layouts.admin')

@section('title', 'Edit Profil')

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-slate-900">Pengaturan Profil</h1>
            <p class="text-slate-500">Kelola informasi pribadi dan keamanan akun Anda.</p>
        </div>

        @if ($errors->any())
            <div class="mb-6 p-4 bg-red-50 text-red-700 rounded-xl border border-red-100 italic text-sm">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Profile Section -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="p-6 border-b border-slate-50 bg-slate-50/30">
                    <h2 class="font-bold text-slate-900">Informasi Pribadi</h2>
                </div>
                <div class="p-8">
                    <div class="flex flex-col md:flex-row gap-8 items-start">
                        <!-- Photo Upload -->
                        <div class="w-full md:w-1/3 flex flex-col items-center">
                            <div class="relative group cursor-pointer" x-data="{ photoPreview: '{{ Auth::user()->photo_url }}' }">
                                <img :src="photoPreview" alt="Profile"
                                    class="w-32 h-32 rounded-full object-cover border-4 border-white shadow-md transition-all group-hover:brightness-75">
                                <label for="photo-upload"
                                    class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                    <span class="text-white text-xs font-bold uppercase tracking-wider">Ubah Foto</span>
                                </label>
                                <input type="file" id="photo-upload" name="photo" class="hidden" accept="image/*"
                                    @change="
                                        const file = $event.target.files[0];
                                        if (file) {
                                            const reader = new FileReader();
                                            reader.onload = (e) => { photoPreview = e.target.result; };
                                            reader.readAsDataURL(file);
                                        }
                                    ">
                            </div>
                            <p class="text-[10px] text-slate-400 mt-4 text-center">Format: JPG, PNG, GIF. Max: 2MB</p>
                        </div>

                        <!-- Info Fields -->
                        <div class="w-full md:w-2/3 space-y-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Nama
                                    Lengkap</label>
                                <input type="text" name="name" value="{{ old('name', Auth::user()->name) }}"
                                    class="w-full px-4 py-3 rounded-xl bg-slate-50 border-transparent focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all outline-none text-slate-900 shadow-sm"
                                    required>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label
                                        class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Username</label>
                                    <input type="text" value="{{ Auth::user()->username }}"
                                        class="w-full px-4 py-3 rounded-xl bg-slate-100 border-transparent text-slate-400 shadow-sm outline-none"
                                        disabled>
                                    <p class="text-[10px] text-slate-400 mt-1 italic">Username tidak dapat diubah</p>
                                </div>
                                <div>
                                    <label
                                        class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Nomor
                                        Telepon</label>
                                    <input type="text" name="phone" value="{{ old('phone', Auth::user()->phone) }}"
                                        class="w-full px-4 py-3 rounded-xl bg-slate-50 border-transparent focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all outline-none text-slate-900 shadow-sm">
                                </div>
                            </div>

                            @if (Auth::user()->role === 'pasien')
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label
                                            class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">NIK</label>
                                        <input type="text" name="nik"
                                            value="{{ old('nik', Auth::user()->patient->nik ?? '') }}"
                                            class="w-full px-4 py-3 rounded-xl bg-slate-50 border-transparent focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all outline-none text-slate-900 shadow-sm"
                                            required>
                                    </div>
                                    <div>
                                        <label
                                            class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Jenis
                                            Kelamin</label>
                                        <select name="gender"
                                            class="w-full px-4 py-3 rounded-xl bg-slate-50 border-transparent focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all outline-none text-slate-900 shadow-sm"
                                            required>
                                            <option value="L"
                                                {{ old('gender', Auth::user()->patient->gender ?? '') === 'L' ? 'selected' : '' }}>
                                                Laki-laki</option>
                                            <option value="P"
                                                {{ old('gender', Auth::user()->patient->gender ?? '') === 'P' ? 'selected' : '' }}>
                                                Perempuan</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label
                                            class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Tanggal
                                            Lahir</label>
                                        <input type="date" name="birth_date"
                                            value="{{ old('birth_date', Auth::user()->patient->birth_date ?? '') }}"
                                            class="w-full px-4 py-3 rounded-xl bg-slate-50 border-transparent focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all outline-none text-slate-900 shadow-sm"
                                            required>
                                    </div>
                                    <div>
                                        <label
                                            class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Pangkat</label>
                                        <input type="text" name="pangkat"
                                            value="{{ old('pangkat', Auth::user()->patient->pangkat ?? '') }}"
                                            class="w-full px-4 py-3 rounded-xl bg-slate-50 border-transparent focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all outline-none text-slate-900 shadow-sm"
                                            placeholder="Contoh: AKBP">
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label
                                            class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">NRP / NIP</label>
                                        <input type="text" name="nrp_nip"
                                            value="{{ old('nrp_nip', Auth::user()->patient->nrp_nip ?? '') }}"
                                            class="w-full px-4 py-3 rounded-xl bg-slate-50 border-transparent focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all outline-none text-slate-900 shadow-sm"
                                            placeholder="Contoh: 86091922">
                                    </div>
                                    <div>
                                        <label
                                            class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Pendidikan Terakhir</label>
                                        <input type="text" name="pendidikan_terakhir"
                                            value="{{ old('pendidikan_terakhir', Auth::user()->patient->pendidikan_terakhir ?? '') }}"
                                            class="w-full px-4 py-3 rounded-xl bg-slate-50 border-transparent focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all outline-none text-slate-900 shadow-sm"
                                            placeholder="Contoh: S2">
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label
                                            class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Jabatan / Kesatuan</label>
                                        <input type="text" name="jabatan_kesatuan"
                                            value="{{ old('jabatan_kesatuan', Auth::user()->patient->jabatan_kesatuan ?? '') }}"
                                            class="w-full px-4 py-3 rounded-xl bg-slate-50 border-transparent focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all outline-none text-slate-900 shadow-sm"
                                            placeholder="Contoh: GADIK KEPOLISIAN MUDA">
                                    </div>
                                    <div>
                                        <label
                                            class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Alamat</label>
                                        <textarea name="address" rows="1"
                                            class="w-full px-4 py-3 rounded-xl bg-slate-50 border-transparent focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all outline-none text-slate-900 shadow-sm"
                                            required>{{ old('address', Auth::user()->patient->address ?? '') }}</textarea>
                                    </div>
                                </div>
                            @endif

                            @if (Auth::user()->role === 'dokter')
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label
                                            class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">NIP
                                            / NRP</label>
                                        <input type="text" name="nrp" value="{{ old('nrp', Auth::user()->nrp) }}"
                                            class="w-full px-4 py-3 rounded-xl bg-slate-50 border-transparent focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all outline-none text-slate-900 shadow-sm">
                                    </div>
                                    <div>
                                        <label
                                            class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Alamat
                                            Domisili</label>
                                        <input type="text" name="address"
                                            value="{{ old('address', Auth::user()->address) }}"
                                            class="w-full px-4 py-3 rounded-xl bg-slate-50 border-transparent focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all outline-none text-slate-900 shadow-sm">
                                    </div>
                                </div>

                                <div class="pt-4">
                                    <label
                                        class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Surat
                                        Izin Praktek (SIP)</label>
                                    <div class="flex items-center space-x-4">
                                        <div class="flex-grow">
                                            <input type="file" name="sip_file"
                                                class="w-full px-4 py-2 rounded-xl bg-slate-50 border border-dashed border-slate-300 text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-black file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition-all">
                                            <p class="text-[10px] text-slate-400 mt-1 italic">Format: PDF, JPG, PNG. Max:
                                                2MB</p>
                                        </div>
                                        @if (Auth::user()->sip_file)
                                            <a href="{{ Auth::user()->sip_url }}" target="_blank"
                                                class="px-4 py-2 bg-blue-50 text-blue-600 rounded-xl text-xs font-bold hover:bg-blue-100 transition-colors flex items-center shadow-sm">
                                                <span class="mr-2">📄</span> Lihat SIP
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Password Section -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="p-6 border-b border-slate-50 bg-slate-50/30">
                    <h2 class="font-bold text-slate-900">Keamanan Akun</h2>
                </div>
                <div class="p-8">
                    <div class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Password
                                    Baru</label>
                                <input type="password" name="password"
                                    class="w-full px-4 py-3 rounded-xl bg-slate-50 border-transparent focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all outline-none text-slate-900 shadow-sm"
                                    placeholder="Kosongkan jika tidak ingin mengubah">
                            </div>
                            <div>
                                <label
                                    class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Konfirmasi
                                    Password</label>
                                <input type="password" name="password_confirmation"
                                    class="w-full px-4 py-3 rounded-xl bg-slate-50 border-transparent focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all outline-none text-slate-900 shadow-sm"
                                    placeholder="Ulangi password baru">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-end pt-4">
                <button type="submit"
                    class="px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-lg shadow-blue-500/30 transition-all hover:-translate-y-1 active:scale-95">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
@endsection
