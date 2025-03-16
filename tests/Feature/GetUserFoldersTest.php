<?php

namespace Tests\Feature;

use App\Models\Folder;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GetUserFoldersTest extends TestCase
{
    use RefreshDatabase;

    public function test_should_retrieve_user_folders()
    {
        $user = User::factory()->create();
        Folder::factory()->count(5)->for($user)->create();
        Folder::factory()->count(3)->create();

        $response = $this->actingAs($user)->getJson(route('folders.index'));
        $response
            ->assertJsonCount(5)
            ->assertJsonStructure([
                "*" => [
                    "id",
                    "name",
                ]
            ]);
    }
}
