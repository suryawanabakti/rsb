<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreLetterRequest;
use App\Models\LetterType;
use App\Services\LetterRequestService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class LetterRequestController extends Controller
{
    public function __construct(
        protected LetterRequestService $letterRequestService
    ) {}

    public function index(Request $request): JsonResponse
    {
        $patient = $request->user()->patient;

        if (!$patient) {
            return response()->json(['message' => 'Data pasien tidak ditemukan'], 404);
        }

        $requests = $this->letterRequestService->getPatientRequests($patient->id);

        return response()->json([
            'data' => $requests
        ]);
    }

    public function store(StoreLetterRequest $request): JsonResponse
    {
        $patient = $request->user()->patient;

        if (!$patient) {
            return response()->json(['message' => 'Data pasien tidak ditemukan'], 404);
        }

        $letterRequest = $this->letterRequestService->createRequest($patient->id, $request->validated());

        return response()->json([
            'message' => 'Permohonan berhasil diajukan',
            'data' => $letterRequest
        ], 201);
    }

    public function show(Request $request, int $id): JsonResponse
    {
        $patient = $request->user()->patient;

        if (!$patient) {
            return response()->json(['message' => 'Data pasien tidak ditemukan'], 404);
        }

        $letterRequest = $this->letterRequestService->getRequestDetails($id, $patient->id);

        if (!$letterRequest) {
            return response()->json(['message' => 'Permohonan tidak ditemukan'], 404);
        }

        return response()->json([
            'data' => $letterRequest
        ]);
    }

    public function types(): JsonResponse
    {
        $types = LetterType::where('is_active', true)->get();

        return response()->json([
            'data' => $types
        ]);
    }

    public function downloadWord(Request $request, int $id)
    {
        $patient = $request->user()->patient;

        if (!$patient) {
            return response()->json(['message' => 'Data pasien tidak ditemukan'], 404);
        }

        $letterRequest = $this->letterRequestService->getRequestDetails($id, $patient->id);

        if (!$letterRequest) {
            return response()->json(['message' => 'Permohonan tidak ditemukan'], 404);
        }

        if ($letterRequest->status !== 'completed') {
            return response()->json(['message' => 'Permohonan belum selesai'], 403);
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

        ob_start();
        $objWriter->save('php://output');
        $content = ob_get_clean();

        return response($content, 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
            'Content-Length' => strlen($content),
        ]);
    }
}
