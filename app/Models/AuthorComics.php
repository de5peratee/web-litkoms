<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuthorComics extends Model
{
    use HasFactory;

    protected $guarded = false;
    protected $fillable = [
        'created_by',
        'name',
        'slug',
        'description',
        'views',
        'cover',
        'comics_file',
        'age_restriction',
        'average_assessment',
        'is_moderated',
        'is_published',
        'feedback',
        'published_at',
    ];

    protected $casts = [
        'is_moderated' => 'string',
        'is_published' => 'boolean',
        'published_at' => 'datetime',
    ];



    public function genres()
    {
        return $this->belongsToMany(Genre::class, 'comics_genres', 'comics_id', 'genre_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class, 'comics_to');
    }

    public function comments()
    {
        return $this->hasMany(Comments::class, 'comics_to');
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}