<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_profile_page_is_accessible()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/profile');

        $response->assertStatus(200);
        $response->assertSee('Pengaturan Profil');
    }

    public function test_profile_can_be_updated()
    {
        $user = User::factory()->create([
            'name' => 'Old Name',
            'phone' => '1234567890',
        ]);

        $response = $this->actingAs($user)->put('/profile', [
            'name' => 'New Name',
            'phone' => '0987654321',
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'New Name',
            'phone' => '0987654321',
        ]);
    }

    public function test_profile_photo_can_be_uploaded()
    {
        Storage::fake('public');

        $user = User::factory()->create();
        $file = UploadedFile::fake()->image('avatar.jpg');

        $response = $this->actingAs($user)->put('/profile', [
            'name' => $user->name,
            'photo' => $file,
        ]);

        $response->assertSessionHas('success');

        $user->refresh();
        $this->assertNotNull($user->photo);
        Storage::disk('public')->assertExists($user->photo);
    }

    public function test_password_can_be_updated()
    {
        $user = User::factory()->create([
            'password' => bcrypt('old-password'),
        ]);

        $response = $this->actingAs($user)->put('/profile', [
            'name' => $user->name,
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
        ]);

        $response->assertSessionHas('success');

        $user->refresh();
        $this->assertTrue(Hash::check('new-password', $user->password));
    }

    public function test_patient_specific_fields_can_be_updated()
    {
        $user = User::factory()->create(['role' => 'pasien']);
        $patient = \App\Models\Patient::create([
            'user_id' => $user->id,
            'nik' => '1234567890123456',
            'address' => 'Old Address',
            'birth_date' => '1990-01-01',
            'gender' => 'L',
        ]);

        $response = $this->actingAs($user)->put('/profile', [
            'name' => 'Patient Name',
            'nik' => '9999999999999999',
            'address' => 'New Address',
            'birth_date' => '1995-05-05',
            'gender' => 'P',
        ]);

        $response->assertSessionHas('success');

        $patient->refresh();
        $this->assertEquals('9999999999999999', $patient->nik);
        $this->assertEquals('New Address', $patient->address);
        $this->assertEquals('1995-05-05', $patient->birth_date);
        $this->assertEquals('P', $patient->gender);
    }
}
