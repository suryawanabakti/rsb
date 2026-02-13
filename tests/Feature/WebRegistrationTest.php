<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Patient;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WebRegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_page_is_accessible()
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
        $response->assertSee('Registrasi Pasien Baru');
    }

    public function test_patient_can_register_on_web()
    {
        $response = $this->post('/register', [
            'name' => 'John Doe',
            'username' => 'johndoe',
            'nik' => '1234567890123456',
            'phone' => '08123456789',
            'birth_date' => '1990-01-01',
            'gender' => 'L',
            'address' => 'Jl. Mappaoddang No. 1',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect('/pasien/dashboard');
        $this->assertAuthenticated();

        $this->assertDatabaseHas('users', [
            'username' => 'johndoe',
            'role' => 'pasien',
        ]);

        $user = User::where('username', 'johndoe')->first();
        $this->assertDatabaseHas('patients', [
            'user_id' => $user->id,
            'nik' => '1234567890123456',
        ]);
    }

    public function test_registration_validation_errors()
    {
        $response = $this->post('/register', [
            'name' => '',
            'username' => '',
            'password' => 'short',
            // missing other fields
        ]);

        $response->assertSessionHasErrors(['name', 'username', 'password', 'nik', 'address', 'birth_date', 'gender']);
    }
}
