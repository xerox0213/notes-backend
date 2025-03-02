<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    private $credentials = [
        'first_name' => 'Damon',
        'last_name' => 'Salvatore',
        'email' => 'damon.salvatore@vampire.com',
        'password' => 'iloveyouelena'
    ];

    public function test_should_register() {
        $response = $this->postJson(route('register.store'), $this->credentials);

        $response->assertNoContent();
        $this->assertDatabaseHas('users', [
            'first_name' => $this->credentials['first_name'],
            'last_name' => $this->credentials['last_name'],
            'email' => $this->credentials['email']
        ]);
        $password = $this->credentials['password'];
        $hashedPassword = User::whereEmail($this->credentials['email'])->first()->password;
        $this->assertTrue(Hash::check($password, $hashedPassword));
    }

    public function test_should_reject_if_email_is_already_used() {
        User::create($this->credentials);
        $response = $this->postJson(route('register.store'), $this->credentials);
        $response->assertJsonValidationErrorFor('email');
    }

    public function test_should_reject_if_email_is_not_in_email_format() {
        $this->credentials['email'] = 'damon.salvatore';
        $response = $this->postJson(route('register.store'), $this->credentials);
        $response->assertJsonValidationErrorFor('email');
    }

    public function test_should_reject_if_first_name_is_not_at_least_three_chars_long() {
        $this->credentials['first_name'] = 'Da';
        $response = $this->postJson(route('register.store'), $this->credentials);
        $response->assertJsonValidationErrorFor('first_name');
    }

    public function test_should_reject_if_last_name_is_not_at_least_three_chars_long() {
        $this->credentials['last_name'] = 'Sa';
        $response = $this->postJson(route('register.store'), $this->credentials);
        $response->assertJsonValidationErrorFor('last_name');
    }

    public function test_should_reject_if_password_is_not_at_least_three_chars_long() {
        $this->credentials['password'] = 'il';
        $response = $this->postJson(route('register.store'), $this->credentials);
        $response->assertJsonValidationErrorFor('password');
    }
}
