<?php

namespace App\Http\Controllers\Pasien;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $patient = $user->patient;

        if (!$patient) {
            // Handle case where user has 'pasien' role but no patient record
            // This ideally shouldn't happen if seeded correctly, but good for safety
            return view('pasien.dashboard', [
                'stats' => [
                    'total' => 0,
                    'pending' => 0,
                    'processed' => 0,
                    'completed' => 0,
                ],
                'recentRequests' => collect([]),
            ]);
        }

        $requests = $patient->letterRequests()->latest()->get();

        $stats = [
            'total' => $requests->count(),
            'pending' => $requests->whereIn('status', ['submitted', 'pending'])->count(),
            'processed' => $requests->where('status', 'approved')->count(), // Assuming 'approved' means processed by admin/verified
            'completed' => $requests->where('status', 'completed')->count(),
        ];

        return view('pasien.dashboard', [
            'stats' => $stats,
            'recentRequests' => $requests->take(5),
            'patient' => $patient
        ]);
    }
}
