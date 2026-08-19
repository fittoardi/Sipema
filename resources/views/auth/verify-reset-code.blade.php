<x-layouts.guest title="Verifikasi Kode | SIPEMA">
    <div class="flex min-h-screen items-center justify-center px-6 py-10">
        <div class="auth-card w-full max-w-md p-8 lg:p-10">
            <div class="text-center">
                <h1 class="text-3xl font-black text-slate-800">Verifikasi Email</h1>
                <p class="mt-3 text-gray-500">Masukkan kode 6 digit yang dikirim ke email Anda.</p>
            </div>

            @if (session('status'))
                <div class="mt-6 rounded-xl border border-green-200 bg-green-50 p-4 text-sm text-green-700">{{ session('status') }}</div>
            @endif
            @if ($errors->any())
                <div class="mt-6 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">{{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ route('password.code.verify.submit') }}" class="mt-8 space-y-6">
                @csrf
                <div>
                    <label for="code" class="mb-2 block text-sm font-semibold text-slate-700">Kode Verifikasi</label>
                    <input id="code" type="text" name="code" inputmode="numeric" pattern="[0-9]{6}" maxlength="6" required autofocus autocomplete="one-time-code" class="auth-input text-center text-2xl tracking-[0.4em]" placeholder="000000">
                </div>
                <button type="submit" class="auth-button">Verifikasi Kode</button>
            </form>

            <p class="mt-6 text-center text-sm text-gray-500">Kode berlaku selama 10 menit.</p>
        </div>
    </div>
</x-layouts.guest>
