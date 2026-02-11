<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LetterRequest;
use App\Models\Patient;
use App\Models\LetterType;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_patients' => Patient::count(),
            'pending_requests' => LetterRequest::whereIn('status', ['submitted', 'verified'])->count(),
            'approved_requests' => LetterRequest::where('status', 'approved')->count(),
            'completed_requests' => LetterRequest::where('status', 'completed')->count(),
        ];

        $recentRequests = LetterRequest::with(['patient.user', 'letterType'])
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentRequests'));
    }
}
