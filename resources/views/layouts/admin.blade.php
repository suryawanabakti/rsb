<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin') - RS. Bhayangkara Makassar</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

        body {
            font-family: 'Inter', sans-serif;
        }

        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body class="bg-gray-50" x-data="{ sidebarOpen: true }">
    <!-- Sidebar -->
    <div class="fixed inset-y-0 left-0 z-50 w-64 bg-slate-900 transition-transform duration-300 transform"
        :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
        <div class="flex items-center space-x-3 h-20 px-6 bg-slate-800 border-b border-slate-700">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-10 h-10">
            <div class="overflow-hidden">
                <p class="text-white font-black text-sm leading-tight truncate">RS. BHAYANGKARA</p>
                <p class="text-blue-400 font-bold text-[10px] tracking-tight truncate uppercase">MAKASSAR</p>
            </div>
            <button @click="sidebarOpen = false" class="lg:hidden text-white ml-auto">&times;</button>
        </div>
        <nav class="mt-6 px-4 space-y-2">
            @php $role = Auth::user()->role; @endphp

            @if ($role === 'admin')
                <a href="{{ route('admin.dashboard') }}"
                    class="flex items-center px-4 py-3 rounded-xl transition-all {{ request()->routeIs('admin.dashboard') ? 'bg-blue-600 text-white shadow-lg' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                    <span class="mr-3">📊</span> Dashboard
                </a>
                <a href="{{ route('admin.letter-requests.index') }}"
                    class="flex items-center px-4 py-3 rounded-xl transition-all {{ request()->routeIs('admin.letter-requests.*') ? 'bg-blue-600 text-white shadow-lg' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                    <span class="mr-3">✉️</span> Permohonan Surat
                </a>
                <a href="{{ route('admin.letter-types.index') }}"
                    class="flex items-center px-4 py-3 rounded-xl transition-all {{ request()->routeIs('admin.letter-types.*') ? 'bg-blue-600 text-white shadow-lg' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                    <span class="mr-3">📋</span> Jenis Surat
                </a>
                <a href="{{ route('admin.patients.index') }}"
                    class="flex items-center px-4 py-3 rounded-xl transition-all {{ request()->routeIs('admin.patients.*') ? 'bg-blue-600 text-white shadow-lg' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                    <span class="mr-3">👥</span> Data Pasien
                </a>

                <div class="px-4 pt-4 pb-2">
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Manajemen Pengguna</p>
                </div>
                <a href="{{ route('admin.dokters.index') }}"
                    class="flex items-center px-4 py-3 rounded-xl transition-all {{ request()->routeIs('admin.dokters.*') ? 'bg-blue-600 text-white shadow-lg' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                    <span class="mr-3">👨‍⚕️</span> Data Dokter
                </a>
                <a href="{{ route('admin.petugas-labs.index') }}"
                    class="flex items-center px-4 py-3 rounded-xl transition-all {{ request()->routeIs('admin.petugas-labs.*') ? 'bg-blue-600 text-white shadow-lg' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                    <span class="mr-3">🧪</span> Data Petugas Lab
                </a>
            @elseif($role === 'petugas_lab')
                <a href="{{ route('petugas-lab.dashboard') }}"
                    class="flex items-center px-4 py-3 rounded-xl transition-all {{ request()->routeIs('petugas-lab.dashboard') ? 'bg-emerald-600 text-white shadow-lg' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                    <span class="mr-3">📊</span> Dashboard
                </a>
                <a href="{{ route('petugas-lab.lab-results.index') }}"
                    class="flex items-center px-4 py-3 rounded-xl transition-all {{ request()->routeIs('petugas-lab.lab-results.*') ? 'bg-emerald-600 text-white shadow-lg' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                    <span class="mr-3">🔬</span> Hasil Pemeriksaan
                </a>
                <a href="{{ route('petugas-lab.lab-results.create') }}"
                    class="flex items-center px-4 py-3 rounded-xl transition-all {{ request()->routeIs('petugas-lab.lab-results.create') ? 'bg-emerald-600 text-white shadow-lg' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                    <span class="mr-3">➕</span> Input Hasil Baru
                </a>
            @elseif($role === 'dokter')
                <a href="{{ route('dokter.dashboard') }}"
                    class="flex items-center px-4 py-3 rounded-xl transition-all {{ request()->routeIs('dokter.dashboard') ? 'bg-purple-600 text-white shadow-lg' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                    <span class="mr-3">📊</span> Dashboard
                </a>
                <a href="{{ route('admin.letter-requests.index') }}"
                    class="flex items-center px-4 py-3 rounded-xl transition-all {{ request()->routeIs('admin.letter-requests.*') ? 'bg-purple-600 text-white shadow-lg' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                    <span class="mr-3">✉️</span> Permohonan Surat
                </a>
                <a href="{{ route('dokter.lab-results.index') }}"
                    class="flex items-center px-4 py-3 rounded-xl transition-all {{ request()->routeIs('dokter.lab-results.*') ? 'bg-purple-600 text-white shadow-lg' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                    <span class="mr-3">🧪</span> Validasi Hasil Lab
                </a>
                <a href="{{ route('dokter.schedules.index') }}"
                    class="flex items-center px-4 py-3 rounded-xl transition-all {{ request()->routeIs('dokter.schedules.*') ? 'bg-purple-600 text-white shadow-lg' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                    <span class="mr-3">📅</span> Jadwal Praktik
                </a>
            @elseif($role === 'pimpinan')
                <a href="{{ route('pimpinan.dashboard') }}"
                    class="flex items-center px-4 py-3 rounded-xl transition-all {{ request()->routeIs('pimpinan.dashboard') ? 'bg-indigo-600 text-white shadow-lg' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                    <span class="mr-3">📊</span> Dashboard
                </a>
                <a href="{{ route('pimpinan.reports.index') }}"
                    class="flex items-center px-4 py-3 rounded-xl transition-all {{ request()->routeIs('pimpinan.reports.*') ? 'bg-indigo-600 text-white shadow-lg' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                    <span class="mr-3">📈</span> Laporan
                </a>
            @endif

            <div class="pt-10">
                <form action="{{ route('admin.logout') }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="flex items-center w-full px-4 py-3 rounded-xl text-red-400 hover:bg-red-500/10 hover:text-red-300 transition-all">
                        <span class="mr-3">🚪</span> Keluar
                    </button>
                </form>
            </div>
        </nav>

    </div>

    <!-- Main Content -->
    <div class="transition-all duration-300" :class="sidebarOpen ? 'lg:pl-64' : ''">
        <header class="h-16 bg-white border-b flex items-center justify-between px-8 sticky top-0 z-40">
            <button @click="sidebarOpen = !sidebarOpen" class="text-gray-500 hover:text-slate-900 transition-colors">
                <span class="text-2xl">☰</span>
            </button>
            <div class="flex items-center space-x-6">
                <!-- Notifications -->
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open"
                        class="relative p-2 text-slate-400 hover:text-slate-600 transition-colors focus:outline-none">
                        <span class="text-xl">🔔</span>
                        @if (Auth::user()->unreadNotifications->count() > 0)
                            <span
                                class="absolute top-0 right-0 h-4 w-4 bg-red-500 text-white text-[10px] font-bold rounded-full flex items-center justify-center border-2 border-white">
                                {{ Auth::user()->unreadNotifications->count() }}
                            </span>
                        @endif
                    </button>

                    <div x-show="open" @click.away="open = false" x-cloak
                        class="absolute right-0 mt-2 w-80 bg-white rounded-2xl shadow-xl border border-slate-100 overflow-hidden z-50">
                        <div class="p-4 border-b border-slate-50 flex justify-between items-center bg-slate-50/50">
                            <h3 class="font-bold text-slate-900 text-sm">Notifikasi</h3>
                        </div>
                        <div class="max-h-96 overflow-y-auto">
                            @forelse(Auth::user()->unreadNotifications as $notification)
                                <div
                                    class="p-4 border-b border-slate-50 hover:bg-slate-50 transition-colors relative group">
                                    @php
                                        $url = '#';
                                        if (isset($notification->data['lab_result_id'])) {
                                            $role = Auth::user()->role;
                                            $url = route(
                                                $role . '.lab-results.show',
                                                $notification->data['lab_result_id'],
                                            );
                                        } elseif (isset($notification->data['letter_request_id'])) {
                                            $url = route(
                                                'admin.letter-requests.show',
                                                $notification->data['letter_request_id'],
                                            );
                                        }
                                    @endphp
                                    <a href="{{ $url }}" class="text-left w-full block">
                                        <p class="text-xs font-bold text-slate-900 mb-1">
                                            {{ $notification->data['test_name'] ?? ($notification->data['letter_type_name'] ?? 'Update Sistem') }}
                                        </p>
                                        <p class="text-xs text-slate-500 leading-relaxed">
                                            {{ $notification->data['message'] }}</p>
                                        <p class="text-[10px] text-slate-400 mt-2 font-medium">
                                            {{ $notification->created_at->diffForHumans() }}</p>
                                    </a>
                                    <div class="absolute top-4 right-4 h-2 w-2 bg-blue-500 rounded-full"></div>
                                </div>
                            @empty
                                <div class="p-8 text-center">
                                    <span class="text-3xl mb-2 block">📭</span>
                                    <p class="text-xs text-slate-400 italic">Tidak ada notifikasi baru</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div class="flex items-center space-x-4">
                    <a href="{{ route('profile.edit') }}" class="text-right hover:opacity-80 transition-opacity">
                        <p class="text-sm font-bold text-slate-900 line-clamp-1">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-slate-500 uppercase">{{ str_replace('_', ' ', Auth::user()->role) }}
                        </p>
                    </a>
                    <a href="{{ route('profile.edit') }}" class="group">
                        <div
                            class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center border-2 border-white shadow-sm overflow-hidden transition-all group-hover:ring-4 group-hover:ring-blue-500/10">
                            <img src="{{ Auth::user()->photo_url }}" alt="Profile"
                                class="w-full h-full object-cover">
                        </div>
                    </a>
                </div>
            </div>
        </header>

        <main class="p-8">
            @if (session('success'))
                <div
                    class="mb-6 p-4 bg-emerald-50 text-emerald-700 rounded-xl border border-emerald-100 flex items-center shadow-sm">
                    <span class="mr-3">✅</span> {{ session('success') }}
                </div>
            @endif

            @yield('content')
        </main>
    </div>
</body>

</html>
