<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\Guest;
use App\Models\Tag;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    public function run()
    {
        $events = Event::factory()->count(5)->create();

        $guests = Guest::factory()->count(20)->create();

        $tags = Tag::factory()->count(15)->create();

        foreach ($events as $event) {
            $randomGuests = $guests->random(rand(1, 5));
            $event->guests()->attach($randomGuests);

            $randomTags = $tags->random(rand(1, 3));
            $event->tags()->attach($randomTags);
        }
    }
}