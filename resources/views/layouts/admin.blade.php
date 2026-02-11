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
            <div class="flex items-center space-x-4">
                <div class="text-right">
                    <p class="text-sm font-bold text-slate-900">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-slate-500">{{ Auth::user()->role }}</p>
                </div>
                <div
                    class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold border-2 border-white shadow-sm">
                    {{ substr(Auth::user()->name, 0, 1) }}
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
