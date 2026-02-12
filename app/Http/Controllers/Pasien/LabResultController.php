<?php

namespace App\Http\Controllers\Pasien;

use App\Http\Controllers\Controller;
use App\Models\LabResult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LabResultController extends Controller
{
    public function index()
    {
        $patient = Auth::user()->patient;

        if (!$patient) {
            return redirect()->route('pasien.dashboard')->with('error', 'Data pasien tidak ditemukan.');
        }

        $results = LabResult::where('patient_id', $patient->id)
            ->with(['validator'])
            ->latest()
            ->paginate(10);

        return view('pasien.lab-results.index', compact('results'));
    }

    public function show($id)
    {
        $patient = Auth::user()->patient;

        if (!$patient) {
            abort(404);
        }

        $labResult = LabResult::where('id', $id)
            ->where('patient_id', $patient->id)
            ->with(['validator', 'inputter'])
            ->firstOrFail();

        return view('pasien.lab-results.show', compact('labResult'));
    }
}
