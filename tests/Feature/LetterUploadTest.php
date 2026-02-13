<?php

namespace Tests\Feature;

use App\Models\LetterRequest;
use App\Models\LetterType;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class LetterUploadTest extends TestCase
{
    use RefreshDatabase;

    protected $letterType;

    protected function setUp(): void
    {
        parent::setUp();
        // Seed basic data if needed, or create manually
        $this->letterType = LetterType::create(['name' => 'SKBN', 'slug' => 'skbn', 'description' => 'Surat Keterangan Bebas Narkoba']);
    }

    public function test_admin_can_upload_final_letter()
    {
        Storage::fake('public');

        $admin = User::factory()->create(['role' => 'admin']);
        $patientUser = User::factory()->create(['role' => 'pasien']);
        $patient = Patient::create([
            'user_id' => $patientUser->id,
            'nik' => '1234567890123456',
            'address' => 'Jl. Mappaoddang',
            'birth_date' => '1990-01-01',
            'gender' => 'L',
        ]);

        $letterRequest = LetterRequest::create([
            'patient_id' => $patient->id,
            'letter_type_id' => $this->letterType->id,
            'status' => 'approved',
            'submission_date' => now(),
        ]);

        $file = UploadedFile::fake()->create('final_letter.pdf', 1000, 'application/pdf');

        $response = $this->actingAs($admin)->post("/admin/letter-requests/{$letterRequest->id}/upload-final", [
            'final_letter' => $file,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $letterRequest->refresh();
        $this->assertEquals('completed', $letterRequest->status);
        $this->assertNotNull($letterRequest->final_letter);
        Storage::disk('public')->assertExists($letterRequest->final_letter);
    }

    public function test_non_admin_cannot_upload_final_letter()
    {
        $patientUser = User::factory()->create(['role' => 'pasien']);
        $patient = Patient::create([
            'user_id' => $patientUser->id,
            'nik' => '1234567890123456',
            'address' => 'Jl. Mappaoddang',
            'birth_date' => '1990-01-01',
            'gender' => 'L',
        ]);

        $letterRequest = LetterRequest::create([
            'patient_id' => $patient->id,
            'letter_type_id' => $this->letterType->id,
            'status' => 'approved',
            'submission_date' => now(),
        ]);

        $file = UploadedFile::fake()->create('final_letter.pdf', 1000);

        $response = $this->actingAs($patientUser)->post("/admin/letter-requests/{$letterRequest->id}/upload-final", [
            'final_letter' => $file,
        ]);

        $response->assertStatus(403); // Or 302 to login/dashboard depending on middleware
    }

    public function test_patient_can_see_final_letter_link_when_completed()
    {
        $patientUser = User::factory()->create(['role' => 'pasien']);
        $patient = Patient::create([
            'user_id' => $patientUser->id,
            'nik' => '1234567890123456',
            'address' => 'Jl. Mappaoddang',
            'birth_date' => '1990-01-01',
            'gender' => 'L',
        ]);

        $letterRequest = LetterRequest::create([
            'patient_id' => $patient->id,
            'letter_type_id' => $this->letterType->id,
            'status' => 'completed',
            'final_letter' => 'final-letters/test.pdf',
            'submission_date' => now(),
        ]);

        $response = $this->actingAs($patientUser)->get("/pasien/letter-requests/{$letterRequest->id}");

        $response->assertStatus(200);
        $response->assertSee('Unduh Surat Resmi');
    }

    public function test_admin_can_update_patient_extra_info()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $patientUser = User::factory()->create(['role' => 'pasien']);
        $patient = Patient::create([
            'user_id' => $patientUser->id,
            'nik' => '1234567890123456',
            'address' => 'Old Address',
            'birth_date' => '1990-01-01',
            'gender' => 'L',
        ]);

        $response = $this->actingAs($admin)->patch("/admin/patients/{$patient->id}/extra-info", [
            'pangkat' => 'BRIPDA',
            'nrp_nip' => '95010123',
            'pendidikan_terakhir' => 'S1 HUKUM',
            'jabatan_kesatuan' => 'SATSABHARA',
            'address' => 'New Address',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('patients', [
            'id' => $patient->id,
            'pangkat' => 'BRIPDA',
            'nrp_nip' => '95010123',
            'address' => 'New Address',
        ]);
    }
}
