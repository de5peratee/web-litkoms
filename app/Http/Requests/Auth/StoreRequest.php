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
        ];
    }

    public function messages(): array
    {
        return [
            'birth_date.required' => 'Поле "Дата рождения" обязательно для заполнения.',
            'birth_date.date' => 'Поле "Дата рождения" должно быть корректной датой.',

            'password.required' => 'Поле "Пароль" обязательно для заполнения.',
            'password.string' => 'Поле "Пароль" должно быть строкой.',
            'password.min' => 'Пароль должен содержать минимум :min символов.',
            'password.confirmed' => 'Пароль и подтверждение пароля не совпадают.',

            'email.required' => 'Поле "Email" обязательно для заполнения.',
            'email.string' => 'Поле "Email" должно быть строкой.',
            'email.email' => 'Поле "Email" должно быть корректным email-адресом.',
            'email.max' => 'Поле "Email" не должно превышать :max символов.',
            'email.unique' => 'Данный email уже зарегистрирован.',

            'nickname.required' => 'Поле "Никнейм" обязательно для заполнения.',
            'nickname.string' => 'Поле "Никнейм" должно быть строкой.',
            'nickname.min' => 'Поле "Никнейм" должно содержать минимум :min символов.',
            'nickname.max' => 'Поле "Никнейм" не должно превышать :max символов.',
            'nickname.unique' => 'Данный никнейм уже занят.',
            'nickname.regex' => 'Поле "Никнейм" должно содержать только латинские буквы, цифры, подчеркивания и дефисы.',

            'name.required' => 'Поле "Имя" обязательно для заполнения.',
            'name.string' => 'Поле "Имя" должно быть строкой.',
            'name.max' => 'Поле "Имя" не должно превышать :max символов.',
            'name.regex' => 'Поле "Имя" должно содержать только буквы.',

            'last_name.required' => 'Поле "Фамилия" обязательно для заполнения.',
            'last_name.string' => 'Поле "Фамилия" должно быть строкой.',
            'last_name.max' => 'Поле "Фамилия" не должно превышать :max символов.',
            'last_name.regex' => 'Поле "Фамилия" должно содержать только буквы.',
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
