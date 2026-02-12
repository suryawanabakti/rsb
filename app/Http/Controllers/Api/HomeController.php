<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LabResult;
use App\Models\LetterRequest;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class HomeController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        $patient = $user->patient;

        // Stats for pasien
        $stats = [
            'total' => $patient ? LetterRequest::where('patient_id', $patient->id)->count() : 0,
            'pending' => $patient ? LetterRequest::where('patient_id', $patient->id)->where('status', 'submitted')->count() : 0,
            'completed' => $patient ? LetterRequest::where('patient_id', $patient->id)->where('status', 'completed')->count() : 0,
            'lab_results' => $patient ? LabResult::where('patient_id', $patient->id)->where('status', 'validated')->count() : 0,
        ];

        // Recent requests
        $recentRequests = $patient
            ? LetterRequest::with('letterType')
            ->where('patient_id', $patient->id)
            ->latest()
            ->take(5)
            ->get()
            : [];

        return response()->json([
            'data' => [
                'stats' => $stats,
                'recent_requests' => $recentRequests,
            ],
        ]);
    }
}
