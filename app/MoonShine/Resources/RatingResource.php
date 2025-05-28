<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use App\Models\AuthorComics;
use Illuminate\Database\Eloquent\Model;
use App\Models\Rating;

use MoonShine\Laravel\Fields\Relationships\BelongsTo;
use MoonShine\Laravel\Fields\Relationships\BelongsToMany;
use MoonShine\Laravel\Fields\Relationships\HasMany;
use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Fields\ID;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\UI\Fields\Number;
use MoonShine\UI\Fields\Select;

/**
 * @extends ModelResource<Rating>
 */
class RatingResource extends ModelResource
{
    protected string $model = Rating::class;
    protected string $title = 'Рейтинг';
    
    /**
     * @return list<FieldContract>
     */
    protected function indexFields(): iterable
    {
        return [
            ID::make()->sortable(),
            BelongsTo::make(
                'Пользователь',
                'rater',
                'nickname',
                resource: UsersResource::class
            )->placeholder('Никнейм'),
            BelongsTo::make(
                'Комикс',
                'comic',
                'name',
                resource: AuthorComicsResource::class
            )->placeholder('Название комикса'),
            Number::make('Оценка', 'grade'),

        ];
    }

    /**
     * @return list<ComponentContract|FieldContract>
     */
    protected function formFields(): iterable
    {
        return [
            Box::make([
                ID::make(),
                BelongsTo::make(
                    'Пользователь',
                    'rater',
                    'nickname',
                    resource: UsersResource::class
                )->placeholder('Никнейм')->searchable()->required(),
                BelongsTo::make(
                    'Комикс',
                    'comic',
                    fn($item) => "{$item->name} ({$item->slug})",
                    resource: AuthorComicsResource::class
                )->placeholder('Название комикса')->searchable()->required(),
                Number::make('Оценка', 'grade')->min(1)->max(5)->required(),
            ]),
        ];
    }

    /**
     * @return list<FieldContract>
     */
    protected function detailFields(): iterable
    {
        return [
            ID::make()->sortable(),
            BelongsTo::make(
                'Пользователь',
                'rater',
                'nickname',
                resource: UsersResource::class
            )->placeholder('Никнейм'),
            BelongsTo::make(
                'Комикс',
                'comic',
                fn($item) => "{$item->name} ({$item->id}, {$item->slug})",
                resource: AuthorComicsResource::class
            )->placeholder('Название комикса'),
            Number::make('Оценка', 'grade'),
        ];
    }

    /**
     * @param Rating $item
     *
     * @return array<string, string[]|string>
     * @see https://laravel.com/docs/validation#available-validation-rules
     */
    protected function rules(mixed $item): array
    {
        return [];
    }
}
