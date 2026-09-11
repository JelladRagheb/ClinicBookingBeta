<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RoleAccessTest extends TestCase
{
    // usage of RefreshDatabase might wipe the seeded data, so we might need to seed in test or use DatabaseTransactions if we want to keep data, 
    // but RefreshDatabase is standard. We will seed roles in setUp.
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\RoleSeeder::class);
    }

    public function test_admin_can_access_admin_dashboard()
    {
        $user = User::factory()->create();
        $user->roles()->attach(Role::where('name', 'super_admin')->first());

        $response = $this->actingAs($user)->get('/admin/dashboard');

        $response->assertStatus(200);
        $response->assertViewIs('dashboard.admin');
    }

    public function test_doctor_can_access_doctor_dashboard()
    {
        $user = User::factory()->create();
        $user->roles()->attach(Role::where('name', 'doctor')->first());

        $response = $this->actingAs($user)->get('/doctor/dashboard');

        $response->assertStatus(200);
        $response->assertViewIs('dashboard.doctor');
    }

    public function test_doctor_cannot_access_admin_dashboard()
    {
        $user = User::factory()->create();
        $user->roles()->attach(Role::where('name', 'doctor')->first());

        $response = $this->actingAs($user)->get('/admin/dashboard');

        $response->assertStatus(403);
    }

    public function test_patient_redirects_to_patient_dashboard_from_root_dashboard()
    {
        $user = User::factory()->create();
        $user->roles()->attach(Role::where('name', 'patient')->first());

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertRedirect('/patient/dashboard');
    }

    public function test_dashboard_redirects_based_on_role()
    {
        $user = User::factory()->create();
        $user->roles()->attach(Role::where('name', 'doctor')->first());

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertRedirect('/doctor/dashboard');
    }

    public function test_receptionist_access()
    {
        $user = User::factory()->create();
        $user->roles()->attach(Role::where('name', 'receptionist')->first());

        $response = $this->actingAs($user)->get('/receptionist/dashboard');
        $response->assertStatus(200);

        $response = $this->actingAs($user)->get('/admin/dashboard');
        $response->assertStatus(403);
    }
}
