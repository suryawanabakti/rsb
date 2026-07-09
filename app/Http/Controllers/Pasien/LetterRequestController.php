<?php

namespace App\Http\Controllers\Pasien;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pasien\StoreLetterRequest;
use App\Models\LetterType;
use App\Services\LetterRequestService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LetterRequestController extends Controller
{
    public function __construct(
        protected LetterRequestService $letterRequestService
    ) {}

    public function index()
    {
        $patient = Auth::user()->patient;
        $requests = $this->letterRequestService->getPatientRequests($patient->id);

        return view('pasien.letter-requests.index', compact('requests'));
    }

    public function create()
    {
        $letterTypes = LetterType::where('is_active', true)->get();
        return view('pasien.letter-requests.create', compact('letterTypes'));
    }

    public function store(StoreLetterRequest $request)
    {
        $patient = Auth::user()->patient;

        try {
            $this->letterRequestService->createRequest($patient->id, $request->validated());

            return redirect()
                ->route('pasien.letter-requests.index')
                ->with('success', 'Permohonan surat berhasil dikirim.');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat mengirim permohonan. Silakan coba lagi.');
        }
    }

    public function show($id)
    {
        $patient = Auth::user()->patient;
        $letterRequest = $this->letterRequestService->getRequestDetails($id, $patient->id);

        if (!$letterRequest) {
            abort(404);
        }

        return view('pasien.letter-requests.show', compact('letterRequest'));
    }

    public function downloadWord($id)
    {
        $patient = Auth::user()->patient;
        $letterRequest = $this->letterRequestService->getRequestDetails($id, $patient->id);

        if (!$letterRequest) {
            abort(404);
        }

        $letterRequest->load(['patient.user', 'letterType', 'dokterPemeriksa']);

        $labResults = \App\Models\LabResult::where('letter_request_id', $letterRequest->id)
            ->orWhere('patient_id', $letterRequest->patient_id)
            ->with('validator')
            ->latest()
            ->first();

        $adminController = app(\App\Http\Controllers\Admin\LetterRequestController::class);

        $phpWord = new \PhpOffice\PhpWord\PhpWord();
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
            $adminController->generateSkbnWord($section, $letterRequest, $labResults);
        } else {
            $adminController->generateSkbjWord($section, $letterRequest, $labResults);
        }

        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        $fileName = 'Surat_' . strtoupper($letterRequest->letterType->slug) . '_' . str_replace(' ', '_', $letterRequest->patient->user->name) . '.docx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        header('Content-Disposition: attachment;filename="' . $fileName . '"');
        header('Cache-Control: max-age=0');

        $objWriter->save('php://output');
        exit;
    }

    public function downloadPdf($id)
    {
        $patient = Auth::user()->patient;
        $letterRequest = $this->letterRequestService->getRequestDetails($id, $patient->id);

        if (!$letterRequest) {
            abort(404);
        }

        $letterRequest->load(['patient.user', 'letterType', 'dokterPemeriksa']);

        $labResults = \App\Models\LabResult::where('letter_request_id', $letterRequest->id)
            ->orWhere('patient_id', $letterRequest->patient_id)
            ->with('validator')
            ->latest()
            ->first();

        $slug = $letterRequest->letterType->slug;
        $view = $slug === 'skbn' ? 'pasien.letter-requests.pdf.skbn' : 'pasien.letter-requests.pdf.skbj';

        $pdf = Pdf::loadView($view, compact('letterRequest', 'labResults'));

        $fileName = 'Surat_' . strtoupper($slug) . '_' . str_replace(' ', '_', $letterRequest->patient->user->name) . '.pdf';

        return $pdf->stream($fileName);
    }
}
