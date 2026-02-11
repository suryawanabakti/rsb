<?php

namespace App\Services;

use App\Models\User;
use App\Models\Patient;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthService
{
    public function register(array $data): array
    {
        $user = User::create([
            'name' => $data['name'],
            'username' => $data['username'],
            'password' => $data['password'], // Hash is handled by model attribute
            'phone' => $data['phone'] ?? null,
            'role' => 'pasien',
        ]);

        Patient::create([
            'user_id' => $user->id,
            'nik' => $data['nik'],
            'address' => $data['address'],
            'birth_date' => $data['birth_date'],
            'gender' => $data['gender'],
        ]);

        $token = $user->createToken('mobile_token')->plainTextToken;

        return [
            'user' => $user->load('patient'),
            'token' => $token,
        ];
    }

    public function login(string $username, string $password): array
    {
        $user = User::where('username', $username)->first();

        if (!$user || !Hash::check($password, $user->password)) {
            throw ValidationException::withMessages([
                'username' => ['Kredensial yang diberikan tidak cocok.'],
            ]);
        }

        $token = $user->createToken('mobile_token')->plainTextToken;

        return [
            'user' => $user->load('patient'),
            'token' => $token,
        ];
    }
}
