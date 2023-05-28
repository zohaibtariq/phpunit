<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LoginTest extends TestCase
{

    use RefreshDatabase;

    public function test_a_user_can_login_with_correct_email_password(): void
    {
        $user = User::factory()->create();
        $loginData = [
            'email' => $user->email, //'email@email.com', // $this->faker->email,
            'password' => 'secret123'
        ];
        $response = $this->postJson(route('login'), $loginData)
            ->assertOk();
        $this->assertArrayHasKey('token', $response->json());
    }

    public function test_if_user_email_is_not_correct_then_it_returns_unauthorized_status_code(): void
    {
        $loginData = [
            'email' => $this->faker->email,// $user->email, //'email@email.com', // $this->faker->email,
            'password' => 'secret123'
        ];
        $this->postJson(route('login'), $loginData)
            ->assertUnauthorized();
    }

}
