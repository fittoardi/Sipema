<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ isset($title) ? $title . ' | SIPENMA' : 'SIPENMA — Admin Desk' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-sandy-desk text-deep-moss font-sans antialiased selection:bg-amber-glow selection:text-deep-moss">
    <div class="flex h-screen overflow-hidden p-2 sm:p-3 gap-2 sm:gap-3">
        <!-- Sidebar: Tactile File Manager -->
        <aside class="w-60 bg-cream-paper border border-warm-mist rounded-window flex-shrink-0 flex flex-col justify-between overflow-hidden">
            <div>
                <!-- Window Title / Brand Header -->
                <div class="px-4 py-3 border-b border-warm-mist bg-soft-linen flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-rose-400 border border-rose-500/50"></span>
                        <span class="w-2.5 h-2.5 rounded-full bg-amber-400 border border-amber-500/50"></span>
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 border border-emerald-500/50"></span>
                    </div>
                    <span class="font-mono text-[11px] text-sage-gray">sipenma.sys</span>
                </div>

                <div class="px-4 py-3.5 border-b border-warm-mist bg-paper-white flex items-center gap-2.5">
                    <div class="w-7 h-7 rounded-[4px] bg-amber-glow flex items-center justify-center text-deep-moss font-bold text-xs border border-burnished-gold">
                        S
                    </div>
                    <div>
                        <div class="font-bold text-xs tracking-tight text-deep-moss">SIPENMA DESK</div>
                        <div class="text-[10px] font-mono text-olive-char">Academic OS &bull; Admin</div>
                    </div>
                </div>

                <!-- Navigation -->
                <nav class="p-2 space-y-0.5 overflow-y-auto text-xs">
                    @php
                        $navItems = [
                            [
                                'route' => 'admin.dashboard',
                                'label' => 'Dashboard',
                                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>',
                            ],
                            [
                                'route' => 'admin.prodi.index',
                                'pattern' => 'admin.prodi.*',
                                'label' => 'Program Studi',
                                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>',
                            ],
                            [
                                'route' => 'admin.dosen.index',
                                'pattern' => 'admin.dosen.*',
                                'label' => 'Dosen Pengajar',
                                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>',
                            ],
                            [
                                'route' => 'admin.mahasiswa.index',
                                'pattern' => 'admin.mahasiswa.*',
                                'label' => 'Data Mahasiswa',
                                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>',
                            ],
                            [
                                'route' => 'admin.matakuliah.index',
                                'pattern' => 'admin.matakuliah.*',
                                'label' => 'Mata Kuliah',
                                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>',
                            ],
                            [
                                'route' => 'admin.kelas.index',
                                'pattern' => 'admin.kelas.*',
                                'label' => 'Rombel Kelas',
                                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"/>',
                            ],
                            [
                                'route' => 'admin.nilai.index',
                                'pattern' => 'admin.nilai.*',
                                'label' => 'Penilaian Akademik',
                                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>',
                            ],
                            [
                                'route' => 'admin.pengaturan-nilai.index',
                                'pattern' => 'admin.pengaturan-nilai.*',
                                'label' => 'Pengaturan Nilai',
                                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>',
                            ],
                            [
                                'route' => 'admin.laporan.index',
                                'pattern' => 'admin.laporan.*',
                                'label' => 'Laporan & Ekspor',
                                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>',
                            ],
                        ];
                    @endphp

                    @foreach ($navItems as $item)
                        @php
                            $pattern = $item['pattern'] ?? $item['route'];
                            $active = request()->routeIs($pattern);
                        @endphp
                        <a href="{{ route($item['route']) }}"
                           class="flex items-center gap-2.5 px-2.5 py-2 rounded-[4px] font-medium transition {{ $active ? 'bg-paper-white text-signal-blue border border-warm-mist font-semibold shadow-2xs' : 'text-olive-char hover:bg-pale-stone hover:text-deep-moss' }}">
                            <svg class="w-4 h-4 flex-shrink-0 {{ $active ? 'text-signal-blue' : 'text-sage-gray' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                {!! $item['icon'] !!}
                            </svg>
                            <span>{{ $item['label'] }}</span>
                        </a>
                    @endforeach
                </nav>
            </div>

            <!-- User Status & Logout -->
            <div class="p-2 border-t border-warm-mist bg-soft-linen">
                <div class="flex items-center justify-between p-1.5">
                    <div class="flex items-center gap-2 min-w-0">
                        <div class="w-6 h-6 rounded-full bg-deep-moss text-paper-white text-[10px] font-bold flex items-center justify-center flex-shrink-0">
                            {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                        </div>
                        <span class="text-xs font-semibold text-deep-moss truncate">{{ auth()->user()->name }}</span>
                    </div>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-sage-gray hover:text-rose-600 transition p-1" title="Logout">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- Main Window Container -->
        <main class="flex-1 flex flex-col overflow-hidden bg-paper-white border border-warm-mist rounded-window">
            <!-- Window Title Bar -->
            <header class="window-titlebar flex-shrink-0">
                <div class="flex items-center gap-2">
                    <span class="font-mono text-sage-gray text-xs">sipenma://admin/@yield('window_file', 'workspace.mdx')</span>
                </div>
                <div class="font-semibold text-deep-moss text-xs">
                    @yield('header', 'Admin Workspace')
                </div>
                <div class="flex items-center gap-3">
                    <span class="tag-pill tag-pill-stone text-[10px] font-mono">v1.0 monolith</span>
                </div>
            </header>

            <!-- Scrollable Page Content -->
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

                @if ($errors->any())
                    <div class="mb-4 p-3 rounded-[4px] bg-rose-50 border border-rose-300 text-rose-900 text-xs">
                        <div class="font-bold mb-1">Periksa kembali data masukan Anda:</div>
                        <ul class="list-disc list-inside space-y-0.5 text-rose-800">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>
</body>

</html>
