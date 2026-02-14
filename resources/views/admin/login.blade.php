<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - RS. Bhayangkara Makassar</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="bg-gray-50 flex items-center justify-center min-h-screen">
    <div class="max-w-md w-full p-6">
        <div class="text-center mb-8 flex flex-col items-center">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-24 h-24 mb-4 object-contain">
            <h1 class="text-2xl font-extrabold text-blue-900 leading-tight">RS. BHAYANGKARA<br><span
                    class="text-lg">MAKASSAR</span></h1>
            <p class="text-gray-600 mt-2">Sistem Administrasi Surat</p>
        </div>

        <div class="bg-white rounded-2xl shadow-xl p-8">
            <form action="{{ route('admin.login.submit') }}" method="POST">
                @csrf

                @if ($errors->any())
                    <div class="mb-4 p-4 bg-red-50 text-red-700 rounded-xl text-sm border border-red-100 italic">
                        {{ $errors->first() }}
                    </div>
                @endif

                <div class="mb-6">
                    <label for="username" class="block text-sm font-semibold text-gray-700 mb-2">Username</label>
                    <input type="text" name="username" id="username" required
                        class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all outline-none"
                        placeholder="admin">
                </div>

                <div class="mb-8">
                    <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">Password</label>
                    <input type="password" name="password" id="password" required
                        class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all outline-none"
                        placeholder="••••••••">
                </div>

                <button type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-xl shadow-lg shadow-blue-200 transition-all duration-300 transform hover:-translate-y-1">
                    Masuk Sekarang
                </button>
            </form>

            <div class="mt-6 text-center">
                <p class="text-sm text-gray-500">
                    Belum punya akun?
                    <a href="{{ route('register') }}" class="text-blue-600 font-bold hover:underline">Daftar sebagai
                        Pasien</a>
                </p>
            </div>
        </div>

        <p class="text-center text-gray-500 text-sm mt-8">
            &copy; {{ date('Y') }} RS Bhayangkara. All rights reserved.
        </p>
    </div>
</body>

</html>
