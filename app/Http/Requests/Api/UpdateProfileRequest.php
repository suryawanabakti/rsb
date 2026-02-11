<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $user = $this->user();
        $patient = $user->patient;

        return [
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'nik' => 'required|string|max:20|unique:patients,nik,' . ($patient->id ?? 0),
            'address' => 'required|string',
            'birth_date' => 'required|date',
            'gender' => 'required|in:L,P',
            'password' => 'nullable|string|min:8|confirmed',
        ];
    }

    public function messages(): array
    {
        return [
            'nik.unique' => 'NIK sudah terdaftar.',
            'gender.in' => 'Jenis kelamin harus L (Laki-laki) atau P (Perempuan).',
        ];
    }
}
