<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LetterRequest;
use Illuminate\Http\Request;

class LetterRequestController extends Controller
{
    public function index(Request $request)
    {
        $query = LetterRequest::with(['patient.user', 'letterType']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('patient.user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('username', 'like', "%{$search}%");
            })->orWhereHas('patient', function ($q) use ($search) {
                $q->where('nik', 'like', "%{$search}%");
            });
        }

        $requests = $query->latest()->paginate(10);

        return view('admin.letter-requests.index', compact('requests'));
    }

    public function show(LetterRequest $letterRequest)
    {
        $letterRequest->load(['patient.user', 'letterType', 'files', 'dokterPemeriksa']);
        $doctors = \App\Models\User::where('role', 'dokter')->get();

        return view('admin.letter-requests.show', compact('letterRequest', 'doctors'));
    }

    public function updateStatus(Request $request, LetterRequest $letterRequest)
    {
        $request->validate([
            'status' => 'required|in:verified,approved,rejected,completed',
            'admin_notes' => 'nullable|string',
        ]);

        $letterRequest->update([
            'status' => $request->status,
            'admin_notes' => $request->admin_notes,
            'processed_at' => now(),
            'processed_by' => auth()->id(),
        ]);

        // Send notification to patient
        $letterRequest->patient->user->notify(new \App\Notifications\LetterRequestStatusChanged($letterRequest));

        return back()->with('success', 'Status permohonan berhasil diperbarui.');
    }

    public function updatePemeriksaan(Request $request, LetterRequest $letterRequest)
    {
        $data = [
            'pemeriksaan_data' => $request->pemeriksaan_data,
            'dokter_pemeriksa_id' => $request->dokter_pemeriksa_id,
            'nomor_surat' => $request->nomor_surat,
            'processed_by' => auth()->id(),
            'processed_at' => now(),
        ];

        if ($request->hasFile('photo_4x6')) {
            $request->validate(['photo_4x6' => 'image|mimes:jpg,jpeg,png|max:2048']);
            $data['photo_4x6'] = $request->file('photo_4x6')->store('photos-4x6', 'public');
        }

        $letterRequest->update($data);

        return back()->with('success', 'Data hasil pemeriksaan berhasil diperbarui.');
    }

    public function printSkbn(LetterRequest $letterRequest)
    {
        $letterRequest->load(['patient.user', 'letterType', 'files', 'dokterPemeriksa']);

        $labResults = \App\Models\LabResult::where('letter_request_id', $letterRequest->id)
            ->orWhere('patient_id', $letterRequest->patient_id)
            ->with('validator')
            ->latest()
            ->first();

        return view('admin.letter-requests.print-skbn', compact('letterRequest', 'labResults'));
    }

    public function printSkbj(LetterRequest $letterRequest)
    {
        $letterRequest->load(['patient.user', 'letterType', 'files', 'dokterPemeriksa']);

        $labResults = \App\Models\LabResult::where('letter_request_id', $letterRequest->id)
            ->orWhere('patient_id', $letterRequest->patient_id)
            ->with('validator')
            ->latest()
            ->first();

        return view('admin.letter-requests.print-skbj', compact('letterRequest', 'labResults'));
    }

    public function downloadWord(LetterRequest $letterRequest)
    {
        $letterRequest->load(['patient.user', 'letterType', 'dokterPemeriksa']);
        $labResults = \App\Models\LabResult::where('letter_request_id', $letterRequest->id)
            ->orWhere('patient_id', $letterRequest->patient_id)
            ->with('validator')
            ->latest()
            ->first();

        $phpWord = new \PhpOffice\PhpWord\PhpWord;
        $phpWord->setDefaultFontName('Times New Roman');
        $phpWord->setDefaultFontSize(11);

        $section = $phpWord->addSection([
            'paperSize' => 'Legal',
            'marginTop' => 600,
            'marginBottom' => 600,
            'marginLeft' => 600,
            'marginRight' => 600,
            'borderTopSize' => 12,
            'borderTopColor' => '000000',
            'borderBottomSize' => 12,
            'borderBottomColor' => '000000',
            'borderLeftSize' => 12,
            'borderLeftColor' => '000000',
            'borderRightSize' => 12,
            'borderRightColor' => '000000',
        ]);

        if ($letterRequest->letterType->slug == 'skbn') {
            $this->generateSkbnWord($section, $letterRequest, $labResults);
        } else {
            $this->generateSkbjWord($section, $letterRequest, $labResults);
        }

        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        $fileName = 'Surat_'.strtoupper($letterRequest->letterType->slug).'_'.str_replace(' ', '_', $letterRequest->patient->user->name).'.docx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        header('Content-Disposition: attachment;filename="'.$fileName.'"');
        header('Cache-Control: max-age=0');

        $objWriter->save('php://output');
        exit;
    }

    public function generateSkbnWord($section, $letterRequest, $labResults)
    {
        // Header
        $headerTable = $section->addTable(['width' => 100 * 50, 'unit' => 'pct']);
        $row = $headerTable->addRow();
        $cell = $row->addCell(10000);
        $cell->addText('KEPOLISIAN DAERAH SULAWESI SELATAN', ['bold' => true, 'size' => 10]);
        $cell->addText('BIDANG KEDOKTERAN DAN KESEHATAN', ['bold' => true, 'size' => 10]);
        $cell->addText('RUMAH SAKIT BHAYANGKARA TK.II MAKASSAR', ['bold' => true, 'size' => 10]);
        $cell->addText('Jalan. Letjen Pol. Mappaoddang No. 63 Makassar', ['size' => 10]);

        $section->addTextBreak(1);

        // Logo Centered
        $logoPath = public_path('images/logo-polisi.jpg');
        if (file_exists($logoPath)) {
            $section->addImage($logoPath, [
                'width' => 50,
                'height' => 50,
                'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER,
            ]);
        }

        // Title
        $section->addText('SURAT KETERANGAN BEBAS NARKOBA', ['bold' => true, 'size' => 14, 'underline' => 'single'], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
        $nomorSurat = $letterRequest->nomor_surat ?? ('SKBN/'.$letterRequest->id.'/'.now()->format('m/Y').'/Rumkit');
        $section->addText('Nomor: '.$nomorSurat, [], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);

        $section->addText('Yang bertanda tangan di bawah ini, dokter Rumah Sakit Bhayangkara Makassar menerangkan bahwa:');

        $table = $section->addTable(['width' => 100 * 50, 'unit' => 'pct']);
        $this->addTableRow($table, 'Nama', ': '.strtoupper($letterRequest->patient->user->name), true);
        $this->addTableRow($table, 'Pangkat', ': '.($letterRequest->patient->pangkat ?? '-'));
        $this->addTableRow($table, 'NRP / NIP', ': '.($letterRequest->patient->nrp_nip ?? '-'));
        $this->addTableRow($table, 'Jenis Kelamin', ': '.($letterRequest->patient->gender == 'L' ? 'LAKI-LAKI' : 'PEREMPUAN'));
        $this->addTableRow($table, 'Pendidikan Terakhir', ': '.($letterRequest->patient->pendidikan_terakhir ?? '-'));
        $this->addTableRow($table, 'Jabatan / Kesatuan', ': '.($letterRequest->patient->jabatan_kesatuan ?? '-'));
        $this->addTableRow($table, 'Alamat', ': '.$letterRequest->patient->address);
        $this->addTableRow($table, 'Waktu Pemeriksaan', ': '.now()->translatedFormat('d F Y'), true);

        $section->addText('Hasil pemeriksaan kondisi kesehatan umum: "Tidak ada tanda-tanda kelainan fisik dan psikis terkait penyalahgunaan narkoba maupun ketergantungan (adiksi) dari zat-zat narkoba", disertai pemeriksaan urine memakai 6 (enam) uji parameter dengan hasil: -------');

        $labTable = $section->addTable(['width' => 100 * 50, 'unit' => 'pct']);
        $params = [
            'amp' => 'a. Amphetamin (AMP)',
            'met' => 'b. Methamphetamin (MET)',
            'mop' => 'c. Morphine (MOP)',
            'thc' => 'd. Mariyuana (THC)',
            'bzo' => 'e. Benzodiazepine (BZO)',
            'coc' => 'f. Cocaine (COC)',
        ];

        foreach ($params as $key => $label) {
            $value = ucfirst(strtolower($letterRequest->pemeriksaan_data[$key] ?? 'Negatif'));
            $row = $labTable->addRow();
            $row->addCell(3500)->addText($label);

            // Add dots after the result
            $resultText = ': '.$value.'-------------------------------------------------------';
            $row->addCell(6500)->addText($resultText);
        }

        $section->addText('Kesimpulan: Yang bersangkutan pada saat diperiksa dinyatakan: "BEBAS NARKOBA".---');

        $section->addText('Keperluan Untuk   :   '.strtoupper($letterRequest->keperluan ?? 'ASSESMENT JABATAN KAPOLRES'), ['bold' => true]);

        $section->addText('Demikian surat keterangan ini dibuat dengan sebenar-benarnya berdasarkan kompetensi dan sumpah dokter, serta dipergunakan hanya untuk sesuai keperluan. -------------------------');

        $footerTable = $section->addTable(['width' => 100 * 50, 'unit' => 'pct']);
        $row = $footerTable->addRow();
        $row->addCell(6000);
        $rightCell = $row->addCell(4000);
        $rightCell->addText('Makassar, '.now()->translatedFormat('d F Y'), [], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
        $rightCell->addText('Dokter Pemeriksa', ['bold' => true], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);

        // Generate QR
        $qrData = route('verify-qr', $letterRequest->id);
        $qrUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=150x150&data='.urlencode($qrData);
        $tempFile = tempnam(sys_get_temp_dir(), 'qr_').'.png';
        $qrContent = @file_get_contents($qrUrl);
        if ($qrContent !== false) {
            file_put_contents($tempFile, $qrContent);
        }

        // Foto + QR sejajar dengan QR di tengah (pas di atas nama dokter)
        $mediaTable = $rightCell->addTable([
            'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER,
        ]);

        $mediaRow = $mediaTable->addRow();

        // 1. FOTO KIRI
        $photoCell = $mediaRow->addCell(2000);

        if ($letterRequest->photo_4x6) {
            $photoPath = storage_path('app/public/'.$letterRequest->photo_4x6);
            if (file_exists($photoPath)) {
                $photoCell->addImage($photoPath, [
                    'width' => 70,
                    'height' => 90,
                    'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER,
                ]);
            }
        }

        // 2. QR DI TENGAH
        $qrCell = $mediaRow->addCell(2000);

        if ($qrContent !== false) {
            $qrCell->addImage($tempFile, [
                'width' => 80,
                'height' => 80,
                'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER,
                'spaceAfter' => 0,
                'spaceBefore' => 0,
            ]);
        }

        // 3. SPACER KANAN (agar QR Code pas di tengah-tengah tanda tangan)
        $mediaRow->addCell(2000);

        $paragraphStyle = [
            'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER,
            'spaceAfter' => 0,
            'spaceBefore' => 0,
        ];

        $rightCell->addText(
            $letterRequest->dokterPemeriksa->name ?? 'Dr. dr. IRWAN, Sp.OG., M.Kes',
            [
                'bold' => true,
                'underline' => 'single',
            ],
            $paragraphStyle
        );

        $rightCell->addText(
            'AKBP NRP '.($letterRequest->dokterPemeriksa->nrp ?? '74030679'),
            [
                'bold' => true,
            ],
            $paragraphStyle
        );
    }

    public function generateSkbjWord($section, $letterRequest, $labResults)
    {
        // Header
        $headerTable = $section->addTable(['width' => 100 * 50, 'unit' => 'pct']);
        $row = $headerTable->addRow();
        $cell = $row->addCell(10000);
        $cell->addText('KEPOLISIAN DAERAH SULAWESI SELATAN', ['bold' => true, 'size' => 10]);
        $cell->addText('BIDANG KEDOKTERAN DAN KESEHATAN', ['bold' => true, 'size' => 10]);
        $cell->addText('RUMAH SAKIT BHAYANGKARA TK.II MAKASSAR', ['bold' => true, 'size' => 10]);
        $cell->addText(
            'Jalan. Letjen Pol. Mappaoddang No. 63 Makassar',
            ['italic' => true, 'size' => 9],
            ['borderBottomSize' => 6]
        );

        $section->addTextBreak(1);

        // Logo
        $logoPath = public_path('images/logo-polisi.jpg');
        if (file_exists($logoPath)) {
            $section->addImage($logoPath, [
                'width' => 50,
                'height' => 50,
                'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER,
            ]);
        }

        // Judul
        $section->addText(
            'SURAT KETERANGAN BERBADAN SEHAT / JASMANI',
            [
                'bold' => true,
                'size' => 14,
                'underline' => 'single',
            ],
            [
                'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER,
            ]
        );

        $nomorSurat = $letterRequest->nomor_surat
            ?? ('SKBJ/'.$letterRequest->id.'/'.now()->format('m/Y').'/Rumkit');

        $section->addText(
            'Nomor: '.$nomorSurat,
            [],
            ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]
        );

        $section->addTextBreak(1);

        $section->addText(
            'Yang bertanda tangan di bawah ini, dokter Rumah Sakit Bhayangkara Makassar menerangkan bahwa:'
        );

        $section->addTextBreak(1);

        $table = $section->addTable([
            'width' => 100 * 50,
            'unit' => 'pct',
        ]);

        $this->addTableRow($table, 'Nama', ': '.strtoupper($letterRequest->patient->user->name), true);
        $this->addTableRow($table, 'Pangkat', ': '.($letterRequest->patient->pangkat ?? '-'));
        $this->addTableRow($table, 'NRP / NIP', ': '.($letterRequest->patient->nrp_nip ?? '-'));
        $this->addTableRow($table, 'Jenis Kelamin', ': '.($letterRequest->patient->gender == 'L' ? 'LAKI-LAKI' : 'PEREMPUAN'));
        $this->addTableRow($table, 'Pendidikan Terakhir', ': '.($letterRequest->patient->pendidikan_terakhir ?? '-'));
        $this->addTableRow($table, 'Jabatan / Kesatuan', ': '.($letterRequest->patient->jabatan_kesatuan ?? '-'));
        $this->addTableRow($table, 'Alamat', ': '.$letterRequest->patient->address);

        $section->addTextBreak(1);

        $section->addText(
            'Yang bersangkutan tersebut di atas telah diperiksa dan dinyatakan:'
        );

        $section->addText(
            '==================== BERBADAN SEHAT ====================',
            ['bold' => true],
            ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]
        );

        $section->addTextBreak(1);

        $section->addText(
            'Demikian surat keterangan ini dibuat dipergunakan untuk:'
        );

        $section->addText(
            strtoupper($letterRequest->keperluan ?? 'ASSESMENT JABATAN KAPOLRES'),
            ['bold' => true],
            ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]
        );

        $section->addTextBreak(1);

        // Footer
        $footerTable = $section->addTable([
            'width' => 100 * 50,
            'unit' => 'pct',
        ]);

        $row = $footerTable->addRow();

        // Kiri
        $leftCell = $row->addCell(5000);

        $leftCell->addText(
            'TD : '.($letterRequest->pemeriksaan_data['td'] ?? '120/80').' mmHg',
            ['bold' => true]
        );

        $leftCell->addText(
            'TB : '.($letterRequest->pemeriksaan_data['tb'] ?? '164').' CM',
            ['bold' => true]
        );

        $leftCell->addText(
            'BB : '.($letterRequest->pemeriksaan_data['bb'] ?? '78').' KG',
            ['bold' => true]
        );

        // Kanan
        $rightCell = $row->addCell(5000);

        $rightCell->addText(
            'Makassar, '.now()->translatedFormat('d F Y'),
            [],
            ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]
        );

        $rightCell->addText(
            'Dokter yang memeriksa',
            ['bold' => true],
            ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]
        );

        // Generate QR
        $qrData = route('verify-qr', $letterRequest->id);
        $qrUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=150x150&data='.urlencode($qrData);

        $tempFile = tempnam(sys_get_temp_dir(), 'qr_').'.png';
        $qrContent = @file_get_contents($qrUrl);

        if ($qrContent !== false) {
            file_put_contents($tempFile, $qrContent);
        }

        // Foto + QR sejajar dengan QR di tengah (pas di atas nama dokter)
        $mediaTable = $rightCell->addTable([
            'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER,
        ]);

        $mediaRow = $mediaTable->addRow();

        // 1. FOTO KIRI
        $photoCell = $mediaRow->addCell(2000);

        if ($letterRequest->photo_4x6) {

            $photoPath = storage_path('app/public/'.$letterRequest->photo_4x6);

            if (file_exists($photoPath)) {
                $photoCell->addImage($photoPath, [
                    'width' => 70,
                    'height' => 90,
                    'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER,
                ]);
            }
        }

        // 2. QR DI TENGAH
        $qrCell = $mediaRow->addCell(2000);

        if ($qrContent !== false) {
            $qrCell->addImage($tempFile, [
                'width' => 80,
                'height' => 80,
                'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER,
                'spaceAfter' => 0,
                'spaceBefore' => 0,
            ]);
        }

        // 3. SPACER KANAN (agar QR Code pas di tengah-tengah tanda tangan)
        $mediaRow->addCell(2000);

      

        $paragraphStyle = [
            'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER,
            'spaceAfter' => 0,
            'spaceBefore' => 0,
        ];

        $rightCell->addText(
            $letterRequest->dokterPemeriksa->name ?? 'Dr. dr. IRWAN, Sp.OG., M.Kes',
            [
                'bold' => true,
                'underline' => 'single',
            ],
            $paragraphStyle
        );

        $rightCell->addText(
            'AKBP NRP '.($letterRequest->dokterPemeriksa->nrp ?? '74030679'),
            [
                'bold' => true,
            ],
            $paragraphStyle
        );
    }

    public function verifyQr(LetterRequest $letterRequest)
    {
        if (in_array($letterRequest->status, ['approved', 'completed'])) {
            $letterRequest->load(['patient.user', 'letterType', 'dokterPemeriksa']);

            if ($letterRequest->letterType->slug == 'skbn') {
                return view('public.verify-skbn', compact('letterRequest'));
            } else {
                return view('public.verify-skbj', compact('letterRequest'));
            }
        } else {
            return abort(404);
        }

    }

    public function addTableRow($table, $label, $value, $bold = false)
    {
        $row = $table->addRow();
        $row->addCell(3000)->addText($label, ['bold' => $bold]);
        $row->addCell(7000)->addText($value, ['bold' => $bold]);
    }
}
