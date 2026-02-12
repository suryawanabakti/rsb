<?php

namespace App\Http\Controllers\Dokter;

use App\Http\Controllers\Controller;
use App\Models\DoctorSchedule;
use App\Models\LabResult;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $stats = [
            'pending_validation' => LabResult::where('status', 'pending')->count(),
            'validated_by_me' => LabResult::where('validated_by', $user->id)->count(),
            'total_results' => LabResult::count(),
        ];

        // Today's day
        $days = ['minggu', 'senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu'];
        $today = $days[now()->dayOfWeek];

        $todaySchedules = DoctorSchedule::where('doctor_id', $user->id)
            ->where('day_of_week', $today)
            ->where('is_active', true)
            ->get();

        $pendingResults = LabResult::with(['patient.user', 'inputter'])
            ->where('status', 'pending')
            ->latest()
            ->take(5)
            ->get();

        return view('dokter.dashboard', compact('stats', 'todaySchedules', 'pendingResults'));
    }
}
