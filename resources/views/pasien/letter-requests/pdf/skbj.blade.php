<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>SKBJ - {{ $letterRequest->patient->user->name }}</title>
    <style>
        @page {
            margin: 15mm;
        }
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 11pt;
            line-height: 1.4;
            color: #000;
        }
        .outer-border {
            border: 3px solid #000;
            padding: 10mm;
        }
        /* Header table */
        .header-table {
            width: 100%;
            border-bottom: 2px solid #000;
            padding-bottom: 6px;
            margin-bottom: 8px;
        }
        .header-table td {
            vertical-align: top;
        }
        .header-left {
            font-size: 10pt;
            font-weight: bold;
            text-transform: uppercase;
            line-height: 1.5;
        }
        .header-left .address {
            font-weight: normal;
            text-transform: none;
            font-style: italic;
        }
        /* Logo section */
        .logo-section {
            text-align: center;
            margin-bottom: 6px;
        }
        .logo-section img {
            height: 65px;
        }
        /* Title */
        .surat-title {
            font-size: 13pt;
            font-weight: bold;
            text-align: center;
            text-decoration: underline;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 2px;
        }
        .nomor-surat {
            text-align: center;
            font-size: 10pt;
            margin-bottom: 12px;
        }
        /* Opening paragraph */
        .opening {
            font-size: 10pt;
            margin-bottom: 8px;
        }
        /* Patient data table */
        .data-table {
            width: 100%;
            margin-bottom: 12px;
            margin-left: 20px;
        }
        .data-table td {
            padding: 2px 0;
            vertical-align: top;
            font-size: 10pt;
        }
        .label-col {
            width: 160px;
            font-weight: bold;
        }
        .colon-col {
            width: 14px;
        }
        /* Status section */
        .status-box {
            text-align: center;
            font-size: 13pt;
            font-weight: 900;
            letter-spacing: 2px;
            text-transform: uppercase;
            border-top: 2px solid #000;
            border-bottom: 2px solid #000;
            padding: 6px 20px;
            margin: 10px auto;
        }
        .status-wrapper {
            text-align: center;
            margin: 10px 0;
        }
        /* Purpose */
        .purpose {
            text-align: center;
            font-size: 13pt;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin: 10px 0;
        }
        /* Physical Stats + Footer Grid */
        .bottom-table {
            width: 100%;
            margin-top: 10px;
        }
        .bottom-table td {
            vertical-align: top;
        }
        .stats-cell {
            width: 50%;
            padding: 12px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
        }
        .sign-cell {
            width: 50%;
            text-align: center;
        }
        /* Stats table inside stats-cell */
        .stats-inner {
            width: 100%;
        }
        .stats-inner td {
            padding: 3px 0;
            font-size: 10pt;
            font-weight: bold;
        }
        .stat-label { width: 30px; }
        .stat-sep   { width: 14px; }
        .stat-val   { text-decoration: underline; }
        .stat-unit  { width: 40px; padding-left: 4px; }
        /* Media / signature */
        .media-row-table {
            margin: 8px auto;
        }
        .media-row-table td {
            vertical-align: middle;
            padding: 0 4px;
        }
        .photo-cell { width: 75px; text-align: center; }
        .qr-cell    { width: 85px; text-align: center; }
        .bold { font-weight: bold; }
        .italic { font-style: italic; }
        .underline { text-decoration: underline; }
        .uppercase { text-transform: uppercase; }
        .small { font-size: 10pt; }
        .mt-8 { margin-top: 8px; }
    </style>
</head>
<body>
<div class="outer-border">

    {{-- ==================== HEADER ==================== --}}
    <table class="header-table" cellspacing="0" cellpadding="0">
        <tr>
            <td class="header-left">
                KEPOLISIAN DAERAH SULAWESI SELATAN<br>
                BIDANG KEDOKTERAN DAN KESEHATAN<br>
                RUMAH SAKIT BHAYANGKARA TK.II MAKASSAR<br>
                <span class="address">Jalan. Letjen Pol. Mappaoddang No. 63 Makassar</span>
            </td>
        </tr>
    </table>

    {{-- ==================== LOGO & JUDUL ==================== --}}
    <div class="logo-section">
        @php
            $logoPath = public_path('images/logo_polri.png');
            $logoAlt  = public_path('images/logo-polisi.jpg');
            $logoSrc  = null;
            if (file_exists($logoPath)) {
                $logoSrc = 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath));
            } elseif (file_exists($logoAlt)) {
                $ext = pathinfo($logoAlt, PATHINFO_EXTENSION);
                $mime = $ext === 'jpg' || $ext === 'jpeg' ? 'image/jpeg' : 'image/png';
                $logoSrc = 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($logoAlt));
            }
        @endphp
        @if($logoSrc)
            <img src="{{ $logoSrc }}" alt="Logo Polri">
        @endif
    </div>

    <div class="surat-title">SURAT KETERANGAN BERBADAN SEHAT / JASMANI</div>
    <div class="nomor-surat">
        Nomor: {{ $letterRequest->nomor_surat ?? ('SKBJ/' . $letterRequest->id . '/' . \Carbon\Carbon::now()->translatedFormat('m/Y') . '/Rumkit') }}
    </div>

    {{-- ==================== OPENING ==================== --}}
    <div class="opening">
        Yang bertanda tangan di bawah ini, dokter Rumah Sakit Bhayangkara Makassar menerangkan bahwa:
    </div>

    {{-- ==================== DATA PASIEN ==================== --}}
    <table class="data-table" cellspacing="0" cellpadding="0">
        <tr>
            <td class="label-col bold">Nama</td>
            <td class="colon-col">:</td>
            <td class="bold uppercase">{{ $letterRequest->patient->user->name }}</td>
        </tr>
        <tr>
            <td class="label-col bold">Pangkat</td>
            <td class="colon-col">:</td>
            <td>{{ $letterRequest->patient->pangkat ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label-col bold">NRP / NIP</td>
            <td class="colon-col">:</td>
            <td>{{ $letterRequest->patient->nrp_nip ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label-col bold">Jenis Kelamin</td>
            <td class="colon-col">:</td>
            <td>{{ $letterRequest->patient->gender == 'L' ? 'LAKI-LAKI' : 'PEREMPUAN' }}</td>
        </tr>
        <tr>
            <td class="label-col bold">Pendidikan Terakhir</td>
            <td class="colon-col">:</td>
            <td>{{ $letterRequest->patient->pendidikan_terakhir ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label-col bold">Jabatan / Kesatuan</td>
            <td class="colon-col">:</td>
            <td>{{ $letterRequest->patient->jabatan_kesatuan ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label-col bold">Alamat</td>
            <td class="colon-col">:</td>
            <td>{{ $letterRequest->patient->address }}</td>
        </tr>
    </table>

    {{-- ==================== DINYATAKAN ==================== --}}
    <div class="small mt-8">
        Yang bersangkutan tersebut di atas telah diperiksa dan dinyatakan:
    </div>

    <div class="status-wrapper">
        <div class="status-box">
            ======== BERBADAN SEHAT =========
        </div>
    </div>

    {{-- ==================== KEPERLUAN ==================== --}}
    <div class="small mt-8">
        Demikian surat keterangan ini dibuat dipergunakan untuk:
    </div>

    <div class="purpose">
        {{ strtoupper($letterRequest->keperluan ?? $letterRequest->admin_notes ?? 'ASSESMENT JABATAN KAPOLRES') }}
    </div>

    {{-- ==================== FOOTER: STATS + TANDA TANGAN ==================== --}}
    @php
        $qrData    = route('verify-qr', $letterRequest->id);
        $qrUrl     = 'https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=' . urlencode($qrData);
        $qrContent = @file_get_contents($qrUrl);
        $qrSrc     = $qrContent !== false ? 'data:image/png;base64,' . base64_encode($qrContent) : null;

        $photoPath  = $letterRequest->photo_4x6 ? storage_path('app/public/' . $letterRequest->photo_4x6) : null;
        $photoSrc   = null;
        if ($photoPath && file_exists($photoPath)) {
            $ext = strtolower(pathinfo($photoPath, PATHINFO_EXTENSION));
            $mime = in_array($ext, ['jpg', 'jpeg']) ? 'image/jpeg' : 'image/png';
            $photoSrc = 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($photoPath));
        }
    @endphp

    <table class="bottom-table" cellspacing="0" cellpadding="0">
        <tr>
            {{-- KIRI: Stats Fisik --}}
            <td class="stats-cell">
                <table class="stats-inner" cellspacing="0" cellpadding="0">
                    <tr>
                        <td class="stat-label bold">TD</td>
                        <td class="stat-sep">:</td>
                        <td class="stat-val bold">{{ $letterRequest->pemeriksaan_data['td'] ?? '120/80' }}</td>
                        <td class="stat-unit">mmHg</td>
                    </tr>
                    <tr>
                        <td class="stat-label bold">TB</td>
                        <td class="stat-sep">:</td>
                        <td class="stat-val bold">{{ $letterRequest->pemeriksaan_data['tb'] ?? '164' }}</td>
                        <td class="stat-unit">CM</td>
                    </tr>
                    <tr>
                        <td class="stat-label bold">BB</td>
                        <td class="stat-sep">:</td>
                        <td class="stat-val bold">{{ $letterRequest->pemeriksaan_data['bb'] ?? '78' }}</td>
                        <td class="stat-unit">KG</td>
                    </tr>
                </table>
            </td>
            {{-- KANAN: Tanda Tangan --}}
            <td class="sign-cell">
                <div class="small">Makassar, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</div>
                <div class="small bold" style="margin-top:2px;">Dokter yang memeriksa</div>

                <table class="media-row-table" cellspacing="0" cellpadding="0">
                    <tr>
                       
                        {{-- QR Code --}}
                        <td class="qr-cell">
                            @if($qrSrc)
                                <img src="{{ $qrSrc }}" alt="QR Code" style="width:80px;height:80px;">
                            @endif
                        </td>
                    </tr>
                </table>

                <div class="small bold underline" style="margin-top:6px;  margin-right:6px">
                    {{ $letterRequest->dokterPemeriksa->name ?? 'Dr. dr. IRWAN, Sp.OG., M.Kes' }}
                </div>
                <div class="small bold">
                    AKBP NRP {{ $letterRequest->dokterPemeriksa->nrp ?? '74030679' }}
                </div>
            </td>
        </tr>
    </table>

</div>
</body>
</html>
