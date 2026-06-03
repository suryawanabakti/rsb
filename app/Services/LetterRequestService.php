<?php

namespace App\Services;

use App\Models\LetterRequest;
use App\Models\RequestFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class LetterRequestService
{
    public function createRequest(int $patientId, array $data): LetterRequest
    {
        return DB::transaction(function () use ($patientId, $data) {
            $letterRequest = LetterRequest::create([
                'patient_id' => $patientId,
                'letter_type_id' => $data['letter_type_id'],
                'keperluan' => $data['keperluan'] ?? null,
                'notes' => $data['notes'] ?? null,
                'submission_date' => now(),
                'status' => 'submitted',
            ]);

            if (isset($data['files'])) {
                foreach ($data['files'] as $file) {
                    $path = $file->store('letter-attachments', 'public');

                    RequestFile::create([
                        'letter_request_id' => $letterRequest->id,
                        'file_name' => $file->getClientOriginalName(),
                        'file_path' => $path,
                        'mime_type' => $file->getMimeType(),
                        'file_size' => $file->getSize(),
                    ]);
                }
            }

            return $letterRequest->load('letterType', 'files');
        });
    }

    public function getPatientRequests(int $patientId)
    {
        return LetterRequest::where('patient_id', $patientId)
            ->with('letterType', 'files')
            ->latest()
            ->paginate(15);
    }

    public function getRequestDetails(int $id, int $patientId): ?LetterRequest
    {
        return LetterRequest::where('id', $id)
            ->where('patient_id', $patientId)
            ->with(['letterType', 'files', 'processor', 'dokterPemeriksa'])
            ->first();
    }
}
