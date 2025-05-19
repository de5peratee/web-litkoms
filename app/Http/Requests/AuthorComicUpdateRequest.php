<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

class AuthorComicUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        Log::info('Request data:', ['data' => $this->all()]);
        Log::info('Request files:', ['files' => $this->files->all()]);

        return [
            'title' => ['required', 'string', 'max:255', 'min:3'],
            'genres' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'min:10', 'max:5000'],
            'comic_file' => ['file', 'mimes:pdf', 'max:20480'],
            'cover' => ['file', 'mimes:jpg,jpeg,png', 'max:5120'],
            'age_restriction' => ['required', 'in:6,12,16,18'],
        ];
    }

    /**
     * Get custom messages for validation errors.
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Название комикса обязательно для заполнения.',
            'title.min' => 'Название комикса должно содержать не менее 3 символов.',
            'title.max' => 'Название комикса не должно превышать 255 символов.',
            'genres.required' => 'Укажите хотя бы один жанр.',
            'genres.max' => 'Список жанров не должен превышать 255 символов.',
            'description.required' => 'Описание комикса обязательно для заполнения.',
            'description.min' => 'Описание должно содержать не менее 10 символов.',
            'description.max' => 'Описание не должно превышать 5000 символов.',
            'comic_file.required' => 'Файл комикса обязателен для загрузки.',
            'comic_file.mimes' => 'Файл комикса должен быть в формате PDF.',
            'comic_file.max' => 'Файл комикса не должен превышать 20 МБ.',
            'cover.required' => 'Обложка комикса обязательна для загрузки.',
            'cover.mimes' => 'Обложка должна быть в формате JPG или PNG.',
            'cover.max' => 'Обложка не должна превышать 5 МБ.',
            'age_restriction.required' => 'Укажите возрастное ограничение.',
            'age_restriction.in' => 'Возрастное ограничение должно быть одним из: 6+, 12+, 16+, 18+.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
}