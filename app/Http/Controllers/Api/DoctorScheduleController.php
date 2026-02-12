<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DoctorSchedule;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class DoctorScheduleController extends Controller
{
    /**
     * Get the authenticated doctor's schedule.
     */
    public function index(Request $request): JsonResponse
    {
        $schedules = DoctorSchedule::where('doctor_id', $request->user()->id)
            ->where('is_active', true)
            ->orderByRaw("FIELD(day_of_week, 'senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu', 'minggu')")
            ->get();

        return response()->json(['data' => $schedules]);
    }
}
