<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak SKBN - {{ $letterRequest->patient->user->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            @page {
                size: A4;
                margin: 0;
            }

            body {
                margin: 0;
                -webkit-print-color-adjust: exact;
            }

            .no-print {
                display: none;
            }
        }

        body {
            font-family: 'Times New Roman', Times, serif;
            background-color: #f4f4f4;
        }

        .certificate-container {
            width: 210mm;
            min-height: 297mm;
            padding: 20mm;
            margin: auto;
            background-color: white;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        @media print {
            .certificate-container {
                box-shadow: none;
                margin: 0;
                width: 100%;
            }
        }

        .header-text {
            line-height: 1.2;
        }

        .line-underline {
            border-bottom: 2px solid black;
            display: inline-block;
            padding-bottom: 1px;
        }
    </style>
</head>

<body class="bg-slate-100">
    <div class="no-print fixed top-4 right-4 space-x-2">
        <button onclick="window.print()"
            class="bg-blue-600 text-white px-4 py-2 rounded-lg shadow-lg hover:bg-blue-700 transition-colors font-bold">
            🖨️ Cetak Surat
        </button>
        <a href="{{ route('admin.letter-requests.show', $letterRequest->id) }}"
            class="bg-slate-800 text-white px-4 py-2 rounded-lg shadow-lg hover:bg-slate-900 transition-colors font-bold">
            ← Kembali
        </a>
    </div>

    <div class="certificate-container">
        <!-- Header -->
        <div class="flex justify-between items-start mb-6">
            <div class="header-text text-sm font-bold uppercase">
                <p>KEPOLISIAN DAERAH SULAWESI SELATAN</p>
                <p>BIDANG KEDOKTERAN DAN KESEHATAN</p>
                <p>RUMAH SAKIT BHAYANGKARA TK.II MAKASSAR</p>
                <p class="font-normal normal-case italic underline decoration-1 underline-offset-2 mt-1">
                    Jalan. Letjen Pol. Mappaoddang No. 63 Makassar
                </p>
            </div>
            <div
                class="qr-code-placeholder w-24 h-24 border border-slate-200 flex items-center justify-center text-[10px] text-slate-400 text-center p-2">
                @php
                    $qrData = route('verify-qr', $letterRequest->id);
                @endphp
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ urlencode($qrData) }}"
                    alt="QR Code" class="w-full h-full">
            </div>
        </div>

        <!-- Logo -->
        <div class="text-center mb-6">
            <img src="{{ asset('images/logo_polri.png') }}" alt="Logo Polri" class="h-20 mx-auto mb-4"
                onerror="this.onerror=null; this.src='https://upload.wikimedia.org/wikipedia/commons/thumb/1/1d/Coat_of_arms_of_the_Indonesian_National_Police.svg/1024px-Coat_of_arms_of_the_Indonesian_National_Police.svg.png'">
            <h1 class="text-xl font-extrabold uppercase tracking-widest border-b-2 border-black inline-block px-4">SURAT
                KETERANGAN BEBAS NARKOBA</h1>
            <p class="text-sm font-bold mt-1">Nomor: {{ $letterRequest->nomor_surat ?? ('SKBN/' . $letterRequest->id . '/' . \Carbon\Carbon::now()->translatedFormat('m/Y') . '/Rumkit') }}</p>
        </div>

        <!-- Opening -->
        <div class="text-sm mb-6 leading-relaxed">
            <p>Yang bertanda tangan di bawah ini, dokter Rumah Sakit Bhayangkara Makassar menerangkan bahwa:</p>
        </div>

        <!-- Patient Details -->
        <div class="text-sm mb-6 space-y-1">
            <div class="flex">
                <div class="w-40 font-bold">Nama</div>
                <div class="w-4">:</div>
                <div class="flex-grow font-bold uppercase">{{ $letterRequest->patient->user->name }}</div>
            </div>
            <div class="flex">
                <div class="w-40 font-bold">Pangkat</div>
                <div class="w-4">:</div>
                <div class="flex-grow">{{ $letterRequest->patient->pangkat ?? '-' }}</div>
            </div>
            <div class="flex">
                <div class="w-40 font-bold">NRP / NIP</div>
                <div class="w-4">:</div>
                <div class="flex-grow">{{ $letterRequest->patient->nrp_nip ?? $letterRequest->patient->nik }}</div>
            </div>
            <div class="flex">
                <div class="w-40 font-bold">Jenis Kelamin</div>
                <div class="w-4">:</div>
                <div class="flex-grow">{{ $letterRequest->patient->gender == 'L' ? 'LAKI-LAKI' : 'PEREMPUAN' }}</div>
            </div>
            <div class="flex">
                <div class="w-40 font-bold">Pendidikan Terakhir</div>
                <div class="w-4">:</div>
                <div class="flex-grow">{{ $letterRequest->patient->pendidikan_terakhir ?? '-' }}</div>
            </div>
            <div class="flex">
                <div class="w-40 font-bold">Jabatan / Kesatuan</div>
                <div class="w-4">:</div>
                <div class="flex-grow">{{ $letterRequest->patient->jabatan_kesatuan ?? '-' }}</div>
            </div>
            <div class="flex">
                <div class="w-40 font-bold">Alamat</div>
                <div class="w-4">:</div>
                <div class="flex-grow font-bold italic underline underline-offset-2">
                    {{ $letterRequest->patient->address }}</div>
            </div>
            <div class="flex pt-2">
                <div class="w-40 font-bold">Waktu Pemeriksaan</div>
                <div class="w-4">:</div>
                <div class="flex-grow font-bold">{{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</div>
            </div>
        </div>

        <!-- Examination Result -->
        <div class="text-sm mb-6 leading-relaxed">
            <p>Hasil pemeriksaan kondisi kesehatan umum: <span class="font-bold italic">"Tidak ada tanda-tanda kelainan
                    fisik dan psikis terkait penyalahgunaan narkoba maupun ketergantungan (adiksi) dari zat-zat
                    narkoba"</span>, disertai pemeriksaan urine memakai 6 (enam) uji parameter dengan hasil:</p>

            <div class="mt-4 grid grid-cols-1 gap-1 max-w-lg mx-auto">
                @php
                    $parameters = [
                        ['a', 'Amphetamin (AMP)', $letterRequest->pemeriksaan_data['amp'] ?? 'Negatif'],
                        ['b', 'Methamphetamin (MET)', $letterRequest->pemeriksaan_data['met'] ?? 'Negatif'],
                        ['c', 'Morphine (MOP)', $letterRequest->pemeriksaan_data['mop'] ?? 'Negatif'],
                        ['d', 'Mariyuana (THC)', $letterRequest->pemeriksaan_data['thc'] ?? 'Negatif'],
                        ['e', 'Benzodiazepine (BZO)', $letterRequest->pemeriksaan_data['bzo'] ?? 'Negatif'],
                        ['f', 'Cocaine (COC)', $letterRequest->pemeriksaan_data['coc'] ?? 'Negatif'],
                    ];
                @endphp

                @foreach ($parameters as $param)
                    <div class="flex border-b border-dashed border-slate-300 py-1">
                        <div class="w-8">{{ $param[0] }}.</div>
                        <div class="w-64 font-bold">{{ $param[1] }}</div>
                        <div class="w-4">:</div>
                        <div class="flex-grow font-bold underline {{ $param[2] == 'POSITIF' ? 'text-red-600 decoration-red-500' : 'text-blue-800 decoration-blue-500' }}">
                            {{ $param[2] }}</div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Conclusion -->
        <div class="text-sm mb-6 font-bold leading-relaxed">
            <p>Kesimpulan: Yang bersangkutan pada saat diperiksa dinyatakan <span class="underline decoration-2">"BEBAS
                    NARKOBA"</span>.</p>
        </div>

        <div class="text-sm mb-10">
            <div class="flex">
                <div class="w-40 font-bold uppercase">Keperluan Untuk</div>
                <div class="w-4">:</div>
                <div class="flex-grow font-bold uppercase">
                    {{ $letterRequest->admin_notes ?? 'ADMINISTRASI KELENGKAPAN BERKAS' }}</div>
            </div>
        </div>

        <div class="text-sm mb-12 italic leading-relaxed">
            <p>Demikian surat keterangan ini dibuat dengan sebenar-benarnya berdasarkan kompetensi dan sumpah dokter,
                serta dipergunakan hanya untuk sesuai keperluan.</p>
        </div>

        <!-- Footer / Signature -->
        <div class="flex justify-end pr-10">
            <div class="text-center w-64">
                <p class="text-sm mb-2">Makassar, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
                <p class="text-sm font-bold mb-4 uppercase">Dokter Pemeriksa</p>

                <div class="signature-space h-24 mb-2 flex items-center justify-center relative">
                    <div class="qr-code-small w-20 h-20">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=100x100&data={{ urlencode($qrData) }}"
                            alt="Signature QR" class="w-full h-full opacity-80">
                    </div>
                    <!-- Photo Placeholder -->
                    <div
                        class="absolute -left-20 top-0 w-16 h-20 border border-slate-400 bg-slate-50 flex items-center justify-center">
                        <p class="text-[8px] text-slate-400">PAS PHOTO</p>
                    </div>
                </div>

                <p class="text-sm font-bold uppercase underline">
                    {{ $letterRequest->dokterPemeriksa->name ?? 'Dr. dr. IRWAN, Sp.OG., M.Kes' }}</p>
                <p class="text-sm font-bold">AKBP NRP {{ $letterRequest->dokterPemeriksa->nrp ?? '74030679' }}</p>
            </div>
        </div>
    </div>

    <!-- Print styling support -->
    <script>
        window.onload = function() {
            // Optional: Auto print if requested via query param
            if (window.location.search.includes('autoprint=1')) {
                window.print();
            }
        }
    </script>
</body>

</html>
