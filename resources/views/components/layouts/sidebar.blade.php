@php
	$role = auth()->user()?->role ?? null;

	$menus = match($role) {
		'dosen' => [
			['label' => 'Dashboard', 'route' => 'dosen.dashboard'],
			['label' => 'Mata Kuliah', 'route' => 'dosen.courses', 'url' => '#'],
			['label' => 'Jadwal', 'route' => 'dosen.schedule', 'url' => '#'],
			['label' => 'Kehadiran', 'route' => 'dosen.attendance', 'url' => '#'],
		],
		'mahasiswa' => [
			['label' => 'Dashboard', 'route' => 'mahasiswa.dashboard'],
			['label' => 'Kehadiran', 'route' => 'mahasiswa.attendance', 'url' => '#'],
			['label' => 'Nilai', 'route' => 'mahasiswa.scores', 'url' => '#'],
			['label' => 'Jadwal', 'route' => 'mahasiswa.schedule', 'url' => '#'],
		],
		default => [
			['label' => 'Dashboard', 'route' => 'login', 'url' => route('login')],
		],
	};
@endphp

<aside class="col-span-12 lg:col-span-2 xl:col-span-2 bg-white shadow p-4">
	<div class="mb-6 flex items-center gap-3">
		<img src="{{ asset('images/logo.png') }}" alt="logo" class="h-10 w-10 object-contain">
		<div>
			<div class="font-bold">{{ config('app.name') }}</div>
			<div class="text-xs text-gray-500">{{ $role }}</div>
		</div>
	</div>

	<nav class="space-y-2">
		@foreach($menus as $item)
			@php
				$isActive = isset($item['route']) && request()->routeIs($item['route']);
				$href = isset($item['route']) ? (Route::has($item['route']) ? route($item['route']) : ($item['url'] ?? '#')) : ($item['url'] ?? '#');
			@endphp

			<a href="{{ $href }}" class="block rounded px-3 py-2 text-sm font-medium {{ $isActive ? 'bg-slate-50 font-semibold text-slate-800' : 'text-slate-700 hover:bg-slate-50' }}">
				{{ $item['label'] }}
			</a>
		@endforeach
	</nav>

</aside>
