<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DoctorSchedule;
use App\Models\User;
use Illuminate\Http\Request;

class DoctorScheduleController extends Controller
{
    public function index()
    {
        $schedules = DoctorSchedule::with('doctor')->latest()->paginate(15);
        return view('admin.doctor-schedules.index', compact('schedules'));
    }

    public function create()
    {
        $doctors = User::where('role', 'dokter')->get();
        return view('admin.doctor-schedules.create', compact('doctors'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'doctor_id' => 'required|exists:users,id',
            'day_of_week' => 'required|in:senin,selasa,rabu,kamis,jumat,sabtu,minggu',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'room' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ]);

        if (!$request->has('is_active')) {
            $validated['is_active'] = false;
        }

        DoctorSchedule::create($validated);

        return redirect()->route('admin.doctor-schedules.index')
            ->with('success', 'Jadwal dokter berhasil ditambahkan.');
    }

    public function edit(DoctorSchedule $doctorSchedule)
    {
        $doctors = User::where('role', 'dokter')->get();
        return view('admin.doctor-schedules.edit', compact('doctorSchedule', 'doctors'));
    }

    public function update(Request $request, DoctorSchedule $doctorSchedule)
    {
        $validated = $request->validate([
            'doctor_id' => 'required|exists:users,id',
            'day_of_week' => 'required|in:senin,selasa,rabu,kamis,jumat,sabtu,minggu',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'room' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ]);

        if (!$request->has('is_active')) {
            $validated['is_active'] = false;
        }

        $doctorSchedule->update($validated);

        return redirect()->route('admin.doctor-schedules.index')
            ->with('success', 'Jadwal dokter berhasil diperbarui.');
    }

    public function destroy(DoctorSchedule $doctorSchedule)
    {
        $doctorSchedule->delete();
        return redirect()->route('admin.doctor-schedules.index')
            ->with('success', 'Jadwal dokter berhasil dihapus.');
    }
}
