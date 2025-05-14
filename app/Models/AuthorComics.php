<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuthorComics extends Model
{
    protected $guarded = false;

    public function genres()
    {
        return $this->belongsToMany(Genre::class, 'comics_genres');
    }
}
