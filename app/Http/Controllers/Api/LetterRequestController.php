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
}
