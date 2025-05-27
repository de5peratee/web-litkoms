<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use App\Models\AuthorComics;

use MoonShine\Laravel\Fields\Relationships\BelongsTo;
use MoonShine\Laravel\Fields\Relationships\BelongsToMany;
use MoonShine\Laravel\Fields\Slug;
use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\Support\DTOs\FileItemExtra;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Components\Layout\Column;
use MoonShine\UI\Components\Layout\Grid;
use MoonShine\UI\Components\When;
use MoonShine\UI\Fields\Checkbox;
use MoonShine\UI\Fields\File;
use MoonShine\UI\Fields\ID;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\UI\Fields\Image;
use MoonShine\UI\Fields\Number;
use MoonShine\UI\Fields\Select;
use MoonShine\UI\Fields\Switcher;
use MoonShine\UI\Fields\Text;
use MoonShine\UI\Fields\Textarea;
use PhpParser\Node\Stmt\Block;

/**
 * @extends ModelResource<AuthorComics>
 */
class AuthorComicsResource extends ModelResource
{
    protected string $model = AuthorComics::class;

    protected string $title = 'Авторские комиксы';

    /**
     * @return list<FieldContract>
     */
    protected function indexFields(): iterable
    {
        return [
            ID::make()->sortable(),
            BelongsTo::make(
                'Автор комикса',
                'createdBy',
                'nickname',
                resource: UsersResource::class
            )->placeholder('Никнейм')->searchable(),
            Text::make('Название', 'name')->sortable(),
            Select::make('Возрастное ограничение', 'age_restriction')->options([
                '6' => '6+',
                '12' => '12+',
                '16' => '16+',
                '18' => '18+',
            ])->sortable(),
            Select::make('Модерация', 'is_moderated')->options([
                'successful' => 'Принят',
                'unsuccessful' => 'Не принят',
                'under review' => 'На рассмотрении',
            ]),
            Switcher::make('Опубликован', 'is_published'),
        ];
    }

    /**
     * @return list<ComponentContract|FieldContract>
     */
    protected function formFields(): iterable
    {
        return [
            Box::make('Информация о комиксе', [
                ID::make(),
                Text::make('Название', 'name')->reactive()->required(),
                Textarea::make('Описание', 'description')->required(),
                Grid::make([
                    Column::make([
                        Image::make('Обложка', 'cover')->allowedExtensions(['png','jpg','jpeg'])
                            ->extraAttributes(fn(string $filename, int $index): ?FileItemExtra => new FileItemExtra(wide: false, auto: true, styles: 'width: 250px;'))
                            ->removable()
                            ->dir('author_comics_covers')
                            ->disk('public'),
                    ])->columnSpan(6),

                    Column::make([
                        File::make('Файл комикса (.pdf)', 'comics_file')
                            ->allowedExtensions(['pdf'])
                            ->removable()
                            ->dir('comics_files')
                            ->disk('public'),
                    ])->columnSpan(6),
                ]),
                Grid::make([
                    Column::make([
                        Select::make('Возрастное ограничение', 'age_restriction')->options([
                            '6' => '6+',
                            '12' => '12+',
                            '16' => '16+',
                            '18' => '18+',
                        ])->required(),
                    ])->columnSpan(4),

                    Column::make([
                        BelongsToMany::make(
                            'Жанры',
                            'genres',
                            'name',
                            resource: GenreResource::class
                        )->selectMode()->placeholder('Жанры')->required(),
                    ])->columnSpan(4),
                    Column::make([
                        BelongsTo::make(
                            'Автор комикса',
                            'createdBy',
                            'nickname',
                            resource: UsersResource::class
                        )->placeholder('Никнейм')->searchable()->required(),
                    ])->columnSpan(4),
                ]),

            ]),

            Box::make('Модерация', [

                Select::make('Модерация', 'is_moderated')->options([
                    'successful' => 'Принят',
                    'unsuccessful' => 'Не принят',
                    'under review' => 'На рассмотрении',
                ])->required(),
                Textarea::make('Обратная связь (при непринятом комиксе)', 'feedback')->nullable(),
                Switcher::make('Опубликовать', 'is_published'),
                Slug::make('Идентификация ресурса', 'slug')
                    ->from('name')->unique()->live()->locked(),
            ]),
        ];
    }

    /**
     * @return list<FieldContract>
     */
    protected function detailFields(): iterable
    {
        return [
            ID::make(),
            Text::make('Название', 'name')->reactive()->required(),
            BelongsTo::make(
                'Автор комикса',
                'createdBy',
                'nickname',
                resource: UsersResource::class
            )->placeholder('Никнейм')->searchable()->required(),
            Textarea::make('Описание', 'description')->required(),
            Image::make('Обложка', 'cover'),
            File::make('Файл комикса', 'comics_file'),
            Select::make('Возрастное ограничение', 'age_restriction')->options([
                '6' => '6+',
                '12' => '12+',
                '16' => '16+',
                '18' => '18+',
            ]),
            BelongsToMany::make(
                'Жанры',
                'genres',
                'name',
                resource: GenreResource::class
            )->selectMode()->placeholder('Жанры')->inLine(separator: ', '),
            Select::make('Модерация', 'is_moderated')->options([
                'successful' => 'Принят',
                'unsuccessful' => 'Не принят',
                'under review' => 'На рассмотрении',
            ]),
            Slug::make('Идентификация ресурса', 'slug')->from('name'),
            Textarea::make('Обратная связь (при непринятом комиксе)', 'feedback'),
        ];
    }

    /**
     * @param AuthorComics $item
     *
     * @return array<string, string[]|string>
     * @see https://laravel.com/docs/validation#available-validation-rules
     */
    protected function rules(mixed $item): array
    {
        return [
            'name' => ['required', 'string', 'max:255', 'min:3'],
            'genres' => ['required', 'max:255'],
            'description' => ['required', 'string', 'min:10', 'max:5000'],

            'comics_file' => [
                $item?->comics_file ? 'nullable' : 'required',
                'file',
                'mimes:pdf',
                'max:20480'
            ],
            'cover' => [
                $item?->cover ? 'nullable' : 'required',
                'file',
                'mimes:jpg,jpeg,png',
                'max:5120'
            ],

            'age_restriction' => ['required', 'in:6,12,16,18'],
        ];
    }
}
