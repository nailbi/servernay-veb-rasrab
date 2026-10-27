<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

/**
 * Аутентификация для REST API (ЛР15).
 * Скопирован из App\Http\Controllers\AuthController: namespace изменён на API,
 * вместо redirect/view — response с теми же данными.
 *
 * Вместо сессии клиент получает токен Sanctum и передаёт его в заголовке
 * Authorization: Bearer <token>.
 */
class AuthController extends Controller
{
    /**
     * Сообщения об ошибках валидации на русском языке.
     */
    private const MESSAGES = [
        'required' => 'Поле «:attribute» обязательно для заполнения.',
        'string'   => 'Поле «:attribute» должно быть строкой.',
        'email'    => 'Поле «:attribute» должно содержать корректный адрес.',
        'min'      => 'Поле «:attribute» должно содержать не менее :min символов.',
        'max'      => 'Поле «:attribute» должно содержать не более :max символов.',
        'unique'   => 'Пользователь с таким значением поля «:attribute» уже зарегистрирован.',
    ];

    /**
     * Названия полей для подстановки в :attribute.
     */
    private const ATTRIBUTES = [
        'name'     => 'Имя',
        'email'    => 'E-mail',
        'password' => 'Пароль',
    ];

    /**
     * Регистрация: валидирует данные и сохраняет пользователя с ролью «читатель».
     * POST /api/register
     */
    public function registration(Request $request)
    {
        $validated = $request->validate([
            'name'     => ['required', 'string', 'min:2', 'max:50'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6', 'max:64'],
        ], self::MESSAGES, self::ATTRIBUTES);

        $validated['role_id'] = Role::where('slug', Role::READER)->value('id');

        // Пароль хэшируется автоматически (cast 'hashed' в модели User).
        $user = User::create($validated);

        return response([
            'message' => 'Регистрация прошла успешно. Войдите, используя свой e-mail и пароль.',
            'user'    => $user,
        ], 201);
    }

    /**
     * Вход: проверяет e-mail и пароль и выдаёт токен Sanctum.
     * POST /api/login
     */
    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ], self::MESSAGES, self::ATTRIBUTES);

        $user = User::where('email', $credentials['email'])->first();

        // В API нет сессии, поэтому пароль проверяем напрямую, без Auth::attempt.
        if (! $user || ! Hash::check($credentials['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => 'Неверный e-mail или пароль.',
            ]);
        }

        // Токен хранится в таблице personal_access_tokens; клиенту отдаём его один раз.
        $token = $user->createToken('api')->plainTextToken;

        return response([
            'message' => 'Добро пожаловать, ' . $user->name . '!',
            'user'    => $user->load('role'),
            'token'   => $token,
        ]);
    }

    /**
     * Текущий пользователь по токену.
     * GET /api/user
     */
    public function user(Request $request)
    {
        return response([
            'user' => $request->user()->load('role'),
        ]);
    }

    /**
     * Выход: удаляет токены пользователя.
     * POST /api/logout
     */
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response([
            'message' => 'Вы вышли из аккаунта.',
        ]);
    }
}
