<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class StoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'birth_date' => 'required|date',
            'password' => 'required|string|min:8|confirmed',
            'email' => 'required|string|email|max:255|unique:users,email',
            'nickname' => 'required|string|min:5|max:20|unique:users,nickname|regex:/^[a-zA-Z0-9_-]+$/',
            'name' => 'required|string|max:100|regex:/^[a-zA-Zа-яА-ЯёЁ]+$/u',
            'last_name' => 'required|string|max:100|regex:/^[a-zA-Zа-яА-ЯёЁ]+$/u',
            'agree' => ['accepted'],
        ];
    }

    public function messages(): array
    {
        return [
            'birth_date.required' => 'Поле не должно быть пустым',
            'birth_date.date' => 'Поле должно быть корректной датой.',

            'password.required' => 'Поле не должно быть пустым',
            'password.string' => 'Поле должно быть строкой.',
            'password.min' => 'Пароль должен содержать минимум :min символов.',
            'password.confirmed' => 'Пароль и подтверждение пароля не совпадают.',

            'email.required' => 'Поле не должно быть пустым',
            'email.string' => 'Поле должно быть строкой.',
            'email.email' => 'Поле должно быть корректным email-адресом.',
            'email.max' => 'Поле не должно превышать :max символов.',
            'email.unique' => 'Данный email уже зарегистрирован.',

            'nickname.required' => 'Поле не должно быть пустым',
            'nickname.string' => 'Поле должно быть строкой.',
            'nickname.min' => 'Поле должно содержать минимум :min символов.',
            'nickname.max' => 'Поле не должно превышать :max символов.',
            'nickname.unique' => 'Данный никнейм уже занят.',
            'nickname.regex' => 'Поле должно содержать только латинские буквы, цифры, подчеркивания и дефисы.',

            'name.required' => 'Поле не должно быть пустым',
            'name.string' => 'Поле должно быть строкой.',
            'name.max' => 'Поле не должно превышать :max символов.',
            'name.regex' => 'Поле должно содержать только буквы.',

            'last_name.required' => 'Поле не должно быть пустым',
            'last_name.string' => 'Поле должно быть строкой.',
            'last_name.max' => 'Поле не должно превышать :max символов.',
            'last_name.regex' => 'Поле должно содержать только буквы.',
        ];
    }

//    protected function withValidator(Validator $validator)
//    {
//        dd([
//            'input_data' => $this->all(),
//            'validation_result' => $validator->errors()->toArray(),
//        ]);
//    }
}
