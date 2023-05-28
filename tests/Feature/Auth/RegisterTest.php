<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RegisterTest extends TestCase
{

    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_a_user_can_register(): void
    {
//        $this->withExceptionHandling();
        $userData = [
            'name' => $this->faker->name,
            'email' => 'email@email.com', // $this->faker->email,
            'active' => false,
        ];
        $this->postJson(route('register'), array_merge($userData, [
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
        ]))
            ->assertCreated();
        $this->assertDatabaseHas('users', $userData);
    }
}
