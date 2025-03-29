<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreNoteRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            "title" => ['string'],
            "content" => ['string']
        ];
    }
}
