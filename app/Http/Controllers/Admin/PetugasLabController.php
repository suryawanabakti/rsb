<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class PetugasLabController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'petugas_lab');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('username', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $petugasLabs = $query->latest()->paginate(15);

        return view('admin.petugas-lab.index', compact('petugasLabs'));
    }

    public function create()
    {
        return view('admin.petugas-lab.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $validated['role'] = 'petugas_lab';

        User::create($validated);

        return redirect()->route('admin.petugas-labs.index')->with('success', 'Data petugas lab berhasil ditambahkan.');
    }

    public function destroy(User $petugasLab)
    {
        if ($petugasLab->role !== 'petugas_lab') {
            abort(404);
        }

        $petugasLab->delete();

        return redirect()->route('admin.petugas-labs.index')->with('success', 'Data petugas lab berhasil dihapus.');
    }
}
