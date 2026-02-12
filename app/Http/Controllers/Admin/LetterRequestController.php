<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LetterRequest;
use Illuminate\Http\Request;

class LetterRequestController extends Controller
{
    public function index(Request $request)
    {
        $query = LetterRequest::with(['patient.user', 'letterType']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('patient.user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('username', 'like', "%{$search}%");
            })->orWhereHas('patient', function ($q) use ($search) {
                $q->where('nik', 'like', "%{$search}%");
            });
        }

        $requests = $query->latest()->paginate(10);

        return view('admin.letter-requests.index', compact('requests'));
    }

    public function show(LetterRequest $letterRequest)
    {
        $letterRequest->load(['patient.user', 'letterType', 'files']);
        return view('admin.letter-requests.show', compact('letterRequest'));
    }

    public function updateStatus(Request $request, LetterRequest $letterRequest)
    {
        $request->validate([
            'status' => 'required|in:verified,approved,rejected,completed',
            'admin_notes' => 'nullable|string',
        ]);

        $letterRequest->update([
            'status' => $request->status,
            'admin_notes' => $request->admin_notes,
            'processed_at' => now(),
            'processed_by' => auth()->id(),
        ]);

        // Send notification to patient
        $letterRequest->patient->user->notify(new \App\Notifications\LetterRequestStatusChanged($letterRequest));

        return back()->with('success', 'Status permohonan berhasil diperbarui.');
    }
}
