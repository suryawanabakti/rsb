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

<body class="bg-slate-50 font-sans antialiased overflow-x-hidden" x-data="{ sidebarOpen: window.innerWidth >= 1024 }">
    <!-- Sidebar Backdrop -->
    <div x-show="sidebarOpen" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="sidebarOpen = false" 
         class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-40 lg:hidden" 
         x-cloak></div>

    <!-- Sidebar -->
    <div class="fixed inset-y-0 left-0 z-50 w-72 bg-slate-900 transition-all duration-300 transform shadow-2xl lg:shadow-none"
        :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
        
        <!-- Sidebar Header -->
        <div class="flex items-center justify-between h-20 px-6 bg-slate-800/50 border-b border-slate-700/50">
            <div class="flex items-center space-x-3">
                <div class="h-10 w-10 bg-white rounded-xl p-1.5 shadow-inner">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-full h-full object-contain">
                </div>
                <div class="overflow-hidden">
                    <p class="text-white font-black text-sm leading-tight tracking-tight truncate">RS. BHAYANGKARA</p>
                    <p class="text-blue-400 font-bold text-[10px] tracking-widest truncate uppercase">MAKASSAR</p>
                </div>
            </div>
            <button @click="sidebarOpen = false" class="lg:hidden h-8 w-8 flex items-center justify-center rounded-lg text-slate-400 hover:text-white hover:bg-slate-700 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        <!-- Sidebar Navigation -->
        <nav class="mt-6 px-4 space-y-1.5 flex-1 overflow-y-auto">
            <p class="px-4 text-[10px] font-bold text-slate-500 uppercase tracking-[0.2em] mb-4">Menu Utama</p>
            
            <a href="{{ route('pasien.dashboard') }}"
                class="flex items-center px-4 py-3 rounded-xl transition-all group {{ request()->routeIs('pasien.dashboard') ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/50' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                <span class="text-lg mr-3 group-hover:scale-110 transition-transform">📊</span>
                <span class="font-semibold text-sm">Dashboard</span>
            </a>

            <a href="{{ route('pasien.lab-results.index') }}"
                class="flex items-center px-4 py-3 rounded-xl transition-all group {{ request()->routeIs('pasien.lab-results.*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/50' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                <span class="text-lg mr-3 group-hover:scale-110 transition-transform">🧪</span>
                <span class="font-semibold text-sm">Hasil Lab</span>
            </a>

            <a href="{{ route('pasien.letter-requests.index') }}"
                class="flex items-center px-4 py-3 rounded-xl transition-all group {{ request()->routeIs('pasien.letter-requests.*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/50' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                <span class="text-lg mr-3 group-hover:scale-110 transition-transform">✉️</span>
                <span class="font-semibold text-sm">Permohonan Surat</span>
            </a>

            <div class="pt-8 mt-8 border-t border-slate-800">
                <form action="{{ route('admin.logout') }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="flex items-center w-full px-4 py-3 rounded-xl text-slate-400 hover:bg-red-500/10 hover:text-red-400 transition-all group">
                        <span class="text-lg mr-3 group-hover:rotate-12 transition-transform">🚪</span>
                        <span class="font-semibold text-sm">Keluar</span>
                    </button>
                </form>
            </div>
        </nav>

    </div>

    <!-- Main Content Container -->
    <div class="min-h-screen transition-all duration-300" :class="sidebarOpen ? 'lg:pl-72' : ''">
        <!-- Header -->
        <header class="h-20 bg-white/80 backdrop-blur-md border-b border-slate-100 flex items-center justify-between px-4 lg:px-8 sticky top-0 z-30">
            <div class="flex items-center space-x-4">
                <button @click="sidebarOpen = !sidebarOpen" class="h-10 w-10 flex items-center justify-center rounded-xl bg-slate-50 text-slate-500 hover:bg-slate-100 hover:text-slate-900 transition-all border border-slate-200 shadow-sm">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                </button>
                <div class="hidden sm:block">
                    <h2 class="text-lg font-bold text-slate-900">@yield('title')</h2>
                </div>
            </div>
            <div class="flex items-center space-x-2 sm:space-x-4">
                <!-- Notifications Dropdown -->
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open"
                        class="h-10 w-10 flex items-center justify-center rounded-xl bg-slate-50 text-slate-400 hover:bg-slate-100 hover:text-slate-600 transition-all border border-slate-200 relative shadow-sm">
                        <span class="text-xl">🔔</span>
                        @if (Auth::user()->unreadNotifications->count() > 0)
                            <span class="absolute -top-1 -right-1 h-5 w-5 bg-red-500 text-white text-[10px] font-black rounded-full flex items-center justify-center border-2 border-white animate-bounce">
                                {{ Auth::user()->unreadNotifications->count() }}
                            </span>
                        @endif
                    </button>

                    <div x-show="open" 
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 translate-y-4"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         @click.away="open = false" 
                         x-cloak
                         class="absolute right-0 mt-4 w-[calc(100vw-2rem)] sm:w-96 bg-white rounded-2xl shadow-2xl border border-slate-100 overflow-hidden z-50">
                        <div class="p-4 border-b border-slate-50 flex justify-between items-center bg-slate-50/50">
                            <h3 class="font-bold text-slate-900 text-sm">Notifikasi</h3>
                            @if (Auth::user()->unreadNotifications->count() > 0)
                                <form action="{{ route('pasien.notifications.mark-all-read') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="text-[10px] font-bold text-blue-600 hover:text-blue-700 uppercase tracking-wider">Tandai Baca Semua</button>
                                </form>
                            @endif
                        </div>
                        <div class="max-h-[60vh] overflow-y-auto">
                            @forelse(Auth::user()->unreadNotifications as $notification)
                                <div class="p-4 border-b border-slate-50 hover:bg-slate-50 transition-colors relative group">
                                    <form action="{{ route('pasien.notifications.read', $notification->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="text-left w-full group">
                                            <p class="text-xs font-bold text-slate-900 mb-1 group-hover:text-blue-600 transition-colors">
                                                {{ $notification->data['letter_type_name'] ?? 'Update Permohonan' }}
                                            </p>
                                            <p class="text-xs text-slate-500 leading-relaxed line-clamp-2">
                                                {{ $notification->data['message'] }}</p>
                                            <p class="text-[10px] text-slate-400 mt-2 font-medium flex items-center">
                                                <span class="mr-1">🕒</span> {{ $notification->created_at->diffForHumans() }}
                                            </p>
                                        </button>
                                    </form>
                                    <div class="absolute top-4 right-4 h-2 w-2 bg-blue-500 rounded-full ring-4 ring-blue-50"></div>
                                </div>
                            @empty
                                <div class="p-12 text-center">
                                    <div class="h-16 w-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <span class="text-3xl">📭</span>
                                    </div>
                                    <p class="text-sm font-medium text-slate-500">Tidak ada notifikasi baru</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- User Profile -->
                <div class="flex items-center pl-2 sm:pl-4 border-l border-slate-100">
                    <a href="{{ route('profile.edit') }}" class="flex items-center space-x-3 group">
                        <div class="hidden sm:block text-right">
                            <p class="text-sm font-bold text-slate-900 line-clamp-1 group-hover:text-blue-600 transition-colors">{{ Auth::user()->name }}</p>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Pasien</p>
                        </div>
                        <div class="h-10 w-10 rounded-xl bg-blue-100 flex items-center justify-center border-2 border-white shadow-sm overflow-hidden transition-all group-hover:ring-4 group-hover:ring-blue-500/10">
                            <img src="{{ Auth::user()->photo_url }}" alt="Profile" class="w-full h-full object-cover">
                        </div>
                    </a>
                </div>
            </div>
        </header>

        <main class="p-4 lg:p-8">
            @if (session('success'))
                <div class="mb-6 p-4 bg-emerald-50 text-emerald-700 rounded-2xl border border-emerald-100 flex items-center shadow-sm animate-fade-in-down">
                    <span class="mr-3 text-lg">✅</span> 
                    <p class="text-sm font-semibold">{{ session('success') }}</p>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-6 p-4 bg-rose-50 text-rose-700 rounded-2xl border border-rose-100 flex items-center shadow-sm animate-fade-in-down">
                    <span class="mr-3 text-lg">⚠️</span> 
                    <p class="text-sm font-semibold">{{ session('error') }}</p>
                </div>
            @endif

            @yield('content')
        </main>
    </div>
</body>

</html>
