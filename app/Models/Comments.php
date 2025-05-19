<?php
// app/Models/Comments.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comments extends Model
{
    protected $fillable = [
        'created_by',
        'comment',
        'author_comics_id',
    ];

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function authorComic()
    {
        return $this->belongsTo(AuthorComics::class, 'comics_to');
    }
}

