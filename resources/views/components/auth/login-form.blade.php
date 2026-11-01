<section class="flex min-h-screen items-center justify-center p-6 lg:p-12">
    <div class="window-card auth-card fade-up w-full max-w-md overflow-hidden bg-paper-white">
        {{-- Window Title Bar --}}
        <div class="window-titlebar flex items-center justify-between px-4 py-2 border-b border-warm-mist bg-soft-linen">
            <div class="flex items-center gap-2">
                <span class="font-mono text-xs font-bold text-deep-moss">auth_session.exe</span>
                <span class="tag-pill tag-pill-amber text-[10px]">Portal Login</span>
            </div>
            <div class="flex items-center gap-1.5">
                <div class="window-control window-control-red"></div>
                <div class="window-control window-control-yellow"></div>
                <div class="window-control window-control-green"></div>
            </div>
        </div>

        <div class="p-8">
            {{-- Mobile Logo --}}
            <div class="mb-6 text-center lg:hidden">
                <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-[4px] bg-paper-white border border-warm-mist shadow-none">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo SIPEMA" class="h-9 w-9 object-contain">
                </div>
                <h1 class="mt-3 text-2xl font-black text-deep-moss tracking-tight font-sans">SIPEMA</h1>
                <p class="text-xs text-olive-char font-mono">Sistem Pengelolaan Nilai Mahasiswa</p>
            </div>

            {{-- Heading --}}
            <div class="mb-6">
                <h2 class="text-2xl font-black text-deep-moss font-sans tracking-tight">Selamat Datang</h2>
                <p class="mt-1 text-xs text-olive-char">Masukkan kredensial akun SIPEMA Anda untuk masuk ke sistem.</p>
            </div>

            @if(session('status'))
                <div class="mb-4 rounded-[4px] border border-moss-green bg-moss-green/10 p-3 text-xs text-deep-moss flex items-center gap-2">
                    <svg class="w-4 h-4 text-moss-green shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                    <span>{{ session('status') }}</span>
                </div>
            @endif

            {{-- Form --}}
            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf

                {{-- Nomor Induk --}}
                <div>
                    <label for="login" class="mb-1 block text-xs font-bold text-deep-moss uppercase tracking-wider font-mono">
                        NIM / NIDN / NIP
                    </label>
                    <input
                        id="login"
                        type="text"
                        name="login"
                        value="{{ old('login') }}"
                        required
                        autofocus
                        autocomplete="username"
                        placeholder="Contoh: 19800101... atau 210101..."
                        class="auth-input font-mono text-xs">

                    @error('login')
                        <p class="mt-1 text-xs text-flame-orange font-mono">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Password --}}
                <div>
                    <label for="password" class="mb-1 block text-xs font-bold text-deep-moss uppercase tracking-wider font-mono">
                        Password
                    </label>

                    <div class="relative">
                        <input
                            type="password"
                            id="password"
                            name="password"
                            required
                            autocomplete="current-password"
                            placeholder="Masukkan password akun Anda"
                            class="auth-input pr-10 font-mono text-xs">

                        <button
                            type="button"
                            id="togglePassword"
                            class="absolute inset-y-0 right-3 flex items-center text-olive-char hover:text-deep-moss transition-colors">
                            {{-- Eye --}}
                            <svg id="eyeOpen" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zm6 0s-3-6-9-6-9 6-9 6 3 6 9 6 9-6 9-6z"/>
                            </svg>
                            {{-- Eye Off --}}
                            <svg id="eyeClose" xmlns="http://www.w3.org/2000/svg" class="hidden h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-5 0-9-7-9-7a17.634 17.634 0 013.52-4.592M9.88 9.88A3 3 0 0114.12 14.12M3 3l18 18"/>
                            </svg>
                        </button>
                    </div>

                    @error('password')
                        <p class="mt-1 text-xs text-flame-orange font-mono">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Remember & Forgot --}}
                <div class="flex items-center justify-between pt-1">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="remember" class="rounded-[2px] border-warm-mist text-amber-glow focus:ring-0 focus:outline-none">
                        <span class="text-xs text-olive-char font-mono">Ingat Saya</span>
                    </label>

                    @if(Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-xs font-mono text-signal-blue hover:underline">
                            Lupa Password?
                        </a>
                    @endif
                </div>

                {{-- Button --}}
                <div class="pt-2">
                    <button id="loginButton" type="submit" class="auth-button">
                        Login ke SIPEMA
                    </button>
                </div>
            </form>

            {{-- Demo Hint Box --}}
            <div class="mt-6 rounded-[4px] bg-soft-linen border border-warm-mist p-3 text-[11px] font-mono text-olive-char">
                <div class="font-bold text-deep-moss mb-1">Akun Demo (Lihat DEMO_ACCOUNTS.md):</div>
                <div class="space-y-0.5">
                    <div>Admin: <span class="text-deep-moss font-semibold">admin</span> (pass: password)</div>
                    <div>Dosen: <span class="text-deep-moss font-semibold">198001012005011001</span> (pass: password)</div>
                    <div>Mhs: <span class="text-deep-moss font-semibold">210101001</span> (pass: password)</div>
                </div>
            </div>

            <div class="mt-6 border-t border-warm-mist pt-4 text-center">
                <p class="text-[11px] text-ash-green font-mono">
                    &copy; {{ date('Y') }} SIPEMA OS Desktop
                </p>
            </div>
        </div>
    </div>
</section>
