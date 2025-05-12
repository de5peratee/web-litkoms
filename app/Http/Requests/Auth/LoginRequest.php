<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
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
            'email' => 'required|string|email|max:255',
            'password' => 'required|string',
        ];
    }

    public function messages(): array
    {
        return [
            'password.required' => 'Поле не должно быть пустым',
            'password.string' => 'Поле должно быть строкой',
//            'password.min' => 'Пароль должен содержать минимум :min символов.',

            'email.required' => 'Поле не должно быть пустым',
            'email.string' => 'Поле должно быть строкой.',
            'email.email' => 'Не корректный адрес почты',
            'email.max' => 'Поле превышает лимит в :max символов.',
        ];
    }

}
