<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

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
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('nrp', 'like', "%{$search}%")
                    ->orWhere('address', 'like', "%{$search}%");
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
            'nrp' => 'nullable|string|max:50',
            'address' => 'nullable|string',
            'sip_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($request->hasFile('sip_file')) {
            $validated['sip_file'] = $request->file('sip_file')->store('sip', 'public');
        }

        $validated['role'] = 'dokter';

        User::create($validated);

        return redirect()->route('admin.dokters.index')->with('success', 'Data dokter berhasil ditambahkan.');
    }

    public function edit(User $dokter)
    {
        if ($dokter->role !== 'dokter') {
            abort(404);
        }

        return view('admin.dokters.edit', compact('dokter'));
    }

    public function update(Request $request, User $dokter)
    {
        if ($dokter->role !== 'dokter') {
            abort(404);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => [
                'required',
                'string',
                'max:255',
                Rule::unique('users')->ignore($dokter->id),
            ],
            'phone' => 'nullable|string|max:20',
            'nrp' => 'nullable|string|max:50',
            'address' => 'nullable|string',
            'sip_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        if ($request->hasFile('sip_file')) {
            if ($dokter->sip_file) {
                Storage::disk('public')->delete($dokter->sip_file);
            }
            $validated['sip_file'] = $request->file('sip_file')->store('sip', 'public');
        }

        if (empty($validated['password'])) {
            unset($validated['password']);
        }

        $dokter->update($validated);

        return redirect()->route('admin.dokters.index')->with('success', 'Data dokter berhasil diperbarui.');
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
