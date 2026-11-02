<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $user = $request->user();

        if ($user?->isDosen()) {
            return redirect()->intended(route('dosen.dashboard', absolute: false));
        }

        if ($user?->isMahasiswa()) {
            return redirect()->intended(route('mahasiswa.dashboard', absolute: false));
        }

        return redirect()->intended(route('admin.dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        try {
            // Log the user out from the web guard
            Auth::guard('web')->logout();

            // Defensive session cleanup: flush data, invalidate and regenerate CSRF token
            $session = $request->session();

            if ($session) {
                // Remove all session data
                $session->flush();

                // Invalidate the session to prevent session fixation
                $session->invalidate();

                // Regenerate CSRF token
                $session->regenerateToken();
            }
        } catch (\Throwable $e) {
            // Don't let session errors break logout flow; report and continue with redirect
            report($e);

            try {
                $request->session()?->regenerateToken();
            } catch (\Throwable) {
                // ignore secondary errors
            }
        }

        return redirect('/');
    }
}
