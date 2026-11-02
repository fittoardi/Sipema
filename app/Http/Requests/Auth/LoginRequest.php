<?php

namespace App\Http\Requests\Auth;

use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\User;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'login' => ['required', 'string'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        $credentials = $this->credentials();

        if (! Auth::attempt($credentials, $this->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'login' => trans('auth.failed'),
            ]);
        }

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'login' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => (int) ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        $login = Str::lower((string) $this->input('login', ''));
        $ip = (string) $this->ip();

        return Str::transliterate($login.'|'.$ip);
    }

    /**
     * @return array{email: string, password: mixed}
     */
    protected function credentials(): array
    {
        $login = trim((string) $this->input('login', ''));

        /** @var User|null $user */
        $user = null;

        if (filter_var($login, FILTER_VALIDATE_EMAIL)) {
            $user = User::query()->where('email', $login)->first();
        }

        if ($user === null && strtolower($login) === 'admin') {
            $user = User::query()
                ->where('email', 'admin@sipenma.test')
                ->orWhere('role', 'admin')
                ->first();
        }

        if ($user === null) {
            /** @var Mahasiswa|null $mahasiswa */
            $mahasiswa = Mahasiswa::query()->where('nim', $login)->first();

            if ($mahasiswa !== null && $mahasiswa->user !== null) {
                $user = $mahasiswa->user;
            }
        }

        if ($user === null) {
            /** @var Dosen|null $dosen */
            $dosen = Dosen::query()
                ->where('nidn', $login)
                ->orWhere('nip', $login)
                ->first();

            if ($dosen !== null && $dosen->user !== null) {
                $user = $dosen->user;
            }
        }

        return [
            'email' => $user !== null ? $user->email : $login,
            'password' => $this->input('password'),
        ];
    }
}
