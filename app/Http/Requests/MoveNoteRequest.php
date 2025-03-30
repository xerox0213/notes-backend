<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MoveNoteRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'folder_id' => ['required', 'exists:folders,id']
        ];
    }
}
