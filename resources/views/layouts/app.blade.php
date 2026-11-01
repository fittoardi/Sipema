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

<body class="min-h-screen bg-sandy-desk text-deep-moss selection:bg-amber-glow/30 selection:text-deep-moss font-sans">

    @include('partials.background')

    <header class="bg-paper-white border-b border-warm-mist">
        <div class="mx-auto max-w-7xl px-4 py-3 sm:px-6 lg:px-8 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('login') }}" class="font-black text-deep-moss tracking-tight flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-amber-glow"></span>
                    {{ config('app.name') }}
                </a>
            </div>

            <div class="flex items-center gap-4">
                @auth
                    <span class="text-xs text-olive-char font-mono">{{ auth()->user()->name }}</span>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn-mist text-xs py-1 px-2.5 text-flame-orange">Logout</button>
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
