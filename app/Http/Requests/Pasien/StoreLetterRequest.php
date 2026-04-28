<?php

namespace App\Http\Requests\Pasien;

use Illuminate\Foundation\Http\FormRequest;

class StoreLetterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'letter_type_id' => 'required|exists:letter_types,id',
            'keperluan' => 'required|string|max:255',
            'files' => 'nullable|array',
            'files.*' => 'file|mimes:jpg,jpeg,png,pdf|max:5120', // Max 5MB per file
            'notes' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'letter_type_id.required' => 'Jenis surat harus dipilih.',
            'letter_type_id.exists' => 'Jenis surat tidak valid.',
            'files.array' => 'Format dokumen tidak valid.',
            'files.*.file' => 'File harus berupa dokumen atau gambar.',
            'files.*.mimes' => 'Format file harus JPG, JPEG, PNG, atau PDF.',
            'files.*.max' => 'Ukuran file maksimal 5MB.',
        ];
    }
}
