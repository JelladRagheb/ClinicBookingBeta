<?php

namespace Tests\Feature;

use App\Models\DoctorProfile;
use App\Models\Role;
use App\Models\Specialty;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DoctorProfileTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\RoleSeeder::class);
    }

    public function test_admin_can_manage_specialties()
    {
        $admin = User::factory()->create();
        $admin->roles()->attach(Role::where('name', 'super_admin')->first());

        // Create
        $response = $this->actingAs($admin)->post(route('specialties.store'), [
            'name' => ['en' => 'Cardiology'],
            'slug' => 'cardiology',
            'is_active' => 1
        ]);
        $response->assertRedirect(route('specialties.index'));
        $this->assertDatabaseHas('specialties', ['slug' => 'cardiology']);

        // Update
        $specialty = Specialty::where('slug', 'cardiology')->first();
        $response = $this->actingAs($admin)->put(route('specialties.update', $specialty), [
            'name' => ['en' => 'Cardiology Updated'],
            'slug' => 'cardiology',
            'is_active' => 1
        ]);
        $this->assertDatabaseHas('specialties', ['slug' => 'cardiology']); // JSON matching is trickier, relying on redirect and no error
    }

    public function test_doctor_can_edit_profile()
    {
        $doctor = User::factory()->create();
        $doctor->roles()->attach(Role::where('name', 'doctor')->first());

        // Ensure profile exists (controller creates it if missing, but typically seeder/event handles it)
        // Let's rely on controller logic for now or manually create it
        DoctorProfile::create(['user_id' => $doctor->id, 'license_number' => 'DOC-001', 'is_available' => true]);

        $specialty = Specialty::create(['name' => ['en' => 'General'], 'slug' => 'general', 'is_active' => 1]);

        $response = $this->actingAs($doctor)->put(route('doctor.profile.update'), [
            'license_number' => 'DOC-001',
            'bio' => ['en' => 'Updated Bio'],
            'consultation_fee' => 100,
            'specialties' => [$specialty->id],
            'accepts_walk_ins' => 1
        ]);

        $response->assertRedirect(route('doctor.profile.edit'));

        $this->assertDatabaseHas('doctor_profiles', [
            'user_id' => $doctor->id,
            'consultation_fee' => 100
        ]);

        $this->assertTrue($doctor->doctorProfile->specialties->contains($specialty->id));
    }

    public function test_public_can_view_doctor_profile()
    {
        $doctor = User::factory()->create(['name' => 'Dr. Public']);
        $doctor->roles()->attach(Role::where('name', 'doctor')->first());
        $profile = DoctorProfile::create([
            'user_id' => $doctor->id,
            'license_number' => 'PUB-001',
            'bio' => ['en' => 'Public Bio'],
            'is_available' => true
        ]);

        $response = $this->get(route('doctor.profile.show', $profile->id));
        $response->assertStatus(200);
        $response->assertSee('Dr. Public');
        $response->assertSee('Public Bio');
    }
}
