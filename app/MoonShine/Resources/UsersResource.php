<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\Support\DTOs\FileItemExtra;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Components\Layout\Column;
use MoonShine\UI\Components\Layout\Grid;
use MoonShine\UI\Components\Layout\LineBreak;
use MoonShine\UI\Fields\Date;
use MoonShine\UI\Fields\Email;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Image;
use MoonShine\UI\Fields\Password;
use MoonShine\UI\Fields\PasswordRepeat;
use MoonShine\UI\Fields\Select;
use MoonShine\UI\Fields\Text;
use MoonShine\UI\Fields\Textarea;


class UsersResource extends ModelResource
{
    protected string $model = User::class;

    protected string $title = 'Пользователи';


    protected function indexFields(): iterable
    {
        return [
            ID::make()->sortable(),
            Text::make('Никнейм', 'nickname'),
            Select::make('Роль', 'role')
                ->options([
                    'user' => 'Пользователь',
                    'editor' => 'Редактор',
                ]),
            Email::make('Email', 'email'),
            Text::make('Имя', 'name'),
            Text::make('Фамилия', 'last_name'),
        ];
    }


    protected function formFields(): iterable
    {
        return [

            Box::make('Общая информация', [
                ID::make()->sortable(),

                Grid::make([
                    Column::make([
                        Text::make('Никнейм', 'nickname')->required(),
                    ])->columnSpan(3),

                    Column::make([
                        Email::make('Email', 'email')->required(),
                    ])->columnSpan(3),
                    Column::make([
                        Text::make('Имя', 'name')->required(),
                    ])->columnSpan(3),
                    Column::make([
                        Text::make('Фамилия', 'last_name')->required(),
                    ])->columnSpan(3),

                ]),

                Grid::make([
                    Column::make([
                        Date::make('Дата рождения', 'birth_date')->required(),
                    ])->columnSpan(4),

                    Column::make([
                        Select::make('Роль', 'role')
                            ->options([
                                'user' => 'Пользователь',
                                'editor' => 'Редактор',
                            ]),
                        ])->columnSpan(4),

                    Column::make([
                        Date::make('Дата верификации (подтверждения почты)', 'email_verified_at')->withTime(),
                    ])->columnSpan(4),

                    ]),

                Grid::make([
                    Column::make([
                        Textarea::make('Описание', 'about')->nullable(),

                    ])->columnSpan(6),

                    Column::make([
                        Image::make('Иконка', 'icon')
                            ->disk('public')
                            ->extraAttributes(fn(string $filename, int $index): ?FileItemExtra => new FileItemExtra(wide: false, auto: true, styles: 'width: 250px;'))
                            ->dir('icon_user')
                            ->nullable()
                            ->allowedExtensions(['png', 'jpg', 'jpeg']),

                        Image::make('Шапка профиля', 'head_profile')
                            ->dir('head_profile')
                            ->disk('public')
                            ->extraAttributes(fn(string $filename, int $index): ?FileItemExtra => new FileItemExtra(wide: false, auto: true, styles: 'width: 250px;'))
                            ->nullable()
                            ->allowedExtensions(['png', 'jpg', 'jpeg']),
                    ])->columnSpan(6),

                ]),

            ]),

            LineBreak::make(),

            Box::make('Пароль', [

                Password::make('Пароль', 'password')
                    ->customAttributes(['autocomplete' => 'new-password']),

                PasswordRepeat::make('Повторите пароль', 'password_repeat')
                    ->customAttributes(['autocomplete' => 'confirm-password']),
            ]),
        ];
    }

    protected function detailFields(): iterable
    {
        return [
            ID::make(),
            Text::make('Никнейм', 'nickname'),
            Email::make('Email', 'email'),
            Text::make('Имя', 'name'),
            Text::make('Фамилия', 'last_name'),
            Date::make('Дата рождения', 'birth_date'),
            Textarea::make('Описание', 'about'),
            Image::make('Иконка', 'icon'),
            Image::make('Шапка профиля', 'head_profile'),
            Text::make('Роль', 'role'),
            Date::make('Дата верификации (подтверждения почты)', 'email_verified_at'),
        ];
    }

    /**
     * @param User $item
     * @return array<string, string[]|string>
     */
    protected function rules(mixed $item): array
    {
        return [
            'nickname' => ['required', 'string', 'min:5', 'max:20', 'regex:/^[a-zA-Z0-9_-]+$/', 'unique:users,nickname,' . $item->id],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $item->id],
            'name' => ['required', 'string', 'max:100', 'regex:/^[a-zA-Zа-яА-ЯёЁ]+$/u'],
            'last_name' => ['required', 'string', 'max:100', 'regex:/^[a-zA-Zа-яА-ЯёЁ]+$/u'],
            'birth_date' => ['required', 'date'],
            'password' => !$item->exists
                ? 'required|min:8|required_with:password_repeat|same:password_repeat'
                : 'sometimes|nullable|min:8|required_with:password_repeat|same:password_repeat',
        ];
    }
}
