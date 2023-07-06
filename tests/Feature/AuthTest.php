<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        Artisan::call('passport:install');
    }

    public function test_user_can_register_with_valid_data()
    {
        $response = $this->postJson('/api/register', [
            'name' => 'Test',
            'email' => 'test@example.com',
            'username' => 'test',
            'password' => 'password',
            'password_confirmation' => 'password',

        ]);
        $response->assertStatus(201)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'user',
                    'access_token',
                ],

            ]);
        $this->assertDatabaseHas('users', [
            'name' => 'Test',
        ]);
    }

    public function test_user_cannot_register_with_invalid_data()
    {
        $response = $this->postJson('/api/register', [
            'name' => 'Test',
        ]);
        $response->assertStatus(422);
    }

    public function test_user_can_login_with_valid_email()
    {
        $user = User::factory()->create(['email' => 'test@example.com', 'password' => 'password']);
        $response = $this->postJson('/api/login', [
            'username' => $user->email,
            'password' => 'password',
        ]);
        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'user',
                    'access_token',
                ],
            ]);
    }

    public function test_user_can_login_with_valid_username()
    {

        $user = User::factory()->create(['username' => 'test', 'password' => 'password']);
        $response = $this->postJson('/api/login', [
            'username' => $user->username,
            'password' => 'password',
        ]);
        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'user',
                    'access_token',
                ],
            ]);
    }

    public function test_user_cannot_login_with_invalid_email()
    {
        $response = $this->postJson('/api/login', [
            'username' => 'test@example.com',
            'password' => 'password',
        ]);
        $response->assertStatus(422);
    }

    public function test_user_cannot_login_with_invalid_username()
    {
        $response = $this->postJson('/api/login', [
            'username' => 'test',
            'password' => 'password',
        ]);
        $response->assertStatus(422);
    }
}
