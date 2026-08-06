<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ isset($title) ? $title . ' | ' . config('app.name') : config('app.name') }}</title>

    @vite([
        'resources/css/app.css',
        'resources/js/app.js',
    ])
</head>

<body class="min-h-screen bg-slate-100 text-slate-900">

    @include('partials.background')

    <header class="bg-white shadow-sm">
        <div class="mx-auto max-w-7xl px-4 py-4 sm:px-6 lg:px-8 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('login') }}" class="font-bold">{{ config('app.name') }}</a>
            </div>

            <div class="flex items-center gap-4">
                @auth
                    <span class="text-sm text-gray-600">{{ auth()->user()->name }}</span>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-sm text-red-600">Logout</button>
                    </form>
                @endauth
            </div>
        </div>
    </header>

    <main class="mx-auto max-w-7xl p-6 sm:px-6 lg:px-8">
        @if (isset($slot))
            {{ $slot }}
        @else
            @yield('content')
        @endif
    </main>

</body>

</html>
