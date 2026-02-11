<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Layanan Digital RS. Bhayangkara Makassar</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">

    <!-- Framework -->
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .glass {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);
        }

        .hero-gradient {
            background: radial-gradient(circle at top right, #eff6ff 0%, #ffffff 50%);
        }
    </style>
</head>

<body class="bg-white text-slate-900 selection:bg-blue-100 selection:text-blue-900 hero-gradient min-h-screen">

    <!-- Navigation -->
    <nav class="sticky top-0 z-50 glass border-b border-slate-100">
        <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-12 w-12 object-contain">
                <div>
                    <span class="block font-black text-blue-900 leading-none">RS. BHAYANGKARA</span>
                    <span class="text-[10px] font-bold tracking-widest text-slate-500 uppercase">Makassar</span>
                </div>
            </div>

            <div class="hidden md:flex items-center space-x-8">
                <a href="#features"
                    class="text-sm font-bold text-slate-600 hover:text-blue-600 transition-colors">Layanan</a>
                <a href="#about"
                    class="text-sm font-bold text-slate-600 hover:text-blue-600 transition-colors">Tentang</a>
                <a href="{{ route('admin.login') }}"
                    class="inline-flex items-center justify-center px-6 py-2.5 rounded-xl bg-blue-600 text-white text-sm font-extrabold hover:bg-blue-700 hover:-translate-y-0.5 transition-all shadow-lg shadow-blue-200">
                    Admin Portal
                </a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <header class="relative pt-16 pb-24 overflow-hidden">
        <div class="max-w-7xl mx-auto px-6 grid md:grid-cols-2 gap-16 items-center">
            <div class="relative z-10">
                <div
                    class="inline-flex items-center space-x-2 px-3 py-1 rounded-full bg-blue-50 border border-blue-100 mb-6">
                    <span class="flex h-2 w-2 rounded-full bg-blue-600"></span>
                    <span class="text-[10px] font-black uppercase tracking-widest text-blue-700">Digital Health
                        Portal</span>
                </div>
                <h1 class="text-5xl lg:text-7xl font-black text-slate-900 leading-[1.1] mb-8">
                    Urus Surat Kesehatan <span class="text-blue-600 italic">Lebih Cepat.</span>
                </h1>
                <p class="text-lg text-slate-500 leading-relaxed mb-10 max-w-lg">
                    Sistem informasi administrasi surat menyurat RS. Bhayangkara Makassar kini hadir dalam genggaman
                    Anda. Ajukan & pantau surat kapan saja.
                </p>
                <div class="flex flex-col sm:row items-start space-y-4 sm:space-y-0 sm:space-x-4">
                    <div
                        class="p-4 bg-slate-900 rounded-2xl flex items-center space-x-4 shadow-xl border border-slate-800">
                        <div class="h-10 w-10 bg-slate-800 rounded-lg flex items-center justify-center">
                            <span class="text-xl">📱</span>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter leading-none">
                                Tersedia di</p>
                            <p class="text-white font-black">Aplikasi Mobile Patient</p>
                        </div>
                    </div>
                    <div class="flex items-center h-full pt-2">
                        <p class="text-xs text-slate-400 italic">Khusus untuk Pasien Terdaftar</p>
                    </div>
                </div>
            </div>

            <div class="relative">
                <div class="absolute inset-0 bg-blue-600/10 blur-[120px] rounded-full transform -translate-y-1/2"></div>
                <div
                    class="relative bg-white p-10 rounded-[4rem] shadow-2xl border border-slate-50 overflow-hidden group">
                    <img src="{{ asset('images/logo.png') }}" alt="Hospital Branding"
                        class="w-full h-auto aspect-square object-contain group-hover:scale-110 transition-transform duration-1000">
                </div>
            </div>
        </div>
    </header>

    <!-- Features -->
    <section id="features" class="py-24 bg-slate-50">
        <div class="max-w-7xl mx-auto px-6 text-center mb-16">
            <h2 class="text-sm font-black text-blue-600 uppercase tracking-widest mb-4">Keunggulan Sistem</h2>
            <p class="text-4xl font-black text-slate-900">Digitalisasi Layanan Administrasi</p>
        </div>

        <div class="max-w-7xl mx-auto px-6 grid md:grid-cols-3 gap-8">
            <!-- Card 1 -->
            <div
                class="bg-white p-10 rounded-[2.5rem] shadow-sm border border-slate-100 hover:shadow-xl hover:-translate-y-2 transition-all group">
                <div
                    class="h-16 w-16 bg-blue-50 rounded-2xl flex items-center justify-center mb-8 group-hover:bg-blue-600 transition-colors">
                    <span class="text-3xl group-hover:scale-125 transition-transform">📄</span>
                </div>
                <h3 class="text-xl font-black text-slate-900 mb-4 text-left">Pengajuan Online</h3>
                <p class="text-slate-500 leading-relaxed text-left">Ajukan surat rujukan, keterangan sehat, dan berkas
                    lainnya langsung melalui aplikasi pasien tanpa harus antre.</p>
            </div>

            <!-- Card 2 -->
            <div
                class="bg-white p-10 rounded-[2.5rem] shadow-sm border border-slate-100 hover:shadow-xl hover:-translate-y-2 transition-all group">
                <div
                    class="h-16 w-16 bg-emerald-50 rounded-2xl flex items-center justify-center mb-8 group-hover:bg-emerald-600 transition-colors">
                    <span class="text-3xl group-hover:scale-125 transition-transform">🛰️</span>
                </div>
                <h3 class="text-xl font-black text-slate-900 mb-4 text-left">Lacak Real-time</h3>
                <p class="text-slate-500 leading-relaxed text-left">Pantau status permohonan Anda mulai dari peninjauan
                    admin hingga siap untuk diunduh atau diambil.</p>
            </div>

            <!-- Card 3 -->
            <div
                class="bg-white p-10 rounded-[2.5rem] shadow-sm border border-slate-100 hover:shadow-xl hover:-translate-y-2 transition-all group">
                <div
                    class="h-16 w-16 bg-amber-50 rounded-2xl flex items-center justify-center mb-8 group-hover:bg-amber-600 transition-colors">
                    <span class="text-3xl group-hover:scale-125 transition-transform">🛡️</span>
                </div>
                <h3 class="text-xl font-black text-slate-900 mb-4 text-left">Keamanan Data</h3>
                <p class="text-slate-500 leading-relaxed text-left">Setiap berkas tersimpan aman dan hanya dapat diakses
                    oleh pasien yang bersangkutan & tim medis berwenang.</p>
            </div>
        </div>
    </section>

    <!-- App CTA -->
    <section class="py-24 max-w-7xl mx-auto px-6">
        <div
            class="bg-blue-600 rounded-[3rem] p-12 md:p-20 text-center relative overflow-hidden shadow-2xl shadow-blue-300">
            <div class="absolute top-0 right-0 h-full w-1/2 opacity-10 flex items-center justify-center -mr-20">
                <span class="text-[300px] leading-none">🚑</span>
            </div>
            <div class="relative z-10 max-w-2xl mx-auto">
                <h2 class="text-4xl md:text-5xl font-black text-white leading-tight mb-8">Siap Memulai Layanan
                    <br>Kesehatan Digital?</h2>
                <p class="text-blue-100 text-lg mb-12">Gunakan aplikasi mobile kami untuk mendapatkan akses penuh ke
                    semua layanan surat menyurat rumah sakit secara instan.</p>
                <div class="flex flex-wrap justify-center gap-4">
                    <button
                        class="px-10 py-5 bg-white text-blue-600 rounded-2xl font-black text-lg hover:scale-105 transition-transform shadow-lg">Portal
                        Pasien</button>
                    <a href="{{ route('admin.login') }}"
                        class="px-10 py-5 bg-blue-700 text-white rounded-2xl font-black text-lg hover:bg-blue-800 transition-colors flex items-center">
                        <span class="mr-3">🔑</span> Area Admin
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="py-12 border-t border-slate-100 bg-white">
        <div class="max-w-7xl mx-auto px-6 flex flex-col md:flex-row items-center justify-between">
            <div class="flex items-center space-x-3 mb-6 md:mb-0 opacity-50">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-8 w-8 grayscale">
                <p class="text-xs font-bold text-slate-500">© {{ date('Y') }} RS. BHAYANGKARA MAKASSAR. All rights
                    reserved.</p>
            </div>
            <div class="flex space-x-8">
                <a href="#"
                    class="text-xs font-black text-slate-400 hover:text-blue-600 uppercase tracking-widest">Syarat &
                    Ketentuan</a>
                <a href="#"
                    class="text-xs font-black text-slate-400 hover:text-blue-600 uppercase tracking-widest">Kebijakan
                    Privasi</a>
            </div>
        </div>
    </footer>

</body>

</html>
