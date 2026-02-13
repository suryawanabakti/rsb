<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\LetterRequest;
use App\Models\LetterType;
use App\Models\Patient;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DoctorAccessTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->doctor = User::factory()->create(['role' => 'dokter']);
        $this->patient = Patient::factory()->create();
        $this->letterType = LetterType::factory()->create(['name' => 'SKBN']);
        $this->letterRequest = LetterRequest::create([
            'patient_id' => $this->patient->id,
            'letter_type_id' => $this->letterType->id,
            'status' => 'submitted',
        ]);
    }

    public function test_doctor_can_access_letter_requests_index()
    {
        $response = $this->actingAs($this->doctor)->get(route('admin.letter-requests.index'));

        $response->assertStatus(200);
    }

    public function test_doctor_can_see_letter_request_detail()
    {
        $response = $this->actingAs($this->doctor)->get(route('admin.letter-requests.show', $this->letterRequest));

        $response->assertStatus(200);
    }

    public function test_doctor_can_update_letter_request_status()
    {
        $response = $this->actingAs($this->doctor)->patch(route('admin.letter-requests.update-status', $this->letterRequest), [
            'status' => 'approved',
            'admin_notes' => 'Validated by doctor',
        ]);

        $response->assertStatus(302);
        $this->assertEquals('approved', $this->letterRequest->fresh()->status);
        $this->assertEquals($this->doctor->id, $this->letterRequest->fresh()->processed_by);
    }
}
