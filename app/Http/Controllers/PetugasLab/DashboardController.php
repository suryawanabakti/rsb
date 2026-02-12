<?php

namespace App\Http\Controllers\PetugasLab;

use App\Http\Controllers\Controller;
use App\Models\LabResult;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $stats = [
            'total_inputted' => LabResult::where('inputted_by', $user->id)->count(),
            'pending_validation' => LabResult::where('inputted_by', $user->id)->where('status', 'pending')->count(),
            'validated' => LabResult::where('inputted_by', $user->id)->where('status', 'validated')->count(),
        ];

        $recentResults = LabResult::with(['patient.user', 'validator'])
            ->where('inputted_by', $user->id)
            ->latest()
            ->take(5)
            ->get();

        return view('petugas-lab.dashboard', compact('stats', 'recentResults'));
    }
}
