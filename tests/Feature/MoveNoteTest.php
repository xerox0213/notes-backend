<?php

namespace Tests\Feature;

use App\Models\Folder;
use App\Models\Note;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MoveNoteTest extends TestCase
{
    use RefreshDatabase;

    public function test_should_move_note()
    {
        $user = User::factory()->create();
        $currFolder = Folder::factory()->for($user)->create();
        $targetFolder = Folder::factory()->for($user)->create();
        $note = Note::factory()->for($currFolder)->for($user)->create();

        $response = $this->actingAs($user)->patchJson(route('notes.move', ['note' => $note->id]), ['folder_id' => $targetFolder->id]);

        $response->assertOk();
        $note->refresh();
        $this->assertEquals($note->folder_id, $targetFolder->id);
    }

    public function test_should_restore_if_note_is_soft_deleted()
    {
        $user = User::factory()->create();
        $currFolder = Folder::factory()->for($user)->create();
        $targetFolder = Folder::factory()->for($user)->create();
        $note = Note::factory()->for($currFolder)->for($user)->trashed()->create();

        $response = $this->actingAs($user)->patchJson(route('notes.move', ['note' => $note->id]), ['folder_id' => $targetFolder->id]);

        $response->assertOk();
        $note->refresh();
        $this->assertNotSoftDeleted($note);
        $this->assertEquals($note->folder_id, $targetFolder->id);
    }

    public function test_should_not_move_if_the_user_does_not_own_the_note()
    {
        $me = User::factory()->create();
        $otherUser = User::factory()->create();
        $currFolder = Folder::factory()->for($otherUser)->create();
        $targetFolder = Folder::factory()->for($otherUser)->create();
        $note = Note::factory()->for($currFolder)->for($otherUser)->create();

        $response = $this->actingAs($me)->patchJson(route('notes.move', ['note' => $note->id]), ['folder_id' => $targetFolder->id]);

        $response->assertForbidden();
        $note->refresh();
        $this->assertEquals($note->folder_id, $currFolder->id);
    }
}
