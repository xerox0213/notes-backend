<?php

namespace Tests\Feature;

use App\Models\Note;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DestroyNoteTest extends TestCase
{
    use RefreshDatabase;

    public function test_should_not_soft_delete_note()
    {
        $note = Note::factory()->create();
        $user = $note->user;

        $response = $this->actingAs($user)->deleteJson(route('notes.destroy', ['note' => $note->id]));

        $response->assertNoContent();
        $this->assertSoftDeleted($note);
    }

    public function test_should_note_soft_delete_if_user_does_not_own_the_note()
    {
        $note = Note::factory()->create();
        $user = User::factory()->create();

        $response = $this->actingAs($user)->deleteJson(route('notes.destroy', ['note' => $note->id]));

        $response->assertForbidden();
        $this->assertNotSoftDeleted($note);
    }
}
