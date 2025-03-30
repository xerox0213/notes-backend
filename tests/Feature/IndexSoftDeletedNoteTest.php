<?php

namespace Tests\Feature;

use App\Models\Folder;
use App\Models\Note;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IndexSoftDeletedNoteTest extends TestCase
{
    use RefreshDatabase;

    public function test_should_retrieve_user_notes(): void
    {
        $otherNotes = Note::factory()->count(7)->create();
        $me = User::factory()->create();
        $myFolder = Folder::factory()->for($me)->create();
        $myNotes = Note::factory()->count(10)->for($myFolder)->for($me)->create();
        $mySoftDeletedNotes = Note::factory()->count(5)->for($myFolder)->for($me)->trashed()->create();

        $response = $this->actingAs($me)->getJson(route('notes-deleted.index'));

        $response->assertOk()->assertJsonCount(5);
    }
}
