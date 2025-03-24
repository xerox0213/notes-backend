<?php

namespace Tests\Feature;

use App\Models\Folder;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UpdateFolderTest extends TestCase
{
    use RefreshDatabase;

    public function test_should_update_the_name()
    {
        $folder = Folder::factory()->create();
        $user = $folder->user;
        $newName = 'My secret notes';

        $response = $this->actingAs($user)->patchJson(route('folders.update', ['folder' => $folder->id]), ['name' => $newName]);

        $response->assertNoContent();
        $folder->refresh();
        $this->assertEquals($newName, $folder->name);
    }

    public function test_should_reject_if_the_user_does_not_own_the_folder()
    {
        $folder = Folder::factory()->create();
        $user = User::factory()->create();
        $newName = 'My secret notes';

        $response = $this->actingAs($user)->patchJson(route('folders.update', ['folder' => $folder->id]), ['name' => $newName]);

        $response->assertForbidden();
        $folder->refresh();
        $this->assertNotEquals($newName, $folder->name);
    }
}
