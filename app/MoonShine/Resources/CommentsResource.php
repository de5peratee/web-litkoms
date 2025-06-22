<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Model;
use App\Models\Comments;

use MoonShine\Laravel\Fields\Relationships\BelongsTo;
use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Fields\ID;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\UI\Fields\Textarea;

/**
 * @extends ModelResource<Comments>
 */
class CommentsResource extends ModelResource
{
    protected string $model = Comments::class;

    protected string $title = 'Комментарии';
    
    /**
     * @return list<FieldContract>
     */
    protected function indexFields(): iterable
    {
        return [
            ID::make()->sortable(),
            BelongsTo::make(
                'Комментатор',
                'createdBy',
                'nickname',
                resource: UsersResource::class
            )->sortable(),
            BelongsTo::make(
                'Комикс',
                'authorComic',
                'name',
                resource: UsersResource::class
            )->sortable(),
            Textarea::make('Комментарий', 'comment')->sortable(),
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
                    'Комментатор',
                    'createdBy',
                    'nickname',
                    resource: UsersResource::class
                )->required(),
                BelongsTo::make(
                    'Комикс',
                    'authorComic',
                    'name',
                    resource: UsersResource::class
                )->required(),
                Textarea::make('Комментарий', 'comment')->required(),
            ])
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
                'Комментатор',
                'createdBy',
                'nickname',
                resource: UsersResource::class
            )->sortable(),
            BelongsTo::make(
                'Комикс',
                'authorComic',
                'name',
                resource: UsersResource::class
            )->sortable(),
            Textarea::make('Комментарий', 'comment')->sortable(),
        ];
    }

    /**
     * @param Comments $item
     *
     * @return array<string, string[]|string>
     * @see https://laravel.com/docs/validation#available-validation-rules
     */
    protected function rules(mixed $item): array
    {
        return [];
    }

    public function search(): array
    {
        return ['comment'];
    }
}
