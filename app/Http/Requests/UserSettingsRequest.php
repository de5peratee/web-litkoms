<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserSettingsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $userId = $this->user()->id;

//        dd($this->toArray());

        return [
            'nickname' => ['required', 'string', 'min:5', 'max:20', Rule::unique('users', 'nickname')->ignore($userId), 'regex:/^[a-zA-Z0-9_-]+$/',],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($userId),],
            'name' => ['required', 'string', 'max:100', 'regex:/^[a-zA-Zа-яА-ЯёЁ]+$/u',],
            'last_name' => ['required', 'string', 'max:100', 'regex:/^[a-zA-Zа-яА-ЯёЁ]+$/u',],
            'birth_date' => ['required', 'date',],
            'about' => ['nullable', 'string', 'max:1000',],
            'icon' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:5120',],
            'head_profile' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:5120',],
            'password' => ['nullable', 'string', 'min:8', 'confirmed',],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     */
    public function messages(): array
    {
        return [
            'nickname.required' => 'Поле никнейма не должно быть пустым.',
            'nickname.string' => 'Никнейм должен быть строкой.',
            'nickname.min' => 'Никнейм должен содержать минимум :min символов.',
            'nickname.max' => 'Никнейм не должен превышать :max символов.',
            'nickname.unique' => 'Данный никнейм уже занят.',
            'nickname.regex' => 'Никнейм должен содержать только латинские буквы, цифры, подчеркивания и дефисы.',

            'email.required' => 'Поле email не должно быть пустым.',
            'email.string' => 'Email должен быть строкой.',
            'email.email' => 'Поле должно быть корректным email-адресом.',
            'email.max' => 'Email не должен превышать :max символов.',
            'email.unique' => 'Данный email уже зарегистрирован.',

            'name.required' => 'Поле имени не должно быть пустым.',
            'name.string' => 'Имя должно быть строкой.',
            'name.max' => 'Имя не должно превышать :max символов.',
            'name.regex' => 'Имя должно содержать только буквы.',

            'last_name.required' => 'Поле фамилии не должно быть пустым.',
            'last_name.string' => 'Фамилия должна быть строкой.',
            'last_name.max' => 'Фамилия не должен превышать :max символов.',
            'last_name.regex' => 'Фамилия должна содержать только буквы.',

            'birth_date.required' => 'Поле даты рождения не должно быть пустым.',
            'birth_date.date' => 'Поле должно быть корректной датой.',

            'about.string' => 'Описание должно быть строкой.',
            'about.max' => 'Описание не должно превышать :max символов.',

            'icon.image' => 'Иконка должна быть изображением.',
            'icon.mimes' => 'Иконка должна быть в формате: jpeg, png, jpg, gif.',
            'icon.max' => 'Размер иконки не должен превышать 5 МБ.',

            'head_profile.image' => 'Обложка профиля должна быть изображением.',
            'head_profile.mimes' => 'Обложка должна быть в формате: jpeg, png, jpg, gif.',
            'head_profile.max' => 'Размер обложки не должен превышать 5 МБ.',

            'password.string' => 'Пароль должен быть строкой.',
            'password.min' => 'Пароль должен содержать минимум :min символов.',
            'password.confirmed' => 'Пароль и подтверждение пароля не совпадают.',
        ];
    }
}
