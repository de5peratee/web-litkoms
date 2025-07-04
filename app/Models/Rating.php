<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    use HasFactory;

    public function rater()
    {
        return $this->belongsTo(User::class, 'graded_by');
    }

    public function comic()
    {
        return $this->belongsTo(AuthorComics::class, 'comics_to');
    }

    protected $fillable = [
        'graded_by',
        'grade',
        'comics_to'
    ];
}
