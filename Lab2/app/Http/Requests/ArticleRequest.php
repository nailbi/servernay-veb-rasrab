<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Валидация данных статьи при создании и редактировании.
 */
class ArticleRequest extends FormRequest
{
    /**
     * Создавать и редактировать статьи может любой пользователь.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Правила валидации.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'date'          => ['required', 'date', 'before_or_equal:today'],
            'name'          => ['required', 'string', 'min:3', 'max:255'],
            'preview_image' => ['required', Rule::in(self::images())],
            'full_image'    => ['required', Rule::in(self::images())],
            'short_desc'    => ['required', 'string', 'min:10', 'max:255'],
            'desc'          => ['required', 'string', 'min:20'],
        ];
    }

    /**
     * Сообщения об ошибках на русском языке.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'required'        => 'Поле «:attribute» обязательно для заполнения.',
            'string'          => 'Поле «:attribute» должно быть строкой.',
            'date'            => 'Поле «:attribute» должно содержать корректную дату.',
            'before_or_equal' => 'Поле «:attribute» не может быть позже сегодняшнего дня.',
            'min'             => 'Поле «:attribute» должно содержать не менее :min символов.',
            'max'             => 'Поле «:attribute» должно содержать не более :max символов.',
            'in'              => 'Выберите изображение из списка.',
        ];
    }

    /**
     * Человекочитаемые названия полей.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'date'          => 'Дата',
            'name'          => 'Название',
            'preview_image' => 'Превью',
            'full_image'    => 'Полное изображение',
            'short_desc'    => 'Краткое описание',
            'desc'          => 'Текст статьи',
        ];
    }

    /**
     * Список изображений, доступных в public/images.
     *
     * @return array<int, string>
     */
    public static function images(): array
    {
        $files = scandir(public_path('images')) ?: [];

        return array_values(array_filter(
            $files,
            fn (string $file) => preg_match('/\.(jpe?g|png|webp)$/i', $file) === 1
        ));
    }
}
