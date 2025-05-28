<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMultimediaPostRequest extends FormRequest
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
            'media' => ['nullable', 'array', 'max:10'],
            'media.*' => ['file', 'mimetypes:image/*,video/*,application/pdf', 'max:10240'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Введите название поста.',
            'description.required' => 'Введите описание.',
            'media.*.file' => 'Каждый медиафайл должен быть файлом.',
            'media.*' => ['file', 'mimetypes:image/*,video/*,application/pdf', 'max:10240'],
            'media.*.max' => 'Максимальный размер файла: 10MB.',
            'media.max' => 'Максимальное количество медиафайлов: 10.',
        ];
    }
}
