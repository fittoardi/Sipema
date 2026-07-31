<section class="flex min-h-screen items-center justify-center px-6 py-10 lg:px-16">

    <div
        class="auth-card fade-up w-full max-w-md lg:max-w-lg p-8 lg:p-10">

        {{-- Mobile Logo --}}
        <div class="mb-8 text-center lg:hidden">

            <div
                class="mx-auto flex h-20 w-20 items-center justify-center rounded-2xl bg-blue-600 shadow-lg">

                <img
                    src="{{ asset('images/logo.png') }}"
                    alt="Logo SIPEMA"
                    class="h-12 w-12 object-contain">

            </div>

            <h1 class="mt-4 text-3xl font-bold text-slate-800">

                SIPEMA

            </h1>

            <p class="mt-2 text-gray-500">

                Sistem Pengelolaan Nilai Mahasiswa

            </p>

        </div>

        {{-- Heading --}}
        <div class="text-center">

            <h2 class="text-4xl font-black text-slate-800">

                Selamat Datang

            </h2>

            <p class="mt-3 text-gray-500">

                Silakan login menggunakan akun Anda

            </p>

        </div>

        @if(session('status'))

            <div
                class="mt-6 rounded-xl border border-green-200 bg-green-50 p-4 text-sm text-green-700">

                {{ session('status') }}

            </div>

        @endif

        {{-- Form --}}
        <form
            method="POST"
            action="{{ route('login') }}"
            class="mt-8 space-y-6">

            @csrf

            {{-- Email --}}
            <div>

                <label
                    for="email"
                    class="mb-2 block text-sm font-semibold text-slate-700">

                    NIM

                </label>

                <input
                    id="NIM"
                    type="string"
                    name="NIM"
                    value="{{ old('NIM') }}"
                    required
                    autofocus
                    placeholder="Masukkan NIM"

                    class="auth-input">

                @error('email')

                    <p class="mt-2 text-sm text-red-500">

                        {{ $message }}

                    </p>

                @enderror

            </div>

            {{-- Password --}}
            <div>

                <label
                    for="password"
                    class="mb-2 block text-sm font-semibold text-slate-700">

                    Password

                </label>

                <div class="relative">
                    <input
                        type="password"
                        id="password"
                        placeholder="Masukkan Password"
                        class="w-full rounded-lg border border-gray-300 px-4 py-3 pr-12 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >

                    <button
                        type="button"
                        id="togglePassword"
                        class="absolute inset-y-0 right-3 flex items-center text-gray-500 hover:text-gray-700"
                    >
                        <!-- Eye -->
                        <svg id="eyeOpen" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0zm6 0s-3-6-9-6-9 6-9 6 3 6 9 6 9-6 9-6z" />
                        </svg>

                        <!-- Eye Off -->
                        <svg id="eyeClose" xmlns="http://www.w3.org/2000/svg"
                            class="hidden h-5 w-5"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                stroke-width="2"
                                d="M13.875 18.825A10.05 10.05 0 0112 19c-5 0-9-7-9-7a17.634 17.634 0 013.52-4.592M9.88 9.88A3 3 0 0114.12 14.12M3 3l18 18" />
                        </svg>
                    </button>
                </div>

                @error('password')

                    <p class="mt-2 text-sm text-red-500">

                        {{ $message }}

                    </p>

                @enderror

            </div>

            {{-- Remember --}}
            <div class="flex items-center justify-between">

                <label class="flex items-center gap-2">

                    <input
                        type="checkbox"
                        name="remember"
                        class="rounded border-gray-300 text-blue-600">

                    <span class="text-sm text-gray-600">

                        Ingat Saya

                    </span>

                </label>

                @if(Route::has('password.request'))

                    <a
                        href="{{ route('password.request') }}"
                        class="text-sm font-medium text-blue-600 hover:text-blue-700">

                        Lupa Password?

                    </a>

                @endif

            </div>

            {{-- Button --}}
            <button
                id="loginButton"
                type="submit"
                class="auth-button">

                Login

            </button>

        </form>

        <div class="mt-8 border-t pt-6">

            <p class="text-center text-sm text-gray-500">

                © {{ date('Y') }} SIPEMA

            </p>

        </div>

    </div>

</section>
