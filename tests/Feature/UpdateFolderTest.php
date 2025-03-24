<?php

namespace Tests\Feature;

use App\Models\Folder;
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
}
