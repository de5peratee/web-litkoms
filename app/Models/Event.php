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

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    protected static function booted()
    {
        static::saving(function ($event) {
            if ($event->start_date && $event->start_time) {
                $event->start_datetime = $event->start_date . ' ' . $event->start_time;
            }

            if ($event->end_date && $event->end_time) {
                $event->end_datetime = $event->end_date . ' ' . $event->end_time;
            }
        });
    }

}
