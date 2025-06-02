<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use App\Models\Event;
use Illuminate\Database\Eloquent\Model;

use MoonShine\Laravel\Fields\Relationships\BelongsTo;
use MoonShine\Laravel\Fields\Relationships\BelongsToMany;
use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\Support\DTOs\FileItemExtra;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Fields\Date;
use MoonShine\UI\Fields\ID;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\UI\Fields\Image;
use MoonShine\UI\Fields\Text;
use MoonShine\UI\Fields\Textarea;

/**
 * @extends ModelResource<Events>
 */
class EventsResource extends ModelResource
{
    protected string $model = Event::class;

    protected string $title = 'События';
    
    /**
     * @return list<FieldContract>
     */
    protected function indexFields(): iterable
    {
        return [
            ID::make()->sortable(),
            Text::make('Название события', 'name')->sortable(),
            Image::make('Обложка', 'cover')
                ->extraAttributes(fn(string $filename, int $index): ?FileItemExtra => new FileItemExtra(wide: false, auto: true, styles: 'width: 250px;'))
                ->dir('icon_user')
                ->disk('public')
                ->nullable()
                ->allowedExtensions(['png', 'jpg', 'jpeg']),
            Date::make('Дата начала', 'start_date'),
            Date::make('Дата окончания', 'end_date'),
            BelongsToMany::make(
                'Гости',
                'guests',
                fn($item) => "{$item->name} {$item->surname}",
                resource: GenreResource::class
            )->selectMode()->placeholder('Гости')->inLine(separator: ', '),
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
                Text::make('Название события', 'name')->required(),
                BelongsToMany::make(
                    'Гости',
                    'guests',
                    fn($item) => "{$item->name} {$item->surname}",
                    resource: GenreResource::class
                )->selectMode()->placeholder('Гости')->inLine(separator: ', '),

                BelongsToMany::make(
                    'Теги',
                    'tags',
                    'name',
                    resource: GenreResource::class
                )->selectMode()->placeholder('Теги')->required(),

                Date::make('Дата начала', 'start_date')->withTime()->required(),
                Date::make('Дата окончания', 'end_date')->withTime(),

                Textarea::make('Описание','description')->required(),

                Image::make('Обложка', 'cover')
                    ->extraAttributes(fn(string $filename, int $index): ?FileItemExtra => new FileItemExtra(wide: false, auto: true, styles: 'width: 250px;'))
                    ->dir('icon_user')
                    ->disk('public')
                    ->nullable()
                    ->allowedExtensions(['png', 'jpg', 'jpeg']),

                BelongsTo::make(
                    'Создатель события',
                    'createdBy',
                    'nickname',
                    resource: UsersResource::class
                )->placeholder('Никнейм')->required(),
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
            Text::make('Название события', 'name')->required(),
            BelongsToMany::make(
                'Гости',
                'guests',
                fn($item) => "{$item->name} {$item->surname}",
                resource: GenreResource::class
            )->selectMode()->placeholder('Гости')->inLine(separator: ', '),

            BelongsToMany::make(
                'Теги',
                'tags',
                'name',
                resource: GenreResource::class
            )->selectMode()->placeholder('Теги')->inLine(separator: ', ')->required(),

            Date::make('Дата начала', 'start_date')->withTime()->required(),
            Date::make('Дата окончания', 'end_date')->withTime(),

            Textarea::make('Описание','description')->required(),

            Image::make('Обложка', 'cover')
                ->extraAttributes(fn(string $filename, int $index): ?FileItemExtra => new FileItemExtra(wide: false, auto: true, styles: 'width: 250px;'))
                ->dir('icon_user')
                ->disk('public')
                ->nullable()
                ->allowedExtensions(['png', 'jpg', 'jpeg']),

            BelongsTo::make(
                'Создатель события',
                'createdBy',
                'nickname',
                resource: UsersResource::class
            )->placeholder('Никнейм')->required(),
        ];
    }

    /**
     * @param Events $item
     *
     * @return array<string, string[]|string>
     * @see https://laravel.com/docs/validation#available-validation-rules
     */
    protected function rules(mixed $item): array
    {
        return [
            'cover' => 'image|mimes:jpeg,png,jpg|max:4096',
            'name' => 'required|string|min:3|max:255',
            'description' => 'required|string|min:10|max:5000',
            'start_date' => ['required', 'date_format:Y-m-d\TH:i', 'after_or_equal:now'],
            'end_date' => ['date_format:Y-m-d\TH:i', 'after_or_equal:start_date'],
            'guests' => 'nullable|max:1000',
            'tags' => 'required|max:1000',
        ];
    }

    public function validationMessages(): array
    {
        return [
            'cover.image' => 'Файл обложки должен быть изображением.',
            'cover.mimes' => 'Допустимые форматы обложки: jpeg, png, jpg.',
            'cover.max' => 'Максимальный размер изображения — 4MB.',

            'name.required' => 'Название события обязательно.',
            'name.string' => 'Название должно быть строкой.',
            'name.min' => 'Название должно содержать не менее 3 символов.',
            'name.max' => 'Название не должно превышать 255 символов.',

            'description.required' => 'Описание события обязательно.',
            'description.string' => 'Описание должно быть строкой.',
            'description.min' => 'Описание должно быть не короче 10 символов.',
            'description.max' => 'Описание не должно превышать 5000 символов.',

            'start_date.required' => 'Дата и время начала обязательны.',
            'start_date.date_format' => 'Дата начала должна быть в формате: ГГГГ-ММ-ДДTчч:мм.',
            'start_date.after_or_equal' => 'Дата начала не может быть раньше текущего времени.',

            'end_date.date_format' => 'Дата окончания должна быть в формате: ГГГГ-ММ-ДД чч:мм:сс.',
            'end_date.after_or_equal' => 'Дата окончания не может быть раньше даты начала.',

            'guests.max' => 'Список гостей слишком длинный (до 1000 символов).',

            'tags.required' => 'Укажите хотя бы один тег.',
            'tags.max' => 'Список тегов слишком длинный (до 1000 символов).',
        ];
    }


}
