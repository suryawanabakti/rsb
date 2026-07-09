<?php

namespace App\Http\Requests\Pasien;

use App\Models\LetterType;
use Illuminate\Foundation\Http\FormRequest;

class StoreLetterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $photoRequired = 'nullable';
        $letterType = LetterType::find($this->letter_type_id);
        if ($letterType && $letterType->slug === 'skbn') {
            $photoRequired = 'required';
        }

        return [
            'letter_type_id' => 'required|exists:letter_types,id',
            'keperluan' => 'required|string|max:255',
            'photo_4x6' => $photoRequired . '|image|mimes:jpg,jpeg,png|max:2048',
            'files' => 'nullable|array',
            'files.*' => 'file|mimes:jpg,jpeg,png,pdf|max:5120',
            'notes' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'letter_type_id.required' => 'Jenis surat harus dipilih.',
            'letter_type_id.exists' => 'Jenis surat tidak valid.',
            'photo_4x6.required' => 'Pas foto 4x6 wajib diupload.',
            'photo_4x6.image' => 'Pas foto harus berupa gambar.',
            'photo_4x6.mimes' => 'Format pas foto harus JPG, JPEG, atau PNG.',
            'photo_4x6.max' => 'Ukuran pas foto maksimal 2MB.',
            'files.array' => 'Format dokumen tidak valid.',
            'files.*.file' => 'File harus berupa dokumen atau gambar.',
            'files.*.mimes' => 'Format file harus JPG, JPEG, PNG, atau PDF.',
            'files.*.max' => 'Ukuran file maksimal 5MB.',
        ];
    }
}
