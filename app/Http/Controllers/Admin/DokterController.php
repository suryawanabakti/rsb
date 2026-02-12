<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DokterController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'dokter');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('username', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $dokters = $query->latest()->paginate(15);

        return view('admin.dokters.index', compact('dokters'));
    }

    public function create()
    {
        return view('admin.dokters.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $validated['role'] = 'dokter';

        User::create($validated);

        return redirect()->route('admin.dokters.index')->with('success', 'Data dokter berhasil ditambahkan.');
    }

    public function destroy(User $dokter)
    {
        if ($dokter->role !== 'dokter') {
            abort(404);
        }

        $dokter->delete();

        return redirect()->route('admin.dokters.index')->with('success', 'Data dokter berhasil dihapus.');
    }
}
