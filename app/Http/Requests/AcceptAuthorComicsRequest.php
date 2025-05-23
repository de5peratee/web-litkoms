<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AcceptAuthorComicsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'age_restriction' => 'required|in:6,12,16,18',
            'feedback' => $this->moderation_status === 'unsuccessful' ? 'required|string|max:1000' : 'nullable|string|max:1000',
            'moderation_status' => 'required|in:successful,unsuccessful'
        ];
    }

    public function messages(): array
    {
        return [
            'age_restriction.required' => 'Ограничение по возрасту обязательно.',
            'age_restriction.in' => 'Ограничение по возрасту должно быть одним из: 6, 12, 16, 18.',
            'feedback.required' => 'Обратная связь обязательна при неуспешной модерации.',
            'feedback.string' => 'Обратная связь должна быть текстом.',
            'feedback.max' => 'Обратная связь не должна превышать 1000 символов.',
            'moderation_status.required' => 'Статус модерации обязателен.',
            'moderation_status.in' => 'Статус модерации должен быть: успешный или неуспешный.'
        ];
    }

}
