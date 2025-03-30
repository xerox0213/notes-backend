<?php

namespace Tests\Feature;

use App\Models\Folder;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StoreNoteTest extends TestCase
{
    use RefreshDatabase;

    private $noteData = [
        'title' => 'My long long long long long title note',
        'content' => 'My content note'
    ];

    public function test_should_create_note()
    {
        $folder = Folder::factory()->create();
        $user = $folder->user;

        $response = $this
            ->actingAs($user)
            ->postJson(route('folders.notes.store', ['folder' => $folder->id]), $this->noteData);

        $response->assertCreated()->assertJson(['title' => $this->noteData['title']]);
        $this->noteData['folder_id'] = $folder->id;
        $this->assertDatabaseHas('notes', $this->noteData);
    }

    public function test_should_not_create_note_if_user_does_not_own_the_folder()
    {
        $user = User::factory()->create();
        $folder = Folder::factory()->create();

        $response = $this
            ->actingAs($user)
            ->postJson(route('folders.notes.store', ['folder' => $folder->id]), $this->noteData);

        $response->assertForbidden();
        $this->noteData['folder_id'] = $folder->id;
        $this->assertDatabaseMissing('notes', $this->noteData);
    }
}
