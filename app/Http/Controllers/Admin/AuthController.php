<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            // Only allow admin, petugas_lab, dokter, pasien
            if (!in_array($user->role, ['admin', 'petugas_lab', 'dokter', 'pasien'])) {
                Auth::logout();
                throw ValidationException::withMessages([
                    'username' => ['Role pengguna tidak valid.'],
                ]);
            }

            $request->session()->regenerate();

            // Redirect based on role
            return match ($user->role) {
                'petugas_lab' => redirect()->intended('/petugas-lab/dashboard'),
                'dokter' => redirect()->intended('/dokter/dashboard'),
                'pasien' => redirect()->intended('/pasien/dashboard'),
                default => redirect()->intended('/admin/dashboard'),
            };
        }

        throw ValidationException::withMessages([
            'username' => ['Kredensial yang diberikan tidak cocok.'],
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/admin/login');
    }
}
