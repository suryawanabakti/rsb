<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Pasien') - RS. Bhayangkara Makassar</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
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
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-10 h-10 object-contain">
            <div class="overflow-hidden">
                <p class="text-white font-black text-sm leading-tight truncate">RS. BHAYANGKARA</p>
                <p class="text-blue-400 font-bold text-[10px] tracking-tight truncate uppercase">MAKASSAR</p>
            </div>
            <button @click="sidebarOpen = false" class="lg:hidden text-white ml-auto">&times;</button>
        </div>
        <nav class="mt-6 px-4 space-y-2">
            <a href="{{ route('pasien.dashboard') }}"
                class="flex items-center px-4 py-3 rounded-xl transition-all {{ request()->routeIs('pasien.dashboard') ? 'bg-blue-600 text-white shadow-lg' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                <span class="mr-3">📊</span> Dashboard
            </a>
            <a href="{{ route('pasien.lab-results.index') }}"
                class="flex items-center space-x-3 px-6 py-4 {{ request()->routeIs('pasien.lab-results.*') ? 'bg-blue-600 text-white' : 'text-blue-100 hover:bg-blue-600/50 hover:text-white' }} transition-all duration-300 group">
                <span class="text-xl group-hover:scale-110 transition-transform duration-300">🧪</span>
                <span class="font-bold tracking-wide">Hasil Lab</span>
            </a>

            <a href="{{ route('pasien.letter-requests.index') }}"
                class="flex items-center px-4 py-3 rounded-xl transition-all {{ request()->routeIs('pasien.letter-requests.*') ? 'bg-blue-600 text-white shadow-lg' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                <span class="mr-3">✉️</span> Permohonan Surat
            </a>

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
                            @if (Auth::user()->unreadNotifications->count() > 0)
                                <form action="{{ route('pasien.notifications.mark-all-read') }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                        class="text-[10px] font-bold text-blue-600 hover:text-blue-700 uppercase tracking-wider">Tandai
                                        semua terbaca</button>
                                </form>
                            @endif
                        </div>
                        <div class="max-h-96 overflow-y-auto">
                            @forelse(Auth::user()->unreadNotifications as $notification)
                                <div
                                    class="p-4 border-b border-slate-50 hover:bg-slate-50 transition-colors relative group">
                                    <form action="{{ route('pasien.notifications.read', $notification->id) }}"
                                        method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="text-left w-full">
                                            <p class="text-xs font-bold text-slate-900 mb-1">
                                                {{ $notification->data['letter_type_name'] ?? 'Update Permohonan' }}
                                            </p>
                                            <p class="text-xs text-slate-500 leading-relaxed">
                                                {{ $notification->data['message'] }}</p>
                                            <p class="text-[10px] text-slate-400 mt-2 font-medium">
                                                {{ $notification->created_at->diffForHumans() }}</p>
                                        </button>
                                    </form>
                                    <div class="absolute top-4 right-4 h-2 w-2 bg-blue-500 rounded-full"></div>
                                </div>
                            @empty
                                <div class="p-8 text-center">
                                    <span class="text-3xl mb-2 block">📭</span>
                                    <p class="text-xs text-slate-400 italic">Tidak ada notifikasi baru</p>
                                </div>
                            @endforelse
                        </div>
                        @if (Auth::user()->notifications->count() > 0)
                            <div class="p-3 bg-slate-50 text-center border-t border-slate-100">
                                <a href="#"
                                    class="text-[10px] font-bold text-slate-500 hover:text-slate-700 uppercase tracking-wider">Lihat
                                    Semua Riwayat</a>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="flex items-center space-x-4">
                    <a href="{{ route('profile.edit') }}" class="text-right hover:opacity-80 transition-opacity">
                        <p class="text-sm font-bold text-slate-900 line-clamp-1">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-slate-500">Pasien</p>
                    </a>
                    <a href="{{ route('profile.edit') }}" class="group">
                        <div
                            class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center border-2 border-white shadow-sm overflow-hidden transition-all group-hover:ring-4 group-hover:ring-blue-500/10">
                            <img src="{{ Auth::user()->photo_url }}" alt="Profile" class="w-full h-full object-cover">
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
