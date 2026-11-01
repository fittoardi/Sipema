<?php

use App\Models\User;
use App\Notifications\PasswordResetCodeNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;

uses(RefreshDatabase::class);

test('user can reset password with an email verification code', function () {
    Notification::fake();
    $user = User::create([
        'name' => 'Test Student',
        'email' => 'student@example.com',
        'password' => Hash::make('OldPassword123'),
        'role' => 'mahasiswa',
    ]);

    $this->post(route('password.code.send'), ['email' => $user->email]);
    $code = null;
    Notification::assertSentTo($user, PasswordResetCodeNotification::class, function (PasswordResetCodeNotification $notification) use (&$code): bool {
        $code = $notification->code;

        return true;
    });

    $this->post(route('password.code.verify.submit'), ['code' => $code]);
    $this->post(route('password.code.update'), [
        'password' => 'NewPassword123',
        'password_confirmation' => 'NewPassword123',
    ])->assertRedirect(route('login'));

    expect(Hash::check('NewPassword123', $user->refresh()->password))->toBeTrue();
});
