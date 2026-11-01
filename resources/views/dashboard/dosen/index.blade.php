<x-layouts.dashboard title="Dashboard Dosen">

	<div class="space-y-4">
		{{-- Header Workspace Card --}}
		<div class="bg-paper-white border border-warm-mist rounded-[4px] p-5">
			<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
				<div>
					<div class="flex items-center gap-2 mb-1">
						<span class="tag-pill tag-pill-amber text-[10px] font-mono font-bold">DOSEN PENGAMPU</span>
						@if($dosen->nidn)
							<span class="text-xs font-mono text-sage-gray">NIDN: {{ $dosen->nidn }}</span>
						@endif
					</div>
					<h1 class="text-lg font-bold text-deep-moss">{{ $dosen->nama ?? auth()->user()?->name }}</h1>
					<p class="text-xs text-olive-char mt-0.5">Ruang kerja evaluasi akademik dan kelola nilai kelas mahasiswa.</p>
				</div>

				<div class="flex flex-wrap items-center gap-2">
					<a href="{{ route('dosen.nilai.index') }}" class="btn-amber">
						<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
						Kelola Nilai
					</a>

					<a href="{{ route('dosen.dashboard.download-nilai') }}" class="btn-mist">
						<svg class="w-3.5 h-3.5 text-moss-green" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
						Unduh CSV
					</a>
				</div>
			</div>
		</div>

		{{-- Statistik Kartu --}}
		<div class="grid gap-3 sm:grid-cols-2 xl:grid-cols-4">
			<div class="bg-paper-white border border-warm-mist rounded-[4px] p-4">
				<div class="flex items-center justify-between text-xs text-sage-gray font-medium">
					<span>Total Mahasiswa</span>
					<span class="font-mono text-[10px] text-olive-char">STU</span>
				</div>
				<div class="mt-2 text-2xl font-bold font-mono text-deep-moss">{{ $totalMahasiswa }}</div>
				<p class="text-[11px] text-sage-gray mt-1">Mahasiswa di kelas binaan</p>
			</div>

			<div class="bg-paper-white border border-warm-mist rounded-[4px] p-4">
				<div class="flex items-center justify-between text-xs text-sage-gray font-medium">
					<span>Mata Kuliah</span>
					<span class="font-mono text-[10px] text-olive-char">CRS</span>
				</div>
				<div class="mt-2 text-2xl font-bold font-mono text-deep-moss">{{ $totalMataKuliah }}</div>
				<p class="text-[11px] text-sage-gray mt-1">Mata kuliah yang diampu</p>
			</div>

			<div class="bg-paper-white border border-warm-mist rounded-[4px] p-4">
				<div class="flex items-center justify-between text-xs text-sage-gray font-medium">
					<span>Sesi Perkuliahan</span>
					<span class="font-mono text-[10px] text-olive-char">SCH</span>
				</div>
				<div class="mt-2 text-2xl font-bold font-mono text-deep-moss">{{ $totalJadwal }}</div>
				<p class="text-[11px] text-sage-gray mt-1">Sesi pertemuan terdaftar</p>
			</div>

			<div class="bg-paper-white border border-warm-mist rounded-[4px] p-4">
				<div class="flex items-center justify-between text-xs text-sage-gray font-medium">
					<span>Rata-rata Nilai</span>
					<span class="font-mono text-[10px] text-olive-char">AVG</span>
				</div>
				<div class="mt-2 text-2xl font-bold font-mono text-deep-moss">{{ $ringkasanNilai }}</div>
				<p class="text-[11px] text-sage-gray mt-1">Skor kumulatif seluruh kelas</p>
			</div>
		</div>

		{{-- Daftar Kelas yang Diampu --}}
		<div class="bg-paper-white border border-warm-mist rounded-[4px] overflow-hidden">
			<div class="px-4 py-2.5 border-b border-warm-mist bg-cream-paper flex items-center justify-between text-xs">
				<span class="font-bold text-deep-moss">Kelas Perkuliahan Binaan</span>
				<a href="{{ route('dosen.courses') }}" class="text-signal-blue hover:underline">
					Semua Mata Kuliah &rarr;
				</a>
			</div>

			<div class="overflow-x-auto">
				<table class="w-full text-left text-xs">
					<thead class="bg-cream-paper border-b border-warm-mist font-semibold text-olive-char uppercase tracking-wider">
						<tr>
							<th class="px-4 py-2.5">Mata Kuliah</th>
							<th class="px-4 py-2.5">Kelas</th>
							<th class="px-4 py-2.5">Semester & TA</th>
							<th class="px-4 py-2.5 text-center">Peserta</th>
							<th class="px-4 py-2.5">Jadwal</th>
							<th class="px-4 py-2.5 text-right">Aksi</th>
						</tr>
					</thead>
					<tbody class="divide-y divide-warm-mist">
						@forelse($kelas as $k)
							<tr class="hover:bg-cream-paper transition">
								<td class="px-4 py-3">
									<div class="font-bold text-deep-moss">{{ $k->mataKuliah?->nama ?? '-' }}</div>
									<div class="text-[11px] font-mono text-sage-gray">{{ $k->mataKuliah?->kode ?? '-' }} ({{ $k->mataKuliah?->sks ?? 0 }} SKS)</div>
								</td>
								<td class="px-4 py-3">
									<span class="tag-pill tag-pill-stone font-mono font-bold">
										{{ $k->nama }}
									</span>
								</td>
								<td class="px-4 py-3 text-olive-char">
									{{ $k->semester }} {{ $k->tahun_akademik }}
								</td>
								<td class="px-4 py-3 text-center font-mono font-bold">
									{{ $k->mahasiswas_count }}
								</td>
								<td class="px-4 py-3 text-olive-char">
									@if($k->jadwals->isNotEmpty())
										@foreach($k->jadwals as $jadwal)
											<div class="text-[11px]">{{ $jadwal->hari }}, {{ substr($jadwal->jam_mulai, 0, 5) }}-{{ substr($jadwal->jam_selesai, 0, 5) }} ({{ $jadwal->ruangan }})</div>
										@endforeach
									@else
										<span class="text-sage-gray italic">Belum diatur</span>
									@endif
								</td>
								<td class="px-4 py-3 text-right">
									<a href="{{ route('dosen.nilai.index', ['kelas_id' => $k->id]) }}" class="btn-amber">
										Input Nilai
									</a>
								</td>
							</tr>
						@empty
							<tr>
								<td colspan="6" class="px-4 py-8 text-center text-xs text-sage-gray">
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
