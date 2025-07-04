<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;
    protected $guarded = false;

    public function events()
    {
        return $this->belongsToMany(Event::class, 'event_tags');
    }
}
