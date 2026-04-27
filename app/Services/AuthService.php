<?php

namespace App\Services;

use App\Models\User;
use App\Models\Patient;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
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
            'pangkat' => $data['pangkat'] ?? null,
            'nrp_nip' => $data['nrp_nip'] ?? null,
            'pendidikan_terakhir' => $data['pendidikan_terakhir'] ?? null,
            'jabatan_kesatuan' => $data['jabatan_kesatuan'] ?? null,
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

    public function updateProfile(User $user, array $data): User
    {
        return DB::transaction(function () use ($user, $data) {
            $userData = [
                'name' => $data['name'],
                'phone' => $data['phone'] ?? $user->phone,
            ];

            if (!empty($data['password'])) {
                $userData['password'] = $data['password'];
            }

            $user->update($userData);

            if ($user->patient) {
                $user->patient->update([
                    'nik' => $data['nik'],
                    'address' => $data['address'],
                    'birth_date' => $data['birth_date'],
                    'gender' => $data['gender'],
                    'pangkat' => $data['pangkat'] ?? $user->patient->pangkat,
                    'nrp_nip' => $data['nrp_nip'] ?? $user->patient->nrp_nip,
                    'pendidikan_terakhir' => $data['pendidikan_terakhir'] ?? $user->patient->pendidikan_terakhir,
                    'jabatan_kesatuan' => $data['jabatan_kesatuan'] ?? $user->patient->jabatan_kesatuan,
                ]);
            }

            return $user->load('patient');
        });
    }
}
