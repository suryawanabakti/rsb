<?php

namespace App\Http\Controllers\Pimpinan;

use App\Http\Controllers\Controller;
use App\Models\LetterRequest;
use App\Models\LabResult;
use App\Models\Patient;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_patients' => Patient::count(),
            'total_letter_requests' => LetterRequest::count(),
            'pending_letter_requests' => LetterRequest::where('status', 'pending')->count(),
            'total_lab_results' => LabResult::count(),
            'validated_lab_results' => LabResult::where('status', 'validated')->count(),
        ];

        $recentRequests = LetterRequest::with(['patient.user', 'letterType'])
            ->latest()
            ->take(5)
            ->get();

        $recentLabResults = LabResult::with(['patient.user'])
            ->latest()
            ->take(5)
            ->get();

        return view('pimpinan.dashboard', compact('stats', 'recentRequests', 'recentLabResults'));
    }
}
