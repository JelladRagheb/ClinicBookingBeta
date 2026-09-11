<?php

namespace Tests\Feature;

use App\Models\Location;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LocationManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Seed roles if not already handled by a trait or global setup
        $this->seed(\Database\Seeders\RoleSeeder::class);
    }

    public function test_admin_can_view_locations_list()
    {
        $admin = User::factory()->create();
        $admin->roles()->attach(Role::where('name', 'super_admin')->first());

        $response = $this->actingAs($admin)->get(route('locations.index'));

        $response->assertStatus(200);
        $response->assertViewIs('locations.index');
    }

    public function test_admin_can_create_location()
    {
        $admin = User::factory()->create();
        $admin->roles()->attach(Role::where('name', 'super_admin')->first());

        $response = $this->actingAs($admin)->post(route('locations.store'), [
            'name' => ['en' => 'Test Clinic', 'fr' => 'Clinique Test'],
            'code' => 'TEST-001',
            'latitude' => 36.8065,
            'longitude' => 10.1815,
            'is_active' => 1
        ]);

        $response->assertRedirect(route('locations.index'));
        $this->assertDatabaseHas('locations', ['code' => 'TEST-001']);
    }

    public function test_non_admin_cannot_manage_locations()
    {
        $doctor = User::factory()->create();
        $doctor->roles()->attach(Role::where('name', 'doctor')->first());

        $response = $this->actingAs($doctor)->get(route('locations.index'));
        $response->assertStatus(403);
    }

    public function test_public_can_access_search_page()
    {
        $response = $this->get(route('locations.search'));
        $response->assertStatus(200);
        $response->assertViewIs('locations.search');
    }

    public function test_search_api_returns_nearby_locations()
    {
        Location::create([
            'name' => ['en' => 'Nearby Clinic'],
            'code' => 'NEARBY',
            'latitude' => 36.8065,
            'longitude' => 10.1815,
            'is_active' => 1
        ]);

        Location::create([
            'name' => ['en' => 'Far Clinic'],
            'code' => 'FAR',
            'latitude' => 48.8566, // Paris
            'longitude' => 2.3522,
            'is_active' => 1
        ]);

        // User is at 36.8, 10.1 (Tunis)
        $response = $this->getJson(route('locations.search', ['lat' => 36.8, 'lng' => 10.1]));

        $response->assertStatus(200);
        $response->assertJsonCount(1);
        $response->assertJsonFragment(['code' => 'NEARBY']);
        $response->assertJsonMissing(['code' => 'FAR']);
    }
}
