<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Model;
use App\Models\MultimediaPost;

use MoonShine\Laravel\Fields\Relationships\BelongsTo;
use MoonShine\Laravel\Fields\Relationships\BelongsToMany;
use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Fields\ID;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\UI\Fields\Image;
use MoonShine\UI\Fields\Text;
use MoonShine\UI\Fields\Textarea;

class MultimediaPostResource extends ModelResource
{
    protected string $model = MultimediaPost::class;
    public string $column = 'file';

    protected string $title = 'Мультимедиа пост';

    protected function indexFields(): iterable
    {
        return [
            ID::make()->sortable(),
            Text::make('Название', 'name'),
            Textarea::make('Описание', 'description'),
            ];
    }

    protected function formFields(): iterable
    {
        return [
            Box::make([
                ID::make(),
                Text::make('Название', 'name'),
                Textarea::make('Описание', 'description'),
                BelongsTo::make('Создатель поста', 'createdBy', 'nickname', resource: UsersResource::class),
                BelongsToMany::make(
                    'Медиа',
                    'medias',
                    'id',
                    resource: MediaResource::class
                )
                    ->withImage('file', 'public', 'mediapost_media')
                    ->selectMode()
                    ->placeholder('Выберите медиа')
            ])
        ];
    }

    protected function detailFields(): iterable
    {
        return [
            ID::make(),
            Text::make('Название', 'name'),
            Textarea::make('Описание', 'description'),
            BelongsToMany::make(
                'Медиа',
                'medias',
                'file',
                resource: MediaResource::class,
            )
                ->selectMode()
                ->placeholder('Выберите медиа')
        ];
    }

    protected function rules(mixed $item): array
    {
        return [];
    }
    public function search(): array
    {
        return ['name', 'description'];
    }
}
