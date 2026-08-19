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
        'role' => 'mahasiswa',
        'email' => 'student@example.com',
        'password' => Hash::make('OldPassword123!'),
    ]);

    $sendResponse = $this->post(route('password.code.send'), [
        'email' => $user->email,
    ]);
    expect($sendResponse->status())->toBe(302)
        ->and($sendResponse->headers->get('Location'))->toBe(route('password.code.verify'));

    $code = null;
    Notification::assertSentTo($user, PasswordResetCodeNotification::class, function (PasswordResetCodeNotification $notification) use (&$code): bool {
        $code = $notification->code;

        return true;
    });

    expect($code)->toMatch('/^[0-9]{6}$/');

    $verifyResponse = $this->post(route('password.code.verify.submit'), [
        'code' => $code,
    ]);
    expect($verifyResponse->status())->toBe(302)
        ->and($verifyResponse->headers->get('Location'))->toBe(route('password.code.reset'));

    $resetResponse = $this->post(route('password.code.update'), [
        'password' => 'NewPassword123',
        'password_confirmation' => 'NewPassword123',
    ]);
    expect($resetResponse->status())->toBe(302)
        ->and($resetResponse->headers->get('Location'))->toBe(route('login'));

    expect(Hash::check('NewPassword123', $user->refresh()->password))->toBeTrue();
});

test('reset code is limited to five invalid attempts', function () {
    Notification::fake();
    $user = User::create([
        'name' => 'Test Student',
        'role' => 'mahasiswa',
        'email' => 'student@example.com',
        'password' => Hash::make('OldPassword123!'),
    ]);

    $this->post(route('password.code.send'), ['email' => $user->email]);

    foreach (range(1, 5) as $attempt) {
        $this->post(route('password.code.verify.submit'), ['code' => '000000'])
            ->assertSessionHasErrors('code');
    }

    $this->post(route('password.code.verify.submit'), ['code' => '123456'])
        ->assertSessionHasErrors('code');
});
