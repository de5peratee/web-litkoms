<?php

declare(strict_types=1);

namespace App\MoonShine\Layouts;

use App\Models\Comments;
use MoonShine\Laravel\Layouts\AppLayout;
use MoonShine\ColorManager\ColorManager;
use MoonShine\Contracts\ColorManager\ColorManagerContract;
use MoonShine\Laravel\Components\Layout\{Locales, Notifications, Profile, Search};
use MoonShine\UI\Components\{Breadcrumbs,
    Components,
    Layout\Flash,
    Layout\Div,
    Layout\Body,
    Layout\Burger,
    Layout\Content,
    Layout\Footer,
    Layout\Head,
    Layout\Favicon,
    Layout\Assets,
    Layout\Meta,
    Layout\Header,
    Layout\Html,
    Layout\Layout,
    Layout\Logo,
    Layout\Menu,
    Layout\Sidebar,
    Layout\ThemeSwitcher,
    Layout\TopBar,
    Layout\Wrapper,
    When
};
use App\MoonShine\Resources\UsersResource;
use MoonShine\MenuManager\MenuDivider;
use MoonShine\MenuManager\MenuGroup;
use MoonShine\MenuManager\MenuItem;
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

final class MoonShineLayout extends AppLayout
{
    protected function assets(): array
    {
        return [
            ...parent::assets(),
        ];
    }

    protected function menu(): array
    {
        return [
            ...parent::menu(),
            MenuGroup::make('Пользователи', [
                MenuItem::make('Пользователи', UsersResource::class),
                MenuItem::make('Подписки', SubscribeResource::class),
            ]),

            MenuDivider::make(),

            MenuGroup::make('Авторский комикс', [
                MenuItem::make('Авторский комикс', AuthorComicsResource::class),
                MenuItem::make('Рейтинг', RatingResource::class),
                MenuItem::make('Комментарии', CommentsResource::class),
            ]),
            MenuItem::make('Каталог', CatalogResource::class),
            MenuItem::make('Жанры', GenreResource::class),

            MenuDivider::make(),

            MenuGroup::make('Событие', [
                MenuItem::make('Событие', EventsResource::class),
                MenuItem::make('Гости', GuestsResource::class),
                MenuItem::make('Теги', TagsResource::class),
            ]),

            MenuGroup::make('Мультимедиа пост', [
                MenuItem::make('Мультимедиа пост', MultimediaPostResource::class),
                MenuItem::make('Медиа', MediaResource::class),
            ]),
        ];
    }

    /**
     * @param ColorManager $colorManager
     */
    protected function colors(ColorManagerContract $colorManager): void
    {
        parent::colors($colorManager);

        // $colorManager->primary('#00000');
    }

    public function build(): Layout
    {
        return parent::build();
    }
}
