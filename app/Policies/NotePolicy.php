<?php

namespace App\Policies;

use App\Models\Note;
use App\Models\User;

class NotePolicy
{
    public function delete(User $user, Note $note)
    {
        return $note->folder->user_id == $user->id;
    }

    public function update(User $user, Note $note)
    {
        return $note->folder->user_id == $user->id;
    }

    public function view(User $user, Note $note)
    {
        return $note->folder->user_id == $user->id;
    }
}
