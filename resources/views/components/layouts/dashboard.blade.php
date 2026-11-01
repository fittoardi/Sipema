@props(['title' => null])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ? $title.' | '.config('app.name') : config('app.name', 'SIPENMA') }}</title>
    @vite([
        'resources/css/app.css',
        'resources/js/app.js',
    ])
</head>

<body class="min-h-screen bg-sandy-desk text-deep-moss font-sans antialiased selection:bg-amber-glow selection:text-deep-moss">

    <div class="flex h-screen overflow-hidden p-2 sm:p-3 gap-2 sm:gap-3">

        {{-- Sidebar: Tactile File Manager --}}
        @include('components.layouts.sidebar')

        {{-- Main content application window --}}
        <main class="flex-1 flex flex-col overflow-hidden bg-paper-white border border-warm-mist rounded-window">
            {{-- Window Title Bar / Navbar --}}
            @include('components.layouts.navbar')

            <div class="flex-1 overflow-y-auto p-4 sm:p-6 bg-cream-paper">
                @if (session('success'))
                    <div class="mb-4 p-3 rounded-[4px] bg-emerald-50 border border-emerald-300 text-emerald-900 text-xs flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-moss-green flex-shrink-0"></span>
                        <span class="font-medium">{{ session('success') }}</span>
                    </div>
                @endif

                @if (session('error'))
                    <div class="mb-4 p-3 rounded-[4px] bg-rose-50 border border-rose-300 text-rose-900 text-xs flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-flame-orange flex-shrink-0"></span>
                        <span class="font-medium">{{ session('error') }}</span>
                    </div>
                @endif

                {{ $slot ?? view()->yieldContent('content') }}
            </div>
        </main>

    </div>

</body>

</html>
