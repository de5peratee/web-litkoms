<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCatalogEditorRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'description' => 'nullable|string',
            'release_year' => 'nullable|integer|min:1800|max:' . date('Y'),
            'genres' => 'nullable|string',
            'cover' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Название обязательно',
            'name.string' => 'Название должно быть строкой',
            'name.max' => 'Название не должно превышать 255 символов',
            'author.required' => 'Автор обязателен',
            'author.string' => 'Автор должен быть строкой',
            'author.max' => 'Имя автора не должно превышать 255 символов',
            'description.string' => 'Описание должно быть строкой',
            'release_year.integer' => 'Год выпуска должен быть числом',
            'release_year.min' => 'Год выпуска не может быть раньше 1800',
            'release_year.max' => 'Год выпуска не может быть позже текущего года',
            'genres.string' => 'Жанры должны быть строкой',
            'cover.image' => 'Обложка должна быть изображением',
            'cover.mimes' => 'Обложка должна быть в формате jpeg, png или jpg',
            'cover.max' => 'Размер обложки не должен превышать 5МБ',
        ];
    }
}