<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class IndexNoteResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => Str::of($this->title)->length() > 15 ? Str::substr($this->title, 0, 12) . '...' : $this->title,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
