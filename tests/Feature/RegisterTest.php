<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
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
        $response = $this->postJson(route('auth.register'), $this->credentials);

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
}
