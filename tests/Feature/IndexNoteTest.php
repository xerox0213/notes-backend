<?php

namespace Tests\Feature;

use App\Models\Folder;
use App\Models\Note;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IndexNoteTest extends TestCase
{
    use RefreshDatabase;

    public function test_should_retrieve_notes()
    {
        $otherFolder = Folder::factory()->create();
        Note::factory()->count(10)->for($otherFolder)->create();

        $myFolder = Folder::factory()->create();
        Note::factory()->count(5)->for($myFolder)->create();
        $me = $myFolder->user;

        $response = $this->actingAs($me)->getJson(route('folders.notes.index', ['folder' => $myFolder->id]));

        $response->assertOk()->assertJsonCount(5);
    }

    public function test_should_not_retrieve_if_user_does_not_own_notes()
    {
        $otherFolder = Folder::factory()->create();
        Note::factory()->for($otherFolder)->count(10)->create();
        $otherUser = $otherFolder->user;

        $myFolder = Folder::factory()->create();
        Note::factory()->count(5)->for($myFolder)->create();

        $response = $this->actingAs($otherUser)->getJson(route('folders.notes.index', ['folder' => $myFolder->id]));

        $response->assertForbidden();
    }
}
