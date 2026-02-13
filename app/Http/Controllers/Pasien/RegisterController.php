<?php

namespace App\Http\Controllers\Pasien;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\RegisterRequest;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    public function __construct(
        protected AuthService $authService
    ) {}

    public function showRegistrationForm()
    {
        return view('pasien.register');
    }

    public function register(RegisterRequest $request)
    {
        $result = $this->authService->register($request->validated());

        $user = $result['user'];

        Auth::login($user);

        return redirect()->route('pasien.dashboard')->with('success', 'Registrasi berhasil. Selamat datang!');
    }
}
