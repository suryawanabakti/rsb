<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak SKBJ - {{ $letterRequest->patient->user->name }}</title>
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
            position: relative;
            overflow: hidden;
        }

        /* Border element based on image */
        .certificate-container::before {
            content: '';
            position: absolute;
            top: 10mm;
            left: 10mm;
            right: 10mm;
            bottom: 10mm;
            border: 15px solid #FFD700; /* Yellow border */
            z-index: 0;
            pointer-events: none;
        }
        
        .certificate-container::after {
            content: '';
            position: absolute;
            top: 12mm;
            left: 12mm;
            right: 12mm;
            bottom: 12mm;
            border: 5px solid #FF0000; /* Red border */
            z-index: 0;
            pointer-events: none;
        }

        .content-wrapper {
            position: relative;
            z-index: 1;
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
        
        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-30deg);
            opacity: 0.05;
            font-size: 100px;
            font-weight: bold;
            color: #000;
            z-index: -1;
            white-space: nowrap;
        }
    </style>
</head>

<body class="bg-slate-100">
    <div class="no-print fixed top-4 right-4 space-x-2 z-50">
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
        <div class="watermark uppercase tracking-widest">RS. BHAYANGKARA MAKASSAR</div>
        
        <div class="content-wrapper">
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
                <div class="w-24 h-24">
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
                <h1 class="text-xl font-extrabold uppercase tracking-widest border-b-2 border-black inline-block px-4">
                    SURAT KETERANGAN BERBADAN SEHAT / JASMANI
                </h1>
                <p class="text-sm font-bold mt-1">Nomor: {{ $letterRequest->nomor_surat ?? ('SKBJ/' . $letterRequest->id . '/' . \Carbon\Carbon::now()->translatedFormat('m/Y') . '/Rumkit') }}</p>
            </div>

            <!-- Opening -->
            <div class="text-sm mb-6 leading-relaxed">
                <p>Yang bertanda tangan di bawah ini, dokter Rumah Sakit Bhayangkara Makassar menerangkan bahwa:</p>
            </div>

            <!-- Patient Details -->
            <div class="text-sm mb-6 space-y-1 ml-10">
                <div class="flex">
                    <div class="w-48 font-bold">Nama</div>
                    <div class="w-4">:</div>
                    <div class="flex-grow font-bold uppercase">{{ $letterRequest->patient->user->name }}</div>
                </div>
                <div class="flex">
                    <div class="w-48 font-bold">Pangkat</div>
                    <div class="w-4">:</div>
                    <div class="flex-grow">{{ $letterRequest->patient->pangkat ?? '-' }}</div>
                </div>
                <div class="flex">
                    <div class="w-48 font-bold">NRP / NIP</div>
                    <div class="w-4">:</div>
                    <div class="flex-grow">{{ $letterRequest->patient->nrp_nip ?? '-' }}</div>
                </div>
                <div class="flex">
                    <div class="w-48 font-bold">Jenis Kelamin</div>
                    <div class="w-4">:</div>
                    <div class="flex-grow">{{ $letterRequest->patient->gender == 'L' ? 'LAKI-LAKI' : 'PEREMPUAN' }}</div>
                </div>
                <div class="flex">
                    <div class="w-48 font-bold">Pendidikan Terakhir</div>
                    <div class="w-4">:</div>
                    <div class="flex-grow">{{ $letterRequest->patient->pendidikan_terakhir ?? '-' }}</div>
                </div>
                <div class="flex">
                    <div class="w-48 font-bold">Jabatan / Kesatuan</div>
                    <div class="w-4">:</div>
                    <div class="flex-grow">{{ $letterRequest->patient->jabatan_kesatuan ?? '-' }}</div>
                </div>
                <div class="flex">
                    <div class="w-48 font-bold">Alamat</div>
                    <div class="w-4">:</div>
                    <div class="flex-grow font-bold italic underline underline-offset-2">
                        {{ $letterRequest->patient->address }}</div>
                </div>
            </div>

            <div class="text-sm mb-6 leading-relaxed">
                <p>Yang bersangkutan tersebut di atas telah diperiksa dan dinyatakan:</p>
            </div>

            <!-- Status Section -->
            <div class="text-center mb-8">
                <p class="text-lg font-black tracking-widest uppercase border-y-2 border-slate-900 py-2 inline-block px-12">
                    ==================== BERBADAN SEHAT ====================
                </p>
            </div>

            <div class="text-sm mb-6 leading-relaxed">
                <p>Demikian surat keterangan ini dibuat dipergunakan untuk:</p>
            </div>

            <!-- Purpose -->
            <div class="text-center mb-10">
                <p class="text-lg font-black uppercase tracking-widest">
                    {{ $letterRequest->admin_notes ?? 'ASSESMENT JABATAN KAPOLRES' }}
                </p>
            </div>

            <!-- Physical Stats and Footer Grid -->
            <div class="grid grid-cols-2 gap-8 items-start">
                <!-- Physical Stats -->
                <div class="space-y-4 bg-slate-50 p-6 border border-slate-200">
                    <div class="flex items-center">
                        <div class="w-12 font-bold">TD</div>
                        <div class="w-4">:</div>
                        <div class="flex-grow font-bold underline decoration-2">{{ $letterRequest->pemeriksaan_data['td'] ?? '120/80' }}</div>
                        <div class="ml-2">mmHg</div>
                    </div>
                    <div class="flex items-center">
                        <div class="w-12 font-bold">TB</div>
                        <div class="w-4">:</div>
                        <div class="flex-grow font-bold underline decoration-2">{{ $letterRequest->pemeriksaan_data['tb'] ?? '164' }}</div>
                        <div class="ml-2">CM</div>
                    </div>
                    <div class="flex items-center">
                        <div class="w-12 font-bold">BB</div>
                        <div class="w-4">:</div>
                        <div class="flex-grow font-bold underline decoration-2">{{ $letterRequest->pemeriksaan_data['bb'] ?? '78' }}</div>
                        <div class="ml-2">KG</div>
                    </div>
                </div>

                <!-- Signature -->
                <div class="text-center">
                    <p class="text-sm mb-1">Makassar, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
                    <p class="text-sm font-bold mb-2 uppercase">Dokter yang memeriksa</p>

                    <div class="signature-space h-32 mb-2 flex items-center justify-center relative">
                        <div class="qr-code-small w-24 h-24">
                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ urlencode($qrData) }}"
                                alt="Signature QR" class="w-full h-full opacity-80">
                        </div>
                    </div>

                    <p class="text-sm font-bold uppercase underline">
                        {{ $letterRequest->dokterPemeriksa->name ?? 'Dr. dr. IRWAN, Sp.OG., M.Kes' }}</p>
                    <p class="text-sm font-bold uppercase">AKBP NRP {{ $letterRequest->dokterPemeriksa->nrp ?? '74030679' }}</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        window.onload = function() {
            if (window.location.search.includes('autoprint=1')) {
                window.print();
            }
        }
    </script>
</body>

</html>
