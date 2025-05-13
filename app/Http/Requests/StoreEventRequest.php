<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEventRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'cover' => 'required|image|mimes:jpeg,png,jpg|max:4096',
            'name' => 'required|string|min:3|max:255',
            'description' => 'required|string|min:10|max:5000',
            'start_date' => 'required|date_format:Y-m-d|after_or_equal:today',
            'time' => 'required|date_format:H:i',
            'guests' => 'nullable|string|max:1000|regex:/^[\p{L}\s,]+$/u',
            'genres' => 'required|string|max:1000|regex:/^[\p{L}\s,]+$/u',
        ];
    }

    public function messages(): array
    {
        return [
            'cover.required' => 'Обложка обязательна для загрузки.',
            'cover.image' => 'Обложка должна быть изображением.',
            'cover.mimes' => 'Обложка должна быть в формате JPEG, PNG или JPG.',
            'cover.max' => 'Размер обложки не должен превышать 4 МБ.',
            'name.required' => 'Название мероприятия обязательно.',
            'name.min' => 'Название должно содержать минимум 3 символа.',
            'name.max' => 'Название не должно превышать 255 символов.',
            'description.required' => 'Описание мероприятия обязательно.',
            'description.min' => 'Описание должно содержать минимум 10 символов.',
            'description.max' => 'Описание не должно превышать 5000 символов.',
            'start_date.required' => 'Дата начала обязательна.',
            'start_date.date_format' => 'Дата должна быть в формате ГГГГ-ММ-ДД.',
            'start_date.after_or_equal' => 'Дата начала не может быть в прошлом.',
            'time.required' => 'Время начала обязательно.',
            'time.date_format' => 'Время должно быть в формате ЧЧ:ММ.',
            'guests.max' => 'Список гостей не должен превышать 1000 символов.',
            'guests.regex' => 'Список гостей может содержать только буквы, пробелы и запятые.',
            'genres.required' => 'Жанры обязательны.',
            'genres.max' => 'Список жанров не должен превышать 1000 символов.',
            'genres.regex' => 'Список жанров может содержать только буквы, пробелы и запятые.',
        ];
    }
}