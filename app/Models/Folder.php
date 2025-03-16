<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Folder extends Model
{
    protected $fillable = [
        "name"
    ];

    public $timestamps = false;

    public function notes(): HasMany
    {
        return $this->hasMany(Note::class);
    }
}
