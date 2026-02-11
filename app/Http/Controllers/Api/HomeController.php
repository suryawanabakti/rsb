<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LetterRequest;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class HomeController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $patient = $request->user()->patient;

        if (!$patient) {
            return response()->json([
                'message' => 'Data pasien tidak ditemukan'
            ], 404);
        }

        $recentRequests = LetterRequest::where('patient_id', $patient->id)
            ->with('letterType')
            ->latest()
            ->take(5)
            ->get();

        $stats = [
            'total_requests' => LetterRequest::where('patient_id', $patient->id)->count(),
            'pending_requests' => LetterRequest::where('patient_id', $patient->id)
                ->whereIn('status', ['submitted', 'verified'])
                ->count(),
            'completed_requests' => LetterRequest::where('patient_id', $patient->id)
                ->where('status', 'completed')
                ->count(),
        ];

        return response()->json([
            'data' => [
                'user' => $request->user(),
                'patient' => $patient,
                'stats' => $stats,
                'recent_requests' => $recentRequests
            ]
        ]);
    }
}
