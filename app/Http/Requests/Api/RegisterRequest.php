<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'nik' => 'required|string|max:20|unique:patients',
            'address' => 'required|string',
            'birth_date' => 'required|date',
            'gender' => 'required|in:L,P',
            'pangkat' => 'nullable|string|max:255',
            'nrp_nip' => 'nullable|string|max:255',
            'pendidikan_terakhir' => 'nullable|string|max:255',
            'jabatan_kesatuan' => 'nullable|string|max:255',
        ];
    }
}
