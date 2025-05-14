<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMultimediaPostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'media' => ['nullable', 'array'],
            'media.*' => ['file', 'mimetypes:image/*,video/*', 'max:10240'], // до 10MB на файл
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Введите название поста.',
            'description.required' => 'Введите описание.',
            'media.*.file' => 'Каждый медиафайл должен быть файлом.',
            'media.*.mimetypes' => 'Допустимы только изображения и видео.',
            'media.*.max' => 'Максимальный размер файла: 10MB.',
        ];
    }
}
