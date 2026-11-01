@php
	$role = auth()->user()?->role ?? null;

	$menus = match($role) {
		'dosen' => [
			['label' => 'Dashboard', 'route' => 'dosen.dashboard'],
			['label' => 'Mata Kuliah', 'route' => 'dosen.courses'],
			['label' => 'Jadwal Mengajar', 'route' => 'dosen.schedule'],
			['label' => 'Kelola Nilai', 'route' => 'dosen.nilai.index'],
			['label' => 'Unduh CSV Nilai', 'route' => 'dosen.dashboard.download-nilai'],
		],
		'mahasiswa' => [
			['label' => 'Dashboard', 'route' => 'mahasiswa.dashboard'],
			['label' => 'Unduh Nilai (CSV)', 'route' => 'mahasiswa.dashboard.download-nilai'],
		],
		default => [
			['label' => 'Dashboard', 'route' => 'login'],
		],
	};
@endphp

<aside class="col-span-12 lg:col-span-2 xl:col-span-2 bg-white shadow p-4">
	<div class="mb-6 flex items-center gap-3">
		<img src="{{ asset('images/logo.png') }}" alt="logo" class="h-10 w-10 object-contain" onerror="this.style.display='none'">
		<div>
			<div class="font-bold text-slate-800">{{ config('app.name', 'SIPEMA') }}</div>
			<div class="text-xs text-blue-600 font-semibold uppercase tracking-wider">{{ $role ?? 'User' }}</div>
		</div>
	</div>

	<nav class="space-y-1.5">
		@foreach($menus as $item)
			@php
				$isActive = isset($item['route']) && request()->routeIs($item['route']);
				$href = isset($item['route']) ? (Route::has($item['route']) ? route($item['route']) : ($item['url'] ?? '#')) : ($item['url'] ?? '#');
			@endphp

			<a href="{{ $href }}" class="flex items-center gap-2 rounded-lg px-3 py-2 text-sm font-medium transition {{ $isActive ? 'bg-blue-50 font-bold text-blue-700' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
				{{ $item['label'] }}
			</a>
		@endforeach
	</nav>

</aside>
