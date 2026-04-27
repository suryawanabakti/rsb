<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LetterType;
use Illuminate\Http\Request;

class LetterTypeController extends Controller
{
    public function index()
    {
        $types = LetterType::latest()->get();
        return view('admin.letter-types.index', compact('types'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:100',
            'slug' => 'nullable|string|max:100|unique:letter_types',
            'description' => 'nullable|string',
            'is_active' => 'sometimes|boolean',
        ]);

        $data['is_active'] = $request->has('is_active');
        $data['slug'] = $data['slug'] ?? \Illuminate\Support\Str::slug($data['name']);

        LetterType::create($data);

        return back()->with('success', 'Jenis surat berhasil ditambahkan.');
    }

    public function update(Request $request, LetterType $letterType)
    {
        $data = $request->validate([
            'name' => 'required|string|max:100',
            'slug' => 'nullable|string|max:100|unique:letter_types,slug,' . $letterType->id,
            'description' => 'nullable|string',
            'is_active' => 'sometimes|boolean',
        ]);

        $data['is_active'] = $request->has('is_active');
        $data['slug'] = $data['slug'] ?? \Illuminate\Support\Str::slug($data['name']);

        $letterType->update($data);

        return back()->with('success', 'Jenis surat berhasil diperbarui.');
    }

    public function destroy(LetterType $letterType)
    {
        $letterType->delete();
        return back()->with('success', 'Jenis surat berhasil dihapus.');
    }
}
