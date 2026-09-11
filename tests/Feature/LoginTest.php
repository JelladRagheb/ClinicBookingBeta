<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_screen_can_be_rendered()
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertSee('Email');
        $response->assertSee('Password');
        $response->assertSee('Remember me');

        // Ensure Register fields are NOT present
        $response->assertDontSee('First Name');
        $response->assertDontSee('Last Name');
        $response->assertDontSee('I am a...');
        $response->assertDontSee('Phone Number');
    }
}
