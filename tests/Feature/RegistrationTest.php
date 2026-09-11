<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Database\Seeders\RoleSeeder;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleSeeder::class);
    }

    public function test_registration_screen_can_be_rendered()
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
        $response->assertSee('I am a...'); // Check for role label
        $response->assertSee('patient'); // Check for patient option
    }

    public function test_new_users_can_register_as_patient()
    {
        $response = $this->post('/register', [
            'first_name' => 'Test',
            'last_name' => 'Patient',
            'email' => 'test@example.com',
            'phone' => '1234567890',
            'password' => 'password',
            'password_confirmation' => 'password',
            'role' => 'patient',
        ]);

        $this->assertAuthenticated();

        $user = User::where('email', 'test@example.com')->first();
        $this->assertTrue($user->hasRole('patient'));
        $this->assertNotNull($user->patientProfile);

        $response->assertRedirect(route('patient.dashboard', absolute: false));
    }

    public function test_new_users_can_register_as_doctor()
    {
        $response = $this->post('/register', [
            'first_name' => 'Test',
            'last_name' => 'Doctor',
            'email' => 'doctor@example.com',
            'phone' => '0987654321',
            'password' => 'password',
            'password_confirmation' => 'password',
            'role' => 'doctor',
        ]);

        $this->assertAuthenticated();

        $user = User::where('email', 'doctor@example.com')->first();
        $this->assertTrue($user->hasRole('doctor'));
        $this->assertNotNull($user->doctorProfile);
        $this->assertStringStartsWith('PENDING-', $user->doctorProfile->license_number);

        $response->assertRedirect(route('doctor.dashboard', absolute: false));
    }

    public function test_users_cannot_register_as_admin()
    {
        $response = $this->post('/register', [
            'first_name' => 'Hacker',
            'last_name' => 'Admin',
            'email' => 'admin@example.com',
            'phone' => '1122334455',
            'password' => 'password',
            'password_confirmation' => 'password',
            'role' => 'super_admin',
        ]);

        $this->assertGuest();
        $response->assertSessionHasErrors('role');
    }
}
