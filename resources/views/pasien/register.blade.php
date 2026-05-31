<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi Pasien - RS. Bhayangkara Makassar</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');

        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="bg-gray-50 flex items-center justify-center min-h-screen py-10 px-4">
    <div class="max-w-2xl w-full">
        <div class="text-center mb-10 flex flex-col items-center">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-20 h-20 mb-4 object-contain">
            <h1 class="text-2xl font-black text-blue-900 leading-tight uppercase tracking-tighter">Registrasi Pasien
                Baru</h1>
            <p class="text-gray-500 font-medium">Lengkapi data diri Anda untuk mengakses layanan surat</p>
        </div>

        <div class="bg-white rounded-3xl shadow-xl shadow-blue-900/5 p-8 md:p-10 border border-white">
            <form action="{{ route('register.submit') }}" method="POST">
                @csrf

                @if ($errors->any())
                    <div class="mb-8 p-4 bg-red-50 text-red-700 rounded-2xl text-sm border border-red-100 italic">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                    <!-- Data Akun -->
                    <div class="md:col-span-2">
                        <h2 class="text-sm font-black text-blue-900 uppercase tracking-widest mb-4 flex items-center">
                            <span class="w-8 h-[2px] bg-blue-600 mr-2"></span> Informasi Akun
                        </h2>
                    </div>

                    <div>
                        <label for="name"
                            class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Nama
                            Lengkap</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required
                            class="w-full px-5 py-3 rounded-2xl border border-gray-100 bg-gray-50 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all outline-none text-sm font-medium"
                            placeholder="Contoh: Andi Wijaya">
                    </div>

                    <div>
                        <label for="username"
                            class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Username</label>
                        <input type="text" name="username" id="username" value="{{ old('username') }}" required
                            class="w-full px-5 py-3 rounded-2xl border border-gray-100 bg-gray-50 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all outline-none text-sm font-medium"
                            placeholder="andiwijaya123">
                    </div>

                    <!-- Data Personal -->
                    <div class="md:col-span-2 mt-4">
                        <h2 class="text-sm font-black text-blue-900 uppercase tracking-widest mb-4 flex items-center">
                            <span class="w-8 h-[2px] bg-blue-600 mr-2"></span> Data Personal
                        </h2>
                    </div>

                    <div>
                        <label for="nik"
                            class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">NIK (Sesuai
                            KTP)</label>
                        <input type="text" name="nik" id="nik" value="{{ old('nik') }}" required
                            class="w-full px-5 py-3 rounded-2xl border border-gray-100 bg-gray-50 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all outline-none text-sm font-medium"
                            placeholder="16 Digit NIK">
                    </div>

                    <div>
                        <label for="phone"
                            class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Nomor
                            WhatsApp</label>
                        <input type="text" name="phone" id="phone" value="{{ old('phone') }}" required
                            class="w-full px-5 py-3 rounded-2xl border border-gray-100 bg-gray-50 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all outline-none text-sm font-medium"
                            placeholder="0812xxxx">
                    </div>

                    <div>
                        <label for="birth_date"
                            class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Tanggal
                            Lahir</label>
                        <input type="date" name="birth_date" id="birth_date" value="{{ old('birth_date') }}" required
                            class="w-full px-5 py-3 rounded-2xl border border-gray-100 bg-gray-50 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all outline-none text-sm font-medium">
                    </div>

                    <div>
                        <label for="gender"
                            class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Jenis
                            Kelamin</label>
                        <select name="gender" id="gender" required
                            class="w-full px-5 py-3 rounded-2xl border border-gray-100 bg-gray-50 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all outline-none text-sm font-medium appearance-none">
                            <option value="">Pilih Jenis Kelamin</option>
                            <option value="L" {{ old('gender') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="P" {{ old('gender') == 'P' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>

                    <div>
                        <label for="pangkat"
                            class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Pangkat</label>
                        <input type="text" name="pangkat" id="pangkat" value="{{ old('pangkat') }}"
                            class="w-full px-5 py-3 rounded-2xl border border-gray-100 bg-gray-50 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all outline-none text-sm font-medium"
                            placeholder="AKBP / BRIPTU / DLL">
                    </div>

                    <div>
                        <label for="nrp_nip"
                            class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">NRP / NIP</label>
                        <input type="text" name="nrp_nip" id="nrp_nip" value="{{ old('nrp_nip') }}"
                            class="w-full px-5 py-3 rounded-2xl border border-gray-100 bg-gray-50 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all outline-none text-sm font-medium"
                            placeholder="...">
                    </div>

                    <div>
                        <label for="pendidikan_terakhir"
                            class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Pendidikan Terakhir</label>
                        <input type="text" name="pendidikan_terakhir" id="pendidikan_terakhir" value="{{ old('pendidikan_terakhir') }}"
                            class="w-full px-5 py-3 rounded-2xl border border-gray-100 bg-gray-50 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all outline-none text-sm font-medium"
                            placeholder="S1 / S2 / SMA / DLL">
                    </div>

                    <div>
                        <label for="jabatan_kesatuan"
                            class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Jabatan / Kesatuan</label>
                        <input type="text" name="jabatan_kesatuan" id="jabatan_kesatuan" value="{{ old('jabatan_kesatuan') }}"
                            class="w-full px-5 py-3 rounded-2xl border border-gray-100 bg-gray-50 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all outline-none text-sm font-medium"
                            placeholder="...">
                    </div>

                    <div class="md:col-span-2">
                        <label for="address"
                            class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Alamat
                            Lengkap</label>
                        <textarea name="address" id="address" rows="3" required
                            class="w-full px-5 py-3 rounded-2xl border border-gray-100 bg-gray-50 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all outline-none text-sm font-medium"
                            placeholder="Alamat domisili saat ini">{{ old('address') }}</textarea>
                    </div>

                    <!-- Kata Sandi -->
                    <div class="md:col-span-2 mt-4">
                        <h2 class="text-sm font-black text-blue-900 uppercase tracking-widest mb-4 flex items-center">
                            <span class="w-8 h-[2px] bg-blue-600 mr-2"></span> Keamanan
                        </h2>
                    </div>

                    <div>
                        <label for="password"
                            class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Kata
                            Sandi</label>
                        <input type="password" name="password" id="password" required
                            class="w-full px-5 py-3 rounded-2xl border border-gray-100 bg-gray-50 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all outline-none text-sm font-medium"
                            placeholder="Minimal 8 karakter">
                    </div>

                    <div>
                        <label for="password_confirmation"
                            class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Konfirmasi Kata
                            Sandi</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" required
                            class="w-full px-5 py-3 rounded-2xl border border-gray-100 bg-gray-50 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all outline-none text-sm font-medium"
                            placeholder="Ulangi kata sandi">
                    </div>
                </div>

                <div class="mt-10">
                    <button type="submit"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-black py-4 rounded-2xl shadow-xl shadow-blue-200 transition-all duration-300 transform hover:-translate-y-1 uppercase tracking-widest text-sm">
                        Daftar Sebagai Pasien
                    </button>
                </div>

                <p class="text-center text-gray-500 text-sm mt-8 font-medium">
                    Sudah memiliki akun? <a href="{{ route('login') }}"
                        class="text-blue-600 font-bold hover:underline">Masuk di sini</a>
                </p>
            </form>
        </div>

        <p class="text-center text-gray-400 text-[10px] mt-10 font-bold uppercase tracking-[0.2em]">
            &copy; {{ date('Y') }} RS Bhayangkara Makassar. All rights reserved.
        </p>
    </div>
</body>

</html>
