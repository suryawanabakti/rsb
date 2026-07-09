<?php

namespace App\Http\Requests\Api;

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
            'photo_4x6' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'files' => 'nullable|array',
            'files.*' => 'file|max:5120', // Max 5MB per file
        ];
    }
}
