<?php

namespace App\Http\Controllers\Dokter;

use App\Http\Controllers\Controller;
use App\Models\LabResult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LabResultController extends Controller
{
    public function index(Request $request)
    {
        $query = LabResult::with(['patient.user', 'inputter', 'validator']);

        if ($request->get('status') === 'pending') {
            $query->where('status', 'pending');
        } elseif ($request->get('status') === 'validated') {
            $query->where('status', 'validated');
        }

        $results = $query->latest()->paginate(15);
        $currentFilter = $request->get('status', 'all');

        return view('dokter.lab-results.index', compact('results', 'currentFilter'));
    }

    public function show($id)
    {
        $result = LabResult::with(['patient.user', 'inputter', 'validator'])->findOrFail($id);

        return view('dokter.lab-results.show', compact('result'));
    }

    public function validate(Request $request, $id)
    {
        $result = LabResult::where('status', 'pending')->findOrFail($id);

        $result->update([
            'status' => 'validated',
            'validated_by' => Auth::id(),
            'validated_at' => now(),
        ]);

        // Notify patient
        $result->patient->user->notify(new \App\Notifications\LabResultStatusUpdated($result, 'validated'));

        return redirect()->route('dokter.lab-results.index')
            ->with('success', 'Hasil pemeriksaan berhasil divalidasi.');
    }
}
