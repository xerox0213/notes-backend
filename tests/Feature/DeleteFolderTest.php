<?php

namespace Tests\Feature;

use App\Models\Folder;
use App\Models\Note;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeleteFolderTest extends TestCase
{
    use RefreshDatabase;

    public function test_should_delete_the_folder()
    {
        $folder = Folder::factory()->create();
        $user = $folder->user;
        $notes = Note::factory()->count(3)->for($folder)->create();

        $response = $this->actingAs($user)->deleteJson(route('folders.destroy', ['folder' => $folder->id]));

        $response->assertNoContent();
        $this->assertModelMissing($folder);
        foreach ($notes as $note) {
            $this->assertSoftDeleted($note);
        }
    }
}
