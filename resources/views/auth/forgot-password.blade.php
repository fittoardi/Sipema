<x-layouts.guest title="Lupa Password | SIPEMA">
    <div class="flex min-h-screen items-center justify-center px-6 py-10">
        <div class="auth-card w-full max-w-md p-8 lg:p-10">
            <div class="text-center">
                <h1 class="text-3xl font-black text-slate-800">Lupa Password?</h1>
                <p class="mt-3 text-gray-500">Masukkan email akun Anda untuk menerima kode verifikasi.</p>
            </div>

            @if ($errors->any())
                <div class="mt-6 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.code.send') }}" class="mt-8 space-y-6">
                @csrf
                <div>
                    <label for="email" class="mb-2 block text-sm font-semibold text-slate-700">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="email" class="auth-input" placeholder="nama@kampus.ac.id">
                </div>
                <button type="submit" class="auth-button">Kirim Kode Verifikasi</button>
            </form>

            <a href="{{ route('login') }}" class="mt-6 block text-center text-sm font-medium text-blue-600 hover:text-blue-700">Kembali ke login</a>
        </div>
    </div>
</x-layouts.guest>
