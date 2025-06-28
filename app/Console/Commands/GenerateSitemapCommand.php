<?php

namespace App\Console\Commands;

use App\Models\AuthorComics;
use App\Models\Catalog;
use Illuminate\Console\Command;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;
use App\Models\Event;


class GenerateSitemapCommand extends Command
{
    protected $signature = 'sitemap:generate';
    protected $description = 'Generate the sitemap for the application';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $sitemap = Sitemap::create();

        $sitemap->add(Url::create(route('home'))
            ->setLastModificationDate(now())
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
            ->setPriority(1.0));

        $sitemap->add(Url::create(route('library.index'))
            ->setLastModificationDate(now())
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
            ->setPriority(0.8));

        $sitemap->add(Url::create(route('events.index'))
            ->setLastModificationDate(now())
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
            ->setPriority(0.8));

        $sitemap->add(Url::create(route('mediaposts'))
            ->setLastModificationDate(now())
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
            ->setPriority(0.8));

        $sitemap->add(Url::create(route('authors_comics_landing'))
            ->setLastModificationDate(now())
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
            ->setPriority(0.7));

        $sitemap->add(Url::create(route('litar_landing'))
            ->setLastModificationDate(now())
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
            ->setPriority(0.7));

        $sitemap->add(Url::create(route('manuals.policy'))
            ->setLastModificationDate(now())
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_YEARLY)
            ->setPriority(0.5));

        Catalog::all()->each(function (Catalog $book) use ($sitemap) {
            $sitemap->add(Url::create(route('library.get_book', $book->id))
                ->setLastModificationDate($book->updated_at)
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                ->setPriority(0.8));
        });

        Event::all()->each(function (Event $event) use ($sitemap) {
            $sitemap->add(Url::create(route('events.get_event', $event->id))
                ->setLastModificationDate($event->updated_at)
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                ->setPriority(0.8));
        });

        AuthorComics::all()->each(function (AuthorComics $comic) use ($sitemap) {
            $sitemap->add(Url::create(route('author_comic', $comic->slug))
                ->setLastModificationDate($comic->updated_at)
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                ->setPriority(0.8));
        });

        $sitemap->writeToFile(public_path('sitemap.xml'));

        $this->info('Sitemap generated successfully.');
    }
}