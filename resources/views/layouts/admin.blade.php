<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ isset($title) ? $title . ' | Admin' : 'Admin' }} - {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-slate-100 text-slate-900">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-slate-800 text-white flex-shrink-0">
            <div class="p-4 border-b border-slate-700">
                <h1 class="text-xl font-bold">Admin Panel</h1>
            </div>
            <nav class="p-4 space-y-2">
                <a href="{{ route('admin.dashboard') }}" class="block px-3 py-2 rounded hover:bg-slate-700">Dashboard</a>
                <a href="{{ route('admin.prodi.index') }}" class="block px-3 py-2 rounded hover:bg-slate-700">Prodi</a>
                <a href="{{ route('admin.dosen.index') }}" class="block px-3 py-2 rounded hover:bg-slate-700">Dosen</a>
                <a href="{{ route('admin.mahasiswa.index') }}" class="block px-3 py-2 rounded hover:bg-slate-700">Mahasiswa</a>
                <a href="{{ route('admin.matakuliah.index') }}" class="block px-3 py-2 rounded hover:bg-slate-700">Mata Kuliah</a>
                <a href="{{ route('admin.kelas.index') }}" class="block px-3 py-2 rounded hover:bg-slate-700">Kelas</a>
                <a href="{{ route('admin.nilai.index') }}" class="block px-3 py-2 rounded hover:bg-slate-700">Nilai</a>
                <a href="{{ route('admin.laporan.index') }}" class="block px-3 py-2 rounded hover:bg-slate-700">Laporan</a>
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Header -->
            <header class="bg-white shadow-sm border-b">
                <div class="px-6 py-4 flex items-center justify-between">
                    <h2 class="text-2xl font-semibold">@yield('header', 'Dashboard')</h2>
                    <div class="flex items-center gap-4">
                        <span class="text-sm text-gray-600">{{ auth()->user()->name }}</span>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="text-sm text-red-600 hover:text-red-800">Logout</button>
                        </form>
                    </div>
                </div>
            </header>

            <!-- Content -->
            <main class="flex-1 overflow-y-auto p-6">
                @if (session('success'))
                    <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="mb-4 p-4 bg-red-100 text-red-800 rounded">
                        {{ session('error') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-4 p-4 bg-red-100 text-red-800 rounded">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>
</body>

</html>
