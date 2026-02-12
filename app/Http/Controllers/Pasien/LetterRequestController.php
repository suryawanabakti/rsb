<?php

namespace App\Http\Controllers\Pasien;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pasien\StoreLetterRequest;
use App\Models\LetterType;
use App\Services\LetterRequestService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LetterRequestController extends Controller
{
    public function __construct(
        protected LetterRequestService $letterRequestService
    ) {}

    public function index()
    {
        $patient = Auth::user()->patient;
        $requests = $this->letterRequestService->getPatientRequests($patient->id);

        return view('pasien.letter-requests.index', compact('requests'));
    }

    public function create()
    {
        $letterTypes = LetterType::where('is_active', true)->get();
        return view('pasien.letter-requests.create', compact('letterTypes'));
    }

    public function store(StoreLetterRequest $request)
    {
        $patient = Auth::user()->patient;

        try {
            $this->letterRequestService->createRequest($patient->id, $request->validated());

            return redirect()
                ->route('pasien.letter-requests.index')
                ->with('success', 'Permohonan surat berhasil dikirim.');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat mengirim permohonan. Silakan coba lagi.');
        }
    }

    public function show($id)
    {
        $patient = Auth::user()->patient;
        $letterRequest = $this->letterRequestService->getRequestDetails($id, $patient->id);

        if (!$letterRequest) {
            abort(404);
        }

        return view('pasien.letter-requests.show', compact('letterRequest'));
    }
}
