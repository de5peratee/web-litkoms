<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

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
        Log::info('AcceptAuthorComicsRequest input:', $this->all());

        return [
            'moderation_status' => 'required|in:successful,unsuccessful',
            'feedback' => $this->moderation_status === 'unsuccessful' ? 'required|string|max:1000' : 'nullable|string|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'feedback.required' => 'Обратная связь обязательна при неуспешной модерации.',
            'feedback.string' => 'Обратная связь должна быть текстом.',
            'feedback.max' => 'Обратная связь не должна превышать 1000 символов.',
            'moderation_status.required' => 'Статус модерации обязателен.',
            'moderation_status.in' => 'Статус модерации должен быть: успешный или неуспешный.'
        ];
    }

}
