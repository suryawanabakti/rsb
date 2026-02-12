<?php

namespace App\Http\Controllers\PetugasLab;

use App\Http\Controllers\Controller;
use App\Models\LabResult;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LabResultController extends Controller
{
    public function index()
    {
        $results = LabResult::with(['patient.user', 'validator'])
            ->where('inputted_by', Auth::id())
            ->latest()
            ->paginate(15);

        return view('petugas-lab.lab-results.index', compact('results'));
    }

    public function create()
    {
        return view('petugas-lab.lab-results.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'test_name' => 'required|string|max:255',
            'test_date' => 'required|date',
            'result_data' => 'required|array|min:1',
            'result_data.*.name' => 'required|string',
            'result_data.*.value' => 'required|string',
            'result_data.*.unit' => 'nullable|string',
            'result_data.*.normal_range' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $labResult = LabResult::create([
            'patient_id' => $validated['patient_id'],
            'test_name' => $validated['test_name'],
            'test_date' => $validated['test_date'],
            'result_data' => $validated['result_data'],
            'notes' => $validated['notes'] ?? null,
            'status' => 'pending',
            'inputted_by' => Auth::id(),
        ]);

        // Notify patient
        $labResult->patient->user->notify(new \App\Notifications\LabResultStatusUpdated($labResult, 'created'));

        // Notify all doctors
        $doctors = \App\Models\User::where('role', 'dokter')->get();
        \Illuminate\Support\Facades\Notification::send($doctors, new \App\Notifications\NewLabResultAdded($labResult));

        return redirect()->route('petugas-lab.lab-results.index')
            ->with('success', 'Hasil pemeriksaan berhasil disimpan.');
    }

    public function edit($id)
    {
        $result = LabResult::with('patient.user')->where('inputted_by', Auth::id())->where('status', 'pending')->findOrFail($id);
        return view('petugas-lab.lab-results.edit', compact('result'));
    }

    public function update(Request $request, $id)
    {
        $result = LabResult::where('inputted_by', Auth::id())->where('status', 'pending')->findOrFail($id);

        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'test_name' => 'required|string|max:255',
            'test_date' => 'required|date',
            'result_data' => 'required|array|min:1',
            'result_data.*.name' => 'required|string',
            'result_data.*.value' => 'required|string',
            'result_data.*.unit' => 'nullable|string',
            'result_data.*.normal_range' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $result->update([
            'patient_id' => $validated['patient_id'],
            'test_name' => $validated['test_name'],
            'test_date' => $validated['test_date'],
            'result_data' => $validated['result_data'],
            'notes' => $validated['notes'] ?? null,
        ]);

        return redirect()->route('petugas-lab.lab-results.index')
            ->with('success', 'Hasil pemeriksaan berhasil diperbarui.');
    }

    public function show($id)
    {
        $result = LabResult::with(['patient.user', 'inputter', 'validator'])
            ->where('inputted_by', Auth::id())
            ->findOrFail($id);

        return view('petugas-lab.lab-results.show', compact('result'));
    }

    public function searchPatients(Request $request)
    {
        $search = $request->get('q');

        $query = Patient::with('user');

        if ($search) {
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            })->orWhere('nik', 'like', "%{$search}%");
        } else {
            // Return recent patients if no search query
            $query->latest()->take(10);
        }

        return response()->json($query->limit(20)->get());
    }
}
