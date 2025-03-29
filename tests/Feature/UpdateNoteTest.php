<?php

namespace Tests\Feature;

use App\Models\Note;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UpdateNoteTest extends TestCase
{
    use RefreshDatabase;

    private $newNoteData = [
        'title' => 'My new title note',
    ];

    public function test_should_update_note()
    {
        $note = Note::factory()->create();
        $user = $note->user;

        $response = $this->actingAs($user)->patch(route('notes.update', ['note' => $note]), $this->newNoteData);

        $response->assertOk();
        $note->refresh();
        $this->assertEquals($this->newNoteData, $note->only(array_keys($this->newNoteData)));
    }

    public function test_should_not_update_if_user_does_not_own_the_note()
    {
        $note = Note::factory()->create();
        $user = User::factory()->create();

        $response = $this->actingAs($user)->patch(route('notes.update', ['note' => $note]), $this->newNoteData);

        $response->assertForbidden();
        $note->refresh();
        $this->assertNotEquals($this->newNoteData, $note->only(array_keys($this->newNoteData)));
    }
}
