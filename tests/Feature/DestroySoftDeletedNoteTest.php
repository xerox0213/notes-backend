<?php

namespace Tests\Feature;

use App\Models\Folder;
use App\Models\Note;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DestroySoftDeletedNoteTest extends TestCase
{
    use RefreshDatabase;

    public function test_should_force_delete_the_note(): void
    {
        $user = User::factory()->create();
        $folder = Folder::factory()->for($user)->create();
        $note = Note::factory()->for($folder)->for($user)->trashed()->create();

        $response = $this->actingAs($user)->deleteJson(route('notes-deleted.destroy', ['note' => $note->id]));

        $response->assertNoContent();
        $this->assertModelMissing($note);
    }

    public function test_should_not_force_delete_if_the_user_does_not_own_the_note()
    {
        $me = User::factory()->create();
        $otherUser = User::factory()->create();
        $folder = Folder::factory()->for($otherUser)->create();
        $note = Note::factory()->for($folder)->for($otherUser)->trashed()->create();

        $response = $this->actingAs($me)->deleteJson(route('notes-deleted.destroy', ['note' => $note->id]));

        $response->assertForbidden();
        $this->assertModelExists($note);
    }

    public function test_should_note_force_delete_if_the_note_is_not_initially_soft_deleted()
    {
        $me = User::factory()->create();
        $folder = Folder::factory()->for($me)->create();
        $note = Note::factory()->for($folder)->for($me)->create();

        $response = $this->actingAs($me)->deleteJson(route('notes-deleted.destroy', ['note' => $note->id]));

        $response->assertForbidden();
        $this->assertModelExists($note);
    }
}
