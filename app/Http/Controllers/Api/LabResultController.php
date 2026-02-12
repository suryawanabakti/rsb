<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LabResult;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class LabResultController extends Controller
{
    /**
     * List lab results.
     * - petugas_lab: all results they inputted
     * - dokter: all results (for validation)
     * - pasien: own results only
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        $query = LabResult::with(['patient.user', 'inputter', 'validator']);

        if ($user->role === 'pasien') {
            $patient = $user->patient;
            if (!$patient) {
                return response()->json(['message' => 'Data pasien tidak ditemukan'], 404);
            }
            $query->where('patient_id', $patient->id);
        } elseif ($user->role === 'petugas_lab') {
            $query->where('inputted_by', $user->id);
        }
        // dokter sees all

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $results = $query->latest()->paginate(15);

        return response()->json(['data' => $results]);
    }

    /**
     * Store a new lab result (petugas_lab only).
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'letter_request_id' => 'nullable|exists:letter_requests,id',
            'test_name' => 'required|string|max:255',
            'test_date' => 'required|date',
            'result_data' => 'required|array',
            'result_data.*.name' => 'required|string',
            'result_data.*.value' => 'required|string',
            'result_data.*.unit' => 'nullable|string',
            'result_data.*.normal_range' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $labResult = LabResult::create([
            'patient_id' => $request->patient_id,
            'letter_request_id' => $request->letter_request_id,
            'test_name' => $request->test_name,
            'test_date' => $request->test_date,
            'result_data' => $request->result_data,
            'notes' => $request->notes,
            'status' => 'pending',
            'inputted_by' => $request->user()->id,
        ]);

        // Notify patient
        $labResult->patient->user->notify(new \App\Notifications\LabResultStatusUpdated($labResult, 'created'));

        // Notify all doctors
        $doctors = \App\Models\User::where('role', 'dokter')->get();
        \Illuminate\Support\Facades\Notification::send($doctors, new \App\Notifications\NewLabResultAdded($labResult));

        return response()->json([
            'message' => 'Hasil pemeriksaan berhasil disimpan',
            'data' => $labResult->load(['patient.user', 'inputter']),
        ], 201);
    }

    /**
     * Show a lab result detail.
     */
    public function show(Request $request, int $id): JsonResponse
    {
        $user = $request->user();
        $query = LabResult::with(['patient.user', 'inputter', 'validator', 'letterRequest']);

        if ($user->role === 'pasien') {
            $patient = $user->patient;
            if (!$patient) {
                return response()->json(['message' => 'Data pasien tidak ditemukan'], 404);
            }
            $query->where('patient_id', $patient->id);
        }

        $labResult = $query->find($id);

        if (!$labResult) {
            return response()->json(['message' => 'Hasil pemeriksaan tidak ditemukan'], 404);
        }

        return response()->json(['data' => $labResult]);
    }

    /**
     * Validate a lab result (dokter only).
     */
    public function validateResult(Request $request, int $id): JsonResponse
    {
        $labResult = LabResult::find($id);

        if (!$labResult) {
            return response()->json(['message' => 'Hasil pemeriksaan tidak ditemukan'], 404);
        }

        if ($labResult->status === 'validated') {
            return response()->json(['message' => 'Hasil pemeriksaan sudah divalidasi'], 422);
        }

        $labResult->update([
            'status' => 'validated',
            'validated_by' => $request->user()->id,
            'validated_at' => now(),
        ]);

        // Notify patient
        $labResult->patient->user->notify(new \App\Notifications\LabResultStatusUpdated($labResult, 'validated'));

        return response()->json([
            'message' => 'Hasil pemeriksaan berhasil divalidasi',
            'data' => $labResult->load(['patient.user', 'inputter', 'validator']),
        ]);
    }

    /**
     * Get list of patients for petugas lab to select when inputting.
     */
    public function patients(Request $request): JsonResponse
    {
        $search = $request->search;

        $patients = Patient::with('user')
            ->when($search, function ($q) use ($search) {
                $q->where('nik', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    });
            })
            ->latest()
            ->paginate(20);

        return response()->json(['data' => $patients]);
    }
}
