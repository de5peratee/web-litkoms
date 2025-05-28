<?php

namespace App\Services;

use App\Models\Event;
use App\Models\MultimediaPost;

class NewsFormatterService
{
    public static function formatEvent(Event $event)
    {
        return (object)[
            'type' => 'event',
            'id' => $event->id,
            'title' => $event->name,
            'description' => $event->description,
            'date' => $event->start_date,
            'cover' => $event->cover,
        ];
    }

    public static function formatMultimedia(MultimediaPost $post)
    {
        return (object)[
            'type' => 'multimedia',
            'id' => $post->id,
            'title' => $post->name,
            'description' => $post->description,
            'date' => $post->created_at,
            'cover' => optional($post->medias->first())->file,
        ];
    }
}

