<x-layouts.dashboard title="Dashboard Dosen">

	<div class="space-y-8">

		<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
			<div>
				<h1 class="text-3xl font-bold text-slate-800">Dashboard Dosen</h1>
				<p class="mt-1 text-gray-500">
					Selamat datang, <span class="font-semibold text-slate-700">{{ $dosen->nama ?? auth()->user()?->name }}</span>
					@if($dosen->nidn)
						<span class="text-xs bg-slate-200 text-slate-700 rounded px-2 py-0.5 ml-2">NIDN: {{ $dosen->nidn }}</span>
					@endif
				</p>
			</div>

			<div class="flex flex-wrap items-center gap-3">
				<a href="{{ route('dosen.nilai.index') }}" class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2.5 font-medium text-white shadow-sm hover:bg-blue-700 transition">
					<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
					Input Nilai
				</a>

				<a href="{{ route('dosen.dashboard.download-nilai') }}" class="inline-flex items-center gap-2 rounded-lg bg-emerald-600 px-4 py-2.5 font-medium text-white shadow-sm hover:bg-emerald-700 transition">
					<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
					Unduh CSV
				</a>
			</div>
		</div>

		{{-- Statistik Kartu --}}
		<div class="grid gap-6 sm:grid-cols-2 xl:grid-cols-4">
			<div class="rounded-xl bg-white p-6 shadow-sm border border-slate-100 flex items-center justify-between">
				<div>
					<p class="text-sm font-medium text-gray-500">Total Mahasiswa</p>
					<h2 class="mt-2 text-3xl font-bold text-slate-800">{{ $totalMahasiswa }}</h2>
					<p class="text-xs text-gray-400 mt-1">Mahasiswa terdaftar di kelas</p>
				</div>
				<div class="p-3 bg-blue-50 text-blue-600 rounded-xl">
					<svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
				</div>
			</div>

			<div class="rounded-xl bg-white p-6 shadow-sm border border-slate-100 flex items-center justify-between">
				<div>
					<p class="text-sm font-medium text-gray-500">Mata Kuliah</p>
					<h2 class="mt-2 text-3xl font-bold text-slate-800">{{ $totalMataKuliah }}</h2>
					<p class="text-xs text-gray-400 mt-1">Mata kuliah yang diampu</p>
				</div>
				<div class="p-3 bg-indigo-50 text-indigo-600 rounded-xl">
					<svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
				</div>
			</div>

			<div class="rounded-xl bg-white p-6 shadow-sm border border-slate-100 flex items-center justify-between">
				<div>
					<p class="text-sm font-medium text-gray-500">Jadwal Perkuliahan</p>
					<h2 class="mt-2 text-3xl font-bold text-slate-800">{{ $totalJadwal }}</h2>
					<p class="text-xs text-gray-400 mt-1">Sesi pertemuan terjadwal</p>
				</div>
				<div class="p-3 bg-amber-50 text-amber-600 rounded-xl">
					<svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
				</div>
			</div>

			<div class="rounded-xl bg-white p-6 shadow-sm border border-slate-100 flex items-center justify-between">
				<div>
					<p class="text-sm font-medium text-gray-500">Rata-rata Nilai</p>
					<h2 class="mt-2 text-3xl font-bold text-slate-800">{{ $ringkasanNilai }}</h2>
					<p class="text-xs text-gray-400 mt-1">Skor kumulatif seluruh kelas</p>
				</div>
				<div class="p-3 bg-purple-50 text-purple-600 rounded-xl">
					<svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
				</div>
			</div>
		</div>

		{{-- Daftar Kelas yang Diampu --}}
		<div class="rounded-xl bg-white shadow-sm border border-slate-100 overflow-hidden">
			<div class="px-6 py-5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
				<div>
					<h2 class="text-lg font-bold text-slate-800">Kelas yang Diampu</h2>
					<p class="text-sm text-gray-500">Daftar kelas perkuliahan aktif untuk dosen</p>
				</div>
				<a href="{{ route('dosen.courses') }}" class="text-sm font-semibold text-blue-600 hover:text-blue-700">
					Lihat Semua Mata Kuliah &rarr;
				</a>
			</div>

			<div class="overflow-x-auto">
				<table class="min-w-full divide-y divide-slate-100 text-sm">
					<thead class="bg-slate-50 text-left text-slate-600">
						<tr>
							<th class="px-6 py-3.5 font-semibold">Mata Kuliah</th>
							<th class="px-6 py-3.5 font-semibold">Kelas</th>
							<th class="px-6 py-3.5 font-semibold">Semester / TA</th>
							<th class="px-6 py-3.5 font-semibold text-center">Peserta</th>
							<th class="px-6 py-3.5 font-semibold">Jadwal</th>
							<th class="px-6 py-3.5 font-semibold text-right">Aksi</th>
						</tr>
					</thead>
					<tbody class="divide-y divide-slate-100">
						@forelse($kelas as $k)
							<tr class="hover:bg-slate-50/60 transition">
								<td class="px-6 py-4">
									<div class="font-medium text-slate-900">{{ $k->mataKuliah?->nama ?? '-' }}</div>
									<div class="text-xs text-gray-500">{{ $k->mataKuliah?->kode ?? '-' }} ({{ $k->mataKuliah?->sks ?? 0 }} SKS)</div>
								</td>
								<td class="px-6 py-4">
									<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-50 text-blue-700">
										{{ $k->nama }}
									</span>
								</td>
								<td class="px-6 py-4 text-slate-600">
									{{ $k->semester }} {{ $k->tahun_akademik }}
								</td>
								<td class="px-6 py-4 text-center">
									<span class="font-semibold text-slate-800">{{ $k->mahasiswas_count }}</span> orang
								</td>
								<td class="px-6 py-4 text-slate-600">
									@if($k->jadwals->isNotEmpty())
										@foreach($k->jadwals as $jadwal)
											<div class="text-xs">{{ $jadwal->hari }}, {{ substr($jadwal->jam_mulai, 0, 5) }}-{{ substr($jadwal->jam_selesai, 0, 5) }} ({{ $jadwal->ruangan }})</div>
										@endforeach
									@else
										<span class="text-xs text-gray-400 italic">Belum diatur</span>
									@endif
								</td>
								<td class="px-6 py-4 text-right">
									<a href="{{ route('dosen.nilai.index', ['kelas_id' => $k->id]) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-blue-50 text-blue-700 hover:bg-blue-100 font-medium text-xs transition">
										<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
										Kelola Nilai
									</a>
								</td>
							</tr>
						@empty
							<tr>
								<td colspan="6" class="px-6 py-8 text-center text-gray-500">
									Belum ada kelas yang diampu saat ini.
								</td>
							</tr>
						@endforelse
					</tbody>
				</table>
			</div>
		</div>

	</div>

</x-layouts.dashboard>
