<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Model;
use App\Models\Catalog;

use MoonShine\Laravel\Fields\Relationships\BelongsToMany;
use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Fields\Date;
use MoonShine\UI\Fields\ID;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\UI\Fields\Image;
use MoonShine\UI\Fields\Number;
use MoonShine\UI\Fields\Text;

/**
 * @extends ModelResource<Catalog>
 */
class CatalogResource extends ModelResource
{
    protected string $model = Catalog::class;

    protected string $title = 'Каталог';
    
    /**
     * @return list<FieldContract>
     */
    protected function indexFields(): iterable
    {
        return [
            ID::make()->sortable(),
            Text::make('Название', 'name')->sortable(),
            Text::make('Автор(ы)', 'author')->sortable(),
            Text::make('Описание', 'description')->sortable(),
            Number::make('Год выпуска', 'release_year')->sortable()
                ->min(1900)
                ->max((int) date('Y')),
            Image::make('Обложка', 'cover'),
            BelongsToMany::make(
                'Жанры',
                'genres',
                'name',
                resource: GenreResource::class
            )->onlyCount()
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
                Text::make('Название', 'name'),
                Text::make('Автор(ы)', 'author'),
                Text::make('Описание', 'description'),
                Number::make('Год выпуска', 'release_year')
                    ->min(1900)
                    ->max((int) date('Y')),
                Image::make('Обложка', 'cover')
                    ->dir('catalog_covers')
                    ->disk('public'),
                BelongsToMany::make(
                    'Жанры',
                    'genres',
                    'name',
                    resource: GenreResource::class
                )->selectMode()->placeholder('Жанры')
            ])
        ];
    }

    /**
     * @return list<FieldContract>
     */
    protected function detailFields(): iterable
    {
        return [
            ID::make(),
            Text::make('Название', 'name'),
            Text::make('Автор(ы)', 'author'),
            Text::make('Описание', 'description'),
            Number::make('Год выпуска', 'release_year')
                ->min(1900)
                ->max((int) date('Y')),
            Image::make('Обложка', 'cover'),
            BelongsToMany::make(
                'Жанры',
                'genres',
                'name',
                resource: GenreResource::class
            )->inLine(separator: ', ')
        ];
    }

    /**
     * @param Catalog $item
     *
     * @return array<string, string[]|string>
     * @see https://laravel.com/docs/validation#available-validation-rules
     */
    protected function rules(mixed $item): array
    {
        return [
            'name' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'description' => 'nullable|string',
            'release_year' => 'nullable|integer|min:1800|max:' . date('Y'),
            'genres' => 'nullable',
            'cover' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
        ];
    }

    public function search(): array
    {
        return ['name', 'author', 'description', 'release_year'];
    }
}
