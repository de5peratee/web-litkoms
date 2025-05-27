<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use MoonShine\Contracts\Core\DependencyInjection\ConfiguratorContract;
use MoonShine\Contracts\Core\DependencyInjection\CoreContract;
use MoonShine\Laravel\DependencyInjection\MoonShine;
use MoonShine\Laravel\DependencyInjection\MoonShineConfigurator;
use App\MoonShine\Resources\MoonShineUserResource;
use App\MoonShine\Resources\MoonShineUserRoleResource;
use App\MoonShine\Resources\UsersResource;
use App\MoonShine\Resources\CatalogResource;
use App\MoonShine\Resources\GenreResource;
use App\MoonShine\Resources\AuthorComicsResource;
use App\MoonShine\Resources\RatingResource;
use App\MoonShine\Resources\SubscribeResource;
use App\MoonShine\Resources\CommentsResource;
use App\MoonShine\Resources\EventsResource;
use App\MoonShine\Resources\GuestsResource;
use App\MoonShine\Resources\TagsResource;
use App\MoonShine\Resources\MultimediaPostResource;
use App\MoonShine\Resources\MediaResource;

class MoonShineServiceProvider extends ServiceProvider
{
    /**
     * @param  MoonShine  $core
     * @param  MoonShineConfigurator  $config
     *
     */
    public function boot(CoreContract $core, ConfiguratorContract $config): void
    {
        $core
            ->resources([
                MoonShineUserResource::class,
                MoonShineUserRoleResource::class,
                UsersResource::class,
                CatalogResource::class,
                GenreResource::class,
                AuthorComicsResource::class,
                RatingResource::class,
                SubscribeResource::class,
                CommentsResource::class,
                EventsResource::class,
                GuestsResource::class,
                TagsResource::class,
                MultimediaPostResource::class,
                MediaResource::class,
            ])
            ->pages([
                ...$config->getPages(),
            ])
        ;
    }
}
