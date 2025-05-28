<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Model;
use App\Models\Subscribe;

use Illuminate\Validation\Rule;
use MoonShine\Laravel\Fields\Relationships\BelongsTo;
use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Fields\ID;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Contracts\UI\ComponentContract;

/**
 * @extends ModelResource<Subscribe>
 */
class SubscribeResource extends ModelResource
{
    protected string $model = Subscribe::class;
    protected string $title = 'Подписки';
    /**
     * @return list<FieldContract>
     */
    protected function indexFields(): iterable
    {
        return [
            ID::make()->sortable(),
            BelongsTo::make(
                'Автор',
                'author',
                'nickname',
                resource: UsersResource::class
            )->placeholder('Никнейм')->searchable()->required(),
            BelongsTo::make(
                'Подписчик',
                'subscriber',
                'nickname',
                resource: UsersResource::class
            )->placeholder('Никнейм')->searchable()->required(),
        ];
    }

    /**
     * @return list<ComponentContract|FieldContract>
     */
    protected function formFields(): iterable
    {
        return [
            Box::make([
                BelongsTo::make(
                    'Автор',
                    'author',
                    'nickname',
                    resource: UsersResource::class
                )->required(),
                BelongsTo::make(
                    'Подписчик',
                    'subscriber',
                    'nickname',
                    resource: UsersResource::class
                )->required(),
            ])
        ];
    }

    /**
     * @return list<FieldContract>
     */
    protected function detailFields(): iterable
    {
        return [
                BelongsTo::make(
                'Автор',
                'author',
                'nickname',
                resource: UsersResource::class
            )->required()->placeholder('Никнейм')->searchable(),

            BelongsTo::make(
                'Подписчик',
                'subscriber',
                'nickname',
                resource: UsersResource::class
            )->required()->placeholder('Никнейм')->searchable(),
        ];
    }

    /**
     * @param Subscribe $item
     *
     * @return array<string, string[]|string>
     * @see https://laravel.com/docs/validation#available-validation-rules
     */
    protected function rules(mixed $item): array
    {
        return [
            'subscribed_to_id' => ['required'],
            'subscriber_id' => [
                'required',
                Rule::unique('subscribes')
                    ->where(function ($query) {
                        return $query->where('subscribed_to_id', request('subscribed_to_id'))
                            ->where('subscriber_id', request('subscriber_id'));
                    })
            ],
        ];
    }

    public function validationMessages(): array
    {
        return [
            'subscribed_to_id.different' => 'Нельзя подписаться на себя.',
            'subscriber_id.unique' => 'Такая подписка уже существует.',
        ];
    }

}
