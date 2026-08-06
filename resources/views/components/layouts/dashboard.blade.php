@props(['title' => null])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ? $title.' | '.config('app.name') : config('app.name') }}</title>
    @vite([
        'resources/css/app.css',
        'resources/js/app.js',
    ])
</head>

<body class="min-h-screen bg-slate-100 text-slate-900">

    @include('partials.background')

    <div class="min-h-screen grid grid-cols-12 gap-6">

        {{-- Sidebar --}}
        @include('components.layouts.sidebar')

        {{-- Main content area --}}
        <div class="col-span-12 lg:col-span-10 xl:col-span-10">

            {{-- Navbar --}}
            @include('components.layouts.navbar')

            <main class="p-6">
                {{ $slot ?? view()->yieldContent('content') }}
            </main>

        </div>

    </div>

</body>

</html>
