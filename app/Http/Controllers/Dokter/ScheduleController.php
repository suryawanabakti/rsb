<?php

namespace App\Http\Controllers\Dokter;

use App\Http\Controllers\Controller;
use App\Models\DoctorSchedule;
use Illuminate\Support\Facades\Auth;

class ScheduleController extends Controller
{
    public function index()
    {
        $schedules = DoctorSchedule::where('doctor_id', Auth::id())
            ->where('is_active', true)
            ->orderByRaw("FIELD(day_of_week, 'senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu', 'minggu')")
            ->get()
            ->groupBy('day_of_week');

        // Today's day
        $days = ['minggu', 'senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu'];
        $today = $days[now()->dayOfWeek];

        return view('dokter.schedules.index', compact('schedules', 'today'));
    }
}
