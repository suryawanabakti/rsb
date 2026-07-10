<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

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

    public function edit(Patient $patient)
    {
        $patient->load('user');
        return view('admin.patients.edit', compact('patient'));
    }

    public function update(Request $request, Patient $patient)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => [
                'required',
                'string',
                'max:255',
                Rule::unique('users')->ignore($patient->user->id),
            ],
            'phone' => 'nullable|string|max:20',
            'nik' => [
                'required',
                'string',
                'max:20',
                Rule::unique('patients')->ignore($patient->id),
            ],
            'gender' => 'required|in:L,P',
            'birth_date' => 'required|date',
            'address' => 'required|string',
            'pangkat' => 'nullable|string|max:255',
            'nrp_nip' => 'nullable|string|max:50',
            'pendidikan_terakhir' => 'nullable|string|max:255',
            'jabatan_kesatuan' => 'nullable|string|max:255',
        ]);

        $patient->user->update([
            'name' => $validated['name'],
            'username' => $validated['username'],
            'phone' => $validated['phone'],
        ]);

        $patient->update([
            'nik' => $validated['nik'],
            'gender' => $validated['gender'],
            'birth_date' => $validated['birth_date'],
            'address' => $validated['address'],
            'pangkat' => $validated['pangkat'],
            'nrp_nip' => $validated['nrp_nip'],
            'pendidikan_terakhir' => $validated['pendidikan_terakhir'],
            'jabatan_kesatuan' => $validated['jabatan_kesatuan'],
        ]);

        return redirect()->route('admin.patients.show', $patient->id)
            ->with('success', 'Data pasien berhasil diperbarui.');
    }

    public function changePassword(Patient $patient)
    {
        $patient->load('user');
        return view('admin.patients.change-password', compact('patient'));
    }

    public function updatePassword(Request $request, Patient $patient)
    {
        $validated = $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        $patient->user->update([
            'password' => $validated['password'],
        ]);

        return redirect()->route('admin.patients.show', $patient->id)
            ->with('success', 'Password pasien berhasil diperbarui.');
    }

    public function updateExtraInfo(Request $request, Patient $patient)
    {
        $validated = $request->validate([
            'pangkat' => 'nullable|string|max:255',
            'nrp_nip' => 'nullable|string|max:50',
            'pendidikan_terakhir' => 'nullable|string|max:255',
            'jabatan_kesatuan' => 'nullable|string|max:255',
            'address' => 'required|string',
        ]);

        $patient->update($validated);

        return back()->with('success', 'Informasi tambahan pasien berhasil diperbarui.');
    }
}
