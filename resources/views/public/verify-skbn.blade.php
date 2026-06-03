<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi SKBN - RS. Bhayangkara Makassar</title>
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .glass { background: rgba(255, 255, 255, 0.85); backdrop-filter: blur(12px); }
        .hero-gradient { background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 50%, #faf5ff 100%); }
    </style>
</head>

<body class="hero-gradient min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-2xl">
        <div class="glass rounded-3xl shadow-2xl border border-white/50 overflow-hidden">
            <!-- Verified Header -->
            <div class="bg-emerald-600 px-8 py-6 text-center">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-white/20 rounded-full mb-4">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <h1 class="text-2xl font-extrabold text-white uppercase tracking-wider">TERVERIFIKASI</h1>
                <p class="text-emerald-100 text-sm mt-1 font-medium">Dokumen ini telah diverifikasi oleh dokter</p>
            </div>

            <div class="p-8 space-y-6">
                <!-- Hospital Identity -->
                <div class="flex items-center space-x-4 pb-6 border-b border-slate-100">
                    <div class="w-14 h-14 bg-slate-900 rounded-2xl p-2.5 flex items-center justify-center shadow-lg">
                        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-full h-full object-contain">
                    </div>
                    <div>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">KEPOLISIAN DAERAH SULAWESI SELATAN</p>
                        <p class="text-sm font-extrabold text-slate-900">RS. BHAYANGKARA TK.II MAKASSAR</p>
                        <p class="text-xs text-slate-500">Jalan. Letjen Pol. Mappaoddang No. 63 Makassar</p>
                    </div>
                </div>

                <!-- Letter Info -->
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Jenis Surat</p>
                            <p class="text-lg font-extrabold text-slate-900">SURAT KETERANGAN BEBAS NARKOBA</p>
                        </div>
                        <span class="px-4 py-1.5 bg-emerald-100 text-emerald-700 rounded-full text-xs font-extrabold uppercase tracking-wider">SKBN</span>
                    </div>
                    <p class="text-sm text-slate-600">
                        <span class="font-bold text-slate-700">Nomor:</span>
                        {{ $letterRequest->nomor_surat ?? ('SKBN/' . $letterRequest->id . '/' . now()->format('m/Y') . '/Rumkit') }}
                    </p>
                </div>

                <!-- Patient Info -->
                <div class="bg-slate-50 rounded-2xl p-5 border border-slate-100 space-y-3">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Data Pemohon</p>
                    <div class="grid grid-cols-2 gap-2 text-sm">
                        <div>
                            <p class="text-slate-500">Nama</p>
                            <p class="font-bold text-slate-900 uppercase">{{ $letterRequest->patient->user->name }}</p>
                        </div>
                        <div>
                            <p class="text-slate-500">NRP / NIP</p>
                            <p class="font-bold text-slate-900">{{ $letterRequest->patient->nrp_nip ?? $letterRequest->patient->nik }}</p>
                        </div>
                        <div>
                            <p class="text-slate-500">Pangkat</p>
                            <p class="font-bold text-slate-900">{{ $letterRequest->patient->pangkat ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-slate-500">Jabatan / Kesatuan</p>
                            <p class="font-bold text-slate-900">{{ $letterRequest->patient->jabatan_kesatuan ?? '-' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Conclusion -->
                <div class="bg-emerald-50 rounded-2xl p-5 border border-emerald-100 text-center">
                    <p class="text-sm font-bold text-emerald-800 uppercase tracking-wide">Kesimpulan Pemeriksaan</p>
                    <p class="text-lg font-extrabold text-emerald-700 mt-1">BEBAS NARKOBA</p>
                </div>

                <!-- Doctor Signature -->
                <div class="border-t border-slate-100 pt-6">
                    <div class="text-center max-w-sm mx-auto">
                        <p class="text-xs text-slate-500 mb-1">Makassar, {{ now()->translatedFormat('d F Y') }}</p>
                        <p class="text-sm font-bold text-slate-700 mb-4">Dokter Pemeriksa</p>
                        <div class="w-20 h-20 mx-auto mb-3 p-1 border border-slate-200 rounded-xl">
                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=100x100&data={{ urlencode(route('verify-qr', $letterRequest->id)) }}"
                                alt="QR" class="w-full h-full">
                        </div>
                        <p class="text-base font-extrabold text-slate-900 underline decoration-2 decoration-emerald-500/30 underline-offset-4">
                            {{ $letterRequest->dokterPemeriksa->name ?? 'Dr. dr. IRWAN, Sp.OG., M.Kes' }}
                        </p>
                        <p class="text-sm font-bold text-slate-600">
                            AKBP NRP {{ $letterRequest->dokterPemeriksa->nrp ?? '74030679' }}
                        </p>
                    </div>
                </div>

                <!-- Footer -->
                <div class="text-center pt-4 border-t border-slate-100">
                    <p class="text-[10px] text-slate-400 font-medium">
                        Dokumen ini sah dan telah ditandatangani secara elektronik.
                        <br>Scan QR Code untuk verifikasi.
                    </p>
                </div>
            </div>
        </div>
    </div>
</body>

</html>