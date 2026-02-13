<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $rules = [
            'name' => 'required|string|max:100',
            'phone' => 'nullable|string|max:15',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'password' => 'nullable|string|min:8|confirmed',
        ];

        if ($user->role === 'pasien') {
            $rules = array_merge($rules, [
                'nik' => 'required|string|max:20|unique:patients,nik,' . ($user->patient->id ?? 0),
                'address' => 'required|string',
                'birth_date' => 'required|date',
                'gender' => 'required|in:L,P',
            ]);
        }

        $request->validate($rules);

        $data = $request->only(['name', 'phone']);

        if ($request->filled('password')) {
            $data['password'] = $request->password;
        }

        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($user->photo) {
                Storage::disk('public')->delete($user->photo);
            }

            $path = $request->file('photo')->store('profile-photos', 'public');
            $data['photo'] = $path;
        }

        $user->update($data);

        if ($user->role === 'pasien') {
            $user->patient()->update($request->only(['nik', 'address', 'birth_date', 'gender']));
        }

        return back()->with('success', 'Profil berhasil diperbarui.');
    }
}
