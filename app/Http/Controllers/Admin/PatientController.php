<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    public function index(Request $request)
    {
        $query = Patient::with('user');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('username', 'like', "%{$search}%");
            })->orWhere('nik', 'like', "%{$search}%");
        }

        $patients = $query->latest()->paginate(15);

        return view('admin.patients.index', compact('patients'));
    }

    public function show(Patient $patient)
    {
        $patient->load(['user', 'letterRequests.letterType']);
        return view('admin.patients.show', compact('patient'));
    }
}
