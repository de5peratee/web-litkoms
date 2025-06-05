<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use Carbon\Carbon;

class StoreEventRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'cover' => 'required|image|mimes:jpeg,png,jpg|max:4096',
            'name' => 'required|string|min:3|max:255',
            'description' => 'required|string|min:10|max:5000',
            'start_date' => 'required|date_format:Y-m-d|after_or_equal:today',
            'end_date' => 'required|date_format:Y-m-d|after_or_equal:start_date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i',
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
                    $validator->errors()->add('guests', 'Количество гостей не должно превышать 10.');
                }
            }

            $tags = array_filter(array_map('trim', explode(',', $this->input('tags'))));
            $tagsCount = count($tags);
            if (count(array_unique(array_map('strtolower', $tags))) < $tagsCount) {
                $validator->errors()->add('tags', 'Теги не должны повторяться.');
            }
            if ($tagsCount > 5) {
                $validator->errors()->add('tags', 'Количество тегов не должно превышать 5.');
            }

            try {
                $startDateTime = Carbon::createFromFormat('Y-m-d H:i', $this->input('start_date') . ' ' . $this->input('start_time'));
                $endDateTime = Carbon::createFromFormat('Y-m-d H:i', $this->input('end_date') . ' ' . $this->input('end_time'));

                if ($endDateTime->lessThan($startDateTime)) {
                    $validator->errors()->add('end_time', 'Время окончания не может быть раньше времени начала.');
                }
            } catch (\Exception $e) {
                $validator->errors()->add('end_time', 'Некорректный формат даты или времени.');
            }
        });
    }


    public function messages(): array
    {
        return [
            'cover.required' => 'Обложка обязательна для загрузки.',
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
            'end_date.required' => 'Дата конца обязательна.',
            'end_date.date_format' => 'Дата конца должна быть в формате ГГГГ-ММ-ДД.',
            'end_date.after_or_equal' => 'Дата конца не может быть раньше даты начала.',
            'start_time.required' => 'Время начала обязательно.',
            'start_time.date_format' => 'Время должно быть в формате ЧЧ:ММ.',
            'end_time.required' => 'Время окончания обязательно.',
            'end_time.date_format' => 'Время должно быть в формате ЧЧ:ММ.',
            'guests.max' => 'Список гостей не должен превышать 1000 символов.',
            'guests.regex' => 'Список гостей может содержать только буквы, пробелы и запятые.',
            'tags.required' => 'Теги обязательны.',
            'tags.max' => 'Список тегов не должен превышать 1000 символов.',
            'tags.regex' => 'Список тегов может содержать только буквы, пробелы и запятые.',
        ];
    }
}
