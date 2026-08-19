<x-layouts.guest title="Password Baru | SIPEMA">
    <div class="flex min-h-screen items-center justify-center px-6 py-10">
        <div class="auth-card w-full max-w-md p-8 lg:p-10">
            <div class="text-center">
                <h1 class="text-3xl font-black text-slate-800">Buat Password Baru</h1>
                <p class="mt-3 text-gray-500">Gunakan password yang kuat dan mudah Anda ingat.</p>
            </div>

            @if ($errors->any())
                <div class="mt-6 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">{{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ route('password.code.update') }}" class="mt-8 space-y-6">
                @csrf
                <div>
                    <label for="password" class="mb-2 block text-sm font-semibold text-slate-700">Password Baru</label>
                    <input id="password" type="password" name="password" required autofocus autocomplete="new-password" class="auth-input">
                </div>
                <div>
                    <label for="password_confirmation" class="mb-2 block text-sm font-semibold text-slate-700">Konfirmasi Password</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" class="auth-input">
                </div>
                <button type="submit" class="auth-button">Simpan Password</button>
            </form>
        </div>
    </div>
</x-layouts.guest>
