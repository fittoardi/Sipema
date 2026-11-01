<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $title ?? config('app.name') }}</title>

    @vite([
        'resources/css/app.css',
        'resources/css/auth.css',
        'resources/js/app.js',
        'resources/js/auth.js',
        'resources/js/password-toggle.js',
    ])
</head>

<body class="min-h-screen overflow-x-hidden bg-sandy-desk text-deep-moss selection:bg-amber-glow/30 selection:text-deep-moss antialiased font-sans">

    {{-- Background Canvas --}}
    @include('partials.background')

    {{-- Content --}}
    <main class="relative z-10 flex min-h-screen flex-col justify-center">
        {{ $slot }}
    </main>

</body>

</html>
