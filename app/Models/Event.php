<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $guarded = false;

    protected $casts = [
        'start_date' => 'datetime',
    ];

    public function guests()
    {
        return $this->belongsToMany(Guest::class, 'event_guests');
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'event_tags');
    }
}
