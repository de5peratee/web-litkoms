<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class UpdateEventRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'cover' => 'image|mimes:jpeg,png,jpg|max:4096',
            'name' => 'required|string|min:3|max:255',
            'description' => 'required|string|min:10|max:5000',
            'start_date' => 'required|date_format:Y-m-d|after_or_equal:today',
            'time' => 'required|date_format:H:i',
            'guests' => 'nullable|string|max:1000|regex:/^[\p{L}\s,]+$/u',
            'tags' => 'required|string|max:1000|regex:/^[\p{L}\s,]+$/u',
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            if ($this->filled('guests')) {
                $guests = array_filter(array_map('trim', explode(',', $this->input('guests'))));
                $guestCount = count($guests);
                if (count(array_unique(array_map('strtolower', $guests))) < $guestCount) {
                    $validator->errors()->add('guests', 'Гости не должны повторяться.');
                }
                if ($guestCount > 10) {
                    $validator->errors()->add('guests', 'Количество гостей не должно превышать 7.');
                }
            }

            $tags = array_filter(array_map('trim', explode(',', $this->input('tags'))));
            $tagsCount = count($tags);

            if (count(array_unique(array_map('strtolower', $tags))) < $tagsCount) {
                $validator->errors()->add('tags', 'Теги не должны повторяться.');
            }
            if ($tagsCount > 5) {
                $validator->errors()->add('tags', 'Количество тегов не должно превышать 7.');
            }
        });
    }

    public function messages(): array
    {
        return [
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
            'tags.required' => 'Теги обязательны.',
            'tags.max' => 'Список тегов не должен превышать 1000 символов.',
            'tags.regex' => 'Список жанров может содержать только буквы, пробелы и запятые.',
        ];
    }
}