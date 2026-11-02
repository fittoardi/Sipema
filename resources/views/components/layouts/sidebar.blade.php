@php
	$userRole = auth()->user()?->role;
	$role = $userRole instanceof \App\Enums\Role ? $userRole->value : (string) ($userRole ?? '');

	$menus = match($role) {
		'admin' => [
			['label' => 'Dashboard', 'route' => 'admin.dashboard', 'icon' => 'home'],
			['label' => 'Kategori Fakultas', 'route' => 'admin.fakultas.index', 'icon' => 'building'],
			['label' => 'Program Studi', 'route' => 'admin.prodi.index', 'icon' => 'academic-cap'],
			['label' => 'Dosen Pengajar', 'route' => 'admin.dosen.index', 'icon' => 'users'],
			['label' => 'Mahasiswa', 'route' => 'admin.mahasiswa.index', 'icon' => 'academic-cap'],
			['label' => 'Mata Kuliah', 'route' => 'admin.matakuliah.index', 'icon' => 'book'],
			['label' => 'Rombel Kelas', 'route' => 'admin.kelas.index', 'icon' => 'calendar'],
			['label' => 'Rekap Nilai', 'route' => 'admin.nilai.index', 'icon' => 'clipboard'],
			['label' => 'Pengaturan Nilai', 'route' => 'admin.pengaturan-nilai.index', 'icon' => 'cog'],
			['label' => 'Laporan Akademik', 'route' => 'admin.laporan.index', 'icon' => 'document-report'],
		],
		'dosen' => [
			['label' => 'Dashboard', 'route' => 'dosen.dashboard', 'icon' => 'home'],
			['label' => 'Perwalian KRS', 'route' => 'dosen.perwalian.index', 'icon' => 'users'],
			['label' => 'Mata Kuliah & LMS', 'route' => 'dosen.courses', 'icon' => 'book'],
			['label' => 'Jadwal Mengajar', 'route' => 'dosen.schedule', 'icon' => 'calendar'],
			['label' => 'Kelola Nilai', 'route' => 'dosen.nilai.index', 'icon' => 'clipboard'],
			['label' => 'Unduh CSV Nilai', 'route' => 'dosen.dashboard.download-nilai', 'icon' => 'download'],
		],
		'mahasiswa' => [
			['heading' => 'Akademik & Jadwal'],
			['label' => 'Dashboard Nilai', 'route' => 'mahasiswa.dashboard', 'icon' => 'home'],
			['label' => 'Jadwal & Kalender', 'route' => 'mahasiswa.jadwal', 'icon' => 'calendar'],
			['label' => 'KRS Online', 'route' => 'mahasiswa.krs.index', 'icon' => 'clipboard'],
			['heading' => 'E-Learning & Perkuliahan'],
			['label' => 'Mata Kuliah & LMS', 'route' => 'mahasiswa.kelas.index', 'icon' => 'book'],
			['label' => 'Tugas & Penugasan', 'route' => 'mahasiswa.tugas.index', 'icon' => 'document-check'],
			['label' => 'Rekap Presensi', 'route' => 'mahasiswa.presensi.index', 'icon' => 'fingerprint'],
			['label' => 'Unduh Nilai (CSV)', 'route' => 'mahasiswa.dashboard.download-nilai', 'icon' => 'download'],
		],
		default => [
			['label' => 'Dashboard', 'route' => 'login', 'icon' => 'home'],
		],
	};
@endphp

<aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="fixed inset-y-0 left-0 z-50 w-60 bg-cream-paper border border-warm-mist rounded-window flex-shrink-0 flex flex-col justify-between overflow-hidden transform lg:translate-x-0 lg:static transition-transform duration-200 ease-in-out">
	<div>
		<!-- Window Header -->
		<div class="px-4 py-3 border-b border-warm-mist bg-soft-linen flex items-center justify-between">
			<div class="flex items-center gap-2">
				<span class="w-2.5 h-2.5 rounded-full bg-rose-400 border border-rose-500/50"></span>
				<span class="w-2.5 h-2.5 rounded-full bg-amber-400 border border-amber-500/50"></span>
				<span class="w-2.5 h-2.5 rounded-full bg-emerald-400 border border-emerald-500/50"></span>
			</div>
			<button @click="sidebarOpen = false" class="lg:hidden text-sage-gray">
				<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
			</button>
		</div>

		<!-- Identity Badge -->
		<div class="px-4 py-3.5 border-b border-warm-mist bg-paper-white flex items-center gap-2.5">
			<div class="w-7 h-7 rounded-[4px] bg-amber-glow flex items-center justify-center text-deep-moss font-bold text-xs border border-burnished-gold">
				{{ strtoupper(substr($role ?? 'U', 0, 1)) }}
			</div>
			<div>
				<div class="font-bold text-xs tracking-tight text-deep-moss uppercase">{{ config('app.name', 'SIPENMA') }}</div>
				<div class="text-[10px] font-mono text-olive-char uppercase tracking-wider">{{ $role }} Portal</div>
			</div>
		</div>

		<!-- Menu list -->
		<nav class="p-2 space-y-0.5 overflow-y-auto text-xs">
			@foreach($menus as $item)
				@if(isset($item['heading']))
					<div class="px-2.5 pt-3 pb-1 text-[10px] font-mono font-bold text-sage-gray uppercase tracking-wider">
						{{ $item['heading'] }}
					</div>
				@else
					@php
						$isActive = isset($item['route']) && request()->routeIs($item['route']);
						$href = isset($item['route']) ? (Route::has($item['route']) ? route($item['route']) : ($item['url'] ?? '#')) : ($item['url'] ?? '#');
					@endphp

					<a href="{{ $href }}"
					   class="flex items-center gap-2.5 px-2.5 py-2 rounded-[4px] font-medium transition {{ $isActive ? 'bg-paper-white text-signal-blue border border-warm-mist font-semibold shadow-2xs' : 'text-olive-char hover:bg-pale-stone hover:text-deep-moss' }}">
						<span class="w-1.5 h-1.5 rounded-full {{ $isActive ? 'bg-signal-blue' : 'bg-warm-mist' }}"></span>
						<span>{{ $item['label'] }}</span>
					</a>
				@endif
			@endforeach
		</nav>
	</div>

	<!-- User Card & Logout -->
	<div class="p-2 border-t border-warm-mist bg-soft-linen">
		<div class="flex items-center justify-between p-1.5">
			<div class="flex items-center gap-2 min-w-0">
				<div class="w-6 h-6 rounded-full bg-deep-moss text-paper-white text-[10px] font-bold flex items-center justify-center flex-shrink-0">
					{{ strtoupper(substr(auth()->user()?->name ?? 'U', 0, 1)) }}
				</div>
				<span class="text-xs font-semibold text-deep-moss truncate">{{ auth()->user()?->name }}</span>
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
