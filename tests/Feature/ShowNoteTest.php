<?php

namespace Tests\Feature;

use App\Models\Note;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShowNoteTest extends TestCase
{
    use RefreshDatabase;

    public function test_should_show_note(): void
    {
        $note = Note::factory()->create();
        $user = $note->folder->user;

        $response = $this->actingAs($user)->getJson(route('notes.show', ['note' => $note->id]));

        $response->assertOk()->assertJson($note->only('id', 'title', 'content'));
    }

    public function test_should_note_show_if_user_doe_not_own_the_note()
    {
        $note = Note::factory()->create();
        $user = User::factory()->create();

        $response = $this->actingAs($user)->getJson(route('notes.show', ['note' => $note->id]));

        $response->assertForbidden();
    }
}
