<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    private $credentials;
    private $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->credentials = [
            'email' => 'damon.salvatore@vampire.com',
            'password' => 'iloveyouelena'
        ];

        $this->user = User::factory()->create([
            'email' => $this->credentials['email'],
            'password' => bcrypt($this->credentials['password'])
        ]);
    }

    public function test_should_login() {
        $response = $this->postJson(route('login.store'), $this->credentials);
        $response->assertNoContent();
        $this->assertAuthenticatedAs($this->user);
    }

    public function test_should_reject_if_invalid_email() {
        $this->credentials['email'] = 'damon.salvatore@human.com';
        $response = $this->postJson(route('login.store'), $this->credentials);
        $response->assertUnauthorized();
        $this->assertGuest();
    }

    public function test_should_reject_if_invalid_password() {
        $this->credentials['password'] = 'iloveyoucaroline';
        $response = $this->postJson(route('login.store'), $this->credentials);
        $response->assertUnauthorized();
        $this->assertGuest();
    }

    public function test_should_reject_if_email_is_not_in_email_format() {
        $this->credentials['email'] = 'damon.salvatore';
        $response = $this->postJson(route('login.store'), $this->credentials);
        $response->assertJsonValidationErrorFor('email');
    }
}
