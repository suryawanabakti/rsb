<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>SKBN - {{ $letterRequest->patient->user->name }}</title>
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
            border: 2px solid #000;
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
            font-size: 14pt;
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
            margin-bottom: 10px;
        }
        /* Opening paragraph */
        .opening {
            font-size: 10pt;
            margin-bottom: 8px;
        }
        /* Patient data table */
        .data-table {
            width: 100%;
            margin-bottom: 10px;
        }
        .data-table td {
            padding: 2px 0;
            vertical-align: top;
            font-size: 10pt;
        }
        .label-col {
            width: 140px;
            font-weight: bold;
        }
        .colon-col {
            width: 14px;
        }
        /* Lab results table */
        .lab-table {
            width: 70%;
            margin: 6px auto;
        }
        .lab-table td {
            padding: 2px 0;
            font-size: 10pt;
            border-bottom: 1px dashed #ccc;
        }
        .lab-num {
            width: 20px;
        }
        .lab-name {
            width: 160px;
            font-weight: bold;
        }
        .lab-sep {
            width: 12px;
        }
        .lab-result {
            font-weight: bold;
        }
        /* Footer / Signature section */
        .footer-table {
            width: 100%;
            margin-top: 10px;
        }
        .footer-table td {
            vertical-align: top;
        }
        .footer-left {
            width: 60%;
        }
        .footer-right {
            text-align: center;
            width: 40%;
        }
        .media-row-table {
            margin: 8px auto;
        }
        .media-row-table td {
            vertical-align: middle;
            padding: 0 4px;
        }
        .photo-cell {
            width: 75px;
            text-align: center;
        }
        .qr-cell {
            width: 85px;
            text-align: center;
        }
        .bold { font-weight: bold; }
        .italic { font-style: italic; }
        .underline { text-decoration: underline; }
        .text-center { text-align: center; }
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

    <div class="surat-title">SURAT KETERANGAN BEBAS NARKOBA</div>
    <div class="nomor-surat">
        Nomor: {{ $letterRequest->nomor_surat ?? ('SKBN/' . $letterRequest->id . '/' . \Carbon\Carbon::now()->translatedFormat('m/Y') . '/Rumkit') }}
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
            <td class="value-col bold uppercase">{{ $letterRequest->patient->user->name }}</td>
        </tr>
        <tr>
            <td class="label-col bold">Pangkat</td>
            <td class="colon-col">:</td>
            <td class="value-col">{{ $letterRequest->patient->pangkat ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label-col bold">NRP / NIP</td>
            <td class="colon-col">:</td>
            <td class="value-col">{{ $letterRequest->patient->nrp_nip ?? $letterRequest->patient->nik }}</td>
        </tr>
        <tr>
            <td class="label-col bold">Jenis Kelamin</td>
            <td class="colon-col">:</td>
            <td class="value-col">{{ $letterRequest->patient->gender == 'L' ? 'LAKI-LAKI' : 'PEREMPUAN' }}</td>
        </tr>
        <tr>
            <td class="label-col bold">Pendidikan Terakhir</td>
            <td class="colon-col">:</td>
            <td class="value-col">{{ $letterRequest->patient->pendidikan_terakhir ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label-col bold">Jabatan / Kesatuan</td>
            <td class="colon-col">:</td>
            <td class="value-col">{{ $letterRequest->patient->jabatan_kesatuan ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label-col bold">Alamat</td>
            <td class="colon-col">:</td>
            <td class="value-col">{{ $letterRequest->patient->address }}</td>
        </tr>
        <tr>
            <td class="label-col bold" style="padding-top:6px;">Waktu Pemeriksaan</td>
            <td class="colon-col" style="padding-top:6px;">:</td>
            <td class="value-col bold" style="padding-top:6px;">{{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</td>
        </tr>
    </table>

    {{-- ==================== HASIL PEMERIKSAAN ==================== --}}
    <div class="small mt-8">
        Hasil pemeriksaan kondisi kesehatan umum: <span class="bold italic">"Tidak ada tanda-tanda kelainan fisik dan psikis terkait penyalahgunaan narkoba maupun ketergantungan (adiksi) dari zat-zat narkoba"</span>, disertai pemeriksaan urine memakai 6 (enam) uji parameter dengan hasil: -------
    </div>

    @php
        $parameters = [
            ['a', 'Amphetamin (AMP)',     $letterRequest->pemeriksaan_data['amp'] ?? 'Negatif'],
            ['b', 'Methamphetamin (MET)', $letterRequest->pemeriksaan_data['met'] ?? 'Negatif'],
            ['c', 'Morphine (MOP)',       $letterRequest->pemeriksaan_data['mop'] ?? 'Negatif'],
            ['d', 'Mariyuana (THC)',      $letterRequest->pemeriksaan_data['thc'] ?? 'Negatif'],
            ['e', 'Benzodiazepine (BZO)', $letterRequest->pemeriksaan_data['bzo'] ?? 'Negatif'],
            ['f', 'Cocaine (COC)',        $letterRequest->pemeriksaan_data['coc'] ?? 'Negatif'],
        ];
    @endphp

    <table class="lab-table" cellspacing="0" cellpadding="0">
        @foreach($parameters as $param)
        <tr>
            <td class="lab-num small">{{ $param[0] }}.</td>
            <td class="lab-name small">{{ $param[1] }}</td>
            <td class="lab-sep small">:</td>
            <td class="lab-result small" style="color: {{ $param[2] === 'POSITIF' ? '#dc2626' : '#000' }}">
                {{ $param[2] }}{{ str_repeat('-', 50) }}
            </td>
        </tr>
        @endforeach
    </table>

    {{-- ==================== KESIMPULAN ==================== --}}
    <div class="small bold mt-8">
        Kesimpulan: Yang bersangkutan pada saat diperiksa dinyatakan: <span class="underline">"BEBAS NARKOBA"</span>.---
    </div>

    <div class="small mt-8">
        <strong>Keperluan Untuk &nbsp;: &nbsp; {{ strtoupper($letterRequest->keperluan ?? $letterRequest->admin_notes ?? 'ADMINISTRASI KELENGKAPAN BERKAS') }}</strong>
    </div>

    <div class="small mt-8 italic">
        Demikian surat keterangan ini dibuat dengan sebenar-benarnya berdasarkan kompetensi dan sumpah dokter, serta dipergunakan hanya untuk sesuai keperluan. -------------------------
    </div>

    {{-- ==================== FOOTER / TANDA TANGAN ==================== --}}
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

    <table class="footer-table" cellspacing="0" cellpadding="0">
        <tr>
            <td class="footer-left">&nbsp;</td>
            <td class="footer-right">
                <div class="small">Makassar, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</div>
                <div class="small bold" style="margin-top:2px;">Dokter Pemeriksa</div>

                {{-- Foto + QR Code berdampingan --}}
                <table class="media-row-table" cellspacing="0" cellpadding="0">
                    <tr>
                        {{-- Foto Pasien --}}
                        <td class="photo-cell">
                            @if($photoSrc)
                                <img src="{{ $photoSrc }}" alt="Pas Foto" style="width:70px;height:90px;border:1px solid #94a3b8;">
                            @else
                                <div style="width:70px;height:90px;border:1px solid #94a3b8;background:#f1f5f9;line-height:90px;text-align:center;">
                                    <span style="font-size:7pt;color:#94a3b8;">PAS FOTO</span>
                                </div>
                            @endif
                        </td>
                        {{-- QR Code --}}
                        <td class="qr-cell">
                            @if($qrSrc)
                                <img src="{{ $qrSrc }}" alt="QR Code" style="width:80px;height:80px;">
                            @endif
                        </td>
                    </tr>
                </table>

                {{-- Nama & NRP Dokter --}}
                <div class="small bold underline" style="margin-top:6px; margin-right:6px">
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
