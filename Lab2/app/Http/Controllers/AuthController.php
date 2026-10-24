<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

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
     * Страница регистрации: отдаёт шаблон auth/signin.
     */
    public function create()
    {
        return view('auth.signin');
    }

    /**
     * Обработка формы регистрации: валидирует данные,
     * сохраняет нового пользователя в БД и перенаправляет на форму входа.
     */
    public function registration(Request $request)
    {
        // При ошибке валидации Laravel сам вернёт пользователя
        // обратно на форму с сообщениями об ошибках и старыми значениями.
        $validated = $request->validate([
            'name'     => ['required', 'string', 'min:2', 'max:50'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6', 'max:64'],
        ], self::MESSAGES, self::ATTRIBUTES);

        // Новый пользователь получает роль «читатель».
        $validated['role_id'] = Role::where('slug', Role::READER)->value('id');

        // Пароль хэшируется автоматически (cast 'hashed' в модели User).
        $user = User::create($validated);

        return redirect()
            ->route('login')
            ->withInput(['email' => $user->email])
            ->with('success', 'Регистрация прошла успешно. Войдите, используя свой e-mail и пароль.');
    }

    /**
     * Страница входа: отдаёт шаблон auth/login.
     */
    public function login()
    {
        return view('auth.login');
    }

    /**
     * Аутентификация: проверяет e-mail и пароль, открывает сессию
     * и выдаёт пользователю токен Sanctum.
     */
    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ], self::MESSAGES, self::ATTRIBUTES);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'email' => 'Неверный e-mail или пароль.',
            ]);
        }

        // Новый идентификатор сессии — защита от фиксации сессии.
        $request->session()->regenerate();

        // Присваиваем пользователю токен аутентификации Sanctum
        // (хранится в таблице personal_access_tokens).
        $token = $request->user()->createToken('web')->plainTextToken;
        $request->session()->put('auth_token', $token);

        // Возврат на страницу, куда пользователь шёл до входа (минуя auth), иначе — на главную.
        return redirect()
            ->intended(route('home'))
            ->with('success', 'Добро пожаловать, ' . $request->user()->name . '!');
    }

    /**
     * Выход: удаляет токены пользователя, аннулирует сессию
     * и обновляет CSRF-токен.
     */
    public function logout(Request $request)
    {
        // Удаляем все токены аутентификации пользователя.
        $request->user()->tokens()->delete();

        Auth::guard('web')->logout();

        // Аннулируем сессию и генерируем новый CSRF-токен.
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('home')
            ->with('success', 'Вы вышли из аккаунта.');
    }
}
