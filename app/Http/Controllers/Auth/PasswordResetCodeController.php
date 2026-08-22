<?php

namespace App\Http\Controllers\Auth;

use App\Actions\Fortify\ResetUserPassword;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\PasswordResetCodeNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\View\View;

class PasswordResetCodeController extends Controller
{
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    public function send(Request $request): RedirectResponse
    {
        $email = $request->validate(['email' => ['required', 'email']])['email'];
        $key = 'password-reset-code|'.$request->ip().'|'.$email;

        if (RateLimiter::tooManyAttempts($key, 3)) {
            return back()->withErrors(['email' => 'Terlalu banyak permintaan. Coba lagi nanti.'])->withInput();
        }

        RateLimiter::hit($key, 600);
        $user = User::where('email', $email)->first();

        if ($user) {
            $code = (string) random_int(100000, 999999);
            DB::table('password_reset_codes')->where('email', $email)->whereNull('used_at')->update(['used_at' => now()]);
            DB::table('password_reset_codes')->insert([
                'email' => $email,
                'code_hash' => Hash::make($code),
                'attempts' => 0,
                'expires_at' => now()->addMinutes(10),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $user->notify(new PasswordResetCodeNotification($code));
        }

        $request->session()->put('password_reset_email', $email);

        return redirect()->route('password.code.verify')->with('status', 'Jika email terdaftar, kode verifikasi telah dikirim.');
    }

    public function verifyForm(Request $request): View|RedirectResponse
    {
        return $request->session()->has('password_reset_email')
            ? view('auth.verify-reset-code')
            : redirect()->route('password.code.request');
    }

    public function verify(Request $request): RedirectResponse
    {
        $code = $request->validate(['code' => ['required', 'digits:6']])['code'];
        $email = $request->session()->get('password_reset_email');
        $resetCode = DB::table('password_reset_codes')->where('email', $email)->whereNull('used_at')->where('expires_at', '>', now())->latest('id')->first();

        if (! $resetCode || $resetCode->attempts >= 5) {
            return back()->withErrors(['code' => 'Kode tidak valid atau sudah kedaluwarsa.']);
        }

        if (! Hash::check($code, $resetCode->code_hash)) {
            DB::table('password_reset_codes')->where('id', $resetCode->id)->increment('attempts');

            return back()->withErrors(['code' => 'Kode tidak valid atau sudah kedaluwarsa.']);
        }

        $request->session()->put([
            'password_reset_verified' => true,
            'password_reset_code_id' => $resetCode->id,
        ]);

        return redirect()->route('password.code.reset');
    }

    public function resetForm(Request $request): View|RedirectResponse
    {
        return $request->session()->get('password_reset_verified')
            ? view('auth.reset-password')
            : redirect()->route('password.code.verify');
    }

    public function reset(Request $request, ResetUserPassword $resetUserPassword): RedirectResponse
    {
        $request->validate(['password' => ['required', 'string', 'confirmed']]);
        $email = $request->session()->get('password_reset_email');
        $codeId = $request->session()->get('password_reset_code_id');
        $resetCode = DB::table('password_reset_codes')->where('id', $codeId)->where('email', $email)->whereNull('used_at')->where('expires_at', '>', now())->first();

        if (! $request->session()->get('password_reset_verified') || ! $resetCode) {
            return redirect()->route('password.code.request')->withErrors(['code' => 'Sesi reset password sudah tidak berlaku.']);
        }

        $user = User::where('email', $email)->firstOrFail();
        $resetUserPassword->reset($user, $request->only('password', 'password_confirmation'));
        DB::table('password_reset_codes')->where('id', $resetCode->id)->update(['used_at' => now(), 'updated_at' => now()]);
        $request->session()->forget(['password_reset_email', 'password_reset_verified', 'password_reset_code_id']);

        return redirect()->route('login')->with('status', 'Password berhasil diubah. Silakan login.');
    }
}
