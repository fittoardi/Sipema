<x-layouts.dashboard title="Mata Kuliah yang Diampu">
    <div class="space-y-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-slate-800">Mata Kuliah yang Diampu</h1>
                <p class="mt-1 text-gray-500">Daftar seluruh mata kuliah dan rombongan belajar perkuliahan Anda.</p>
            </div>

            <a href="{{ route('dosen.nilai.index') }}" class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 font-medium text-white shadow-sm hover:bg-blue-700 transition text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                Kelola Nilai
            </a>
        </div>

        <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
            @forelse($kelas as $k)
                <div class="rounded-xl bg-white p-6 shadow-sm border border-slate-100 flex flex-col justify-between hover:shadow-md transition">
                    <div>
                        <div class="flex items-center justify-between gap-2">
                            <span class="text-xs font-mono font-semibold px-2 py-0.5 rounded bg-slate-100 text-slate-700">
                                {{ $k->mataKuliah?->kode ?? '-' }}
                            </span>
                            <span class="text-xs font-semibold px-2.5 py-0.5 rounded-full bg-blue-50 text-blue-700">
                                {{ $k->nama }}
                            </span>
                        </div>

                        <h3 class="text-lg font-bold text-slate-900 mt-3">
                            {{ $k->mataKuliah?->nama ?? 'Tanpa Mata Kuliah' }}
                        </h3>

                        <div class="mt-3 space-y-1.5 text-xs text-gray-500">
                            <div class="flex items-center justify-between">
                                <span>Program Studi:</span>
                                <span class="font-medium text-slate-700">{{ $k->mataKuliah?->prodi?->nama_prodi ?? '-' }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span>Bobot SKS:</span>
                                <span class="font-medium text-slate-700">{{ $k->mataKuliah?->sks ?? 0 }} SKS</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span>Semester / TA:</span>
                                <span class="font-medium text-slate-700">{{ $k->semester }} {{ $k->tahun_akademik }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span>Total Mahasiswa:</span>
                                <span class="font-semibold text-slate-800">{{ $k->mahasiswas_count }} orang</span>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between">
                        <span class="text-xs text-gray-400">ID Kelas: #{{ $k->id }}</span>
                        <a href="{{ route('dosen.nilai.index', ['kelas_id' => $k->id]) }}" class="inline-flex items-center gap-1 text-xs font-semibold text-blue-600 hover:text-blue-800">
                            Input Nilai &rarr;
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-full rounded-xl bg-white p-12 text-center text-gray-500 shadow-sm border border-slate-100">
                    <p class="text-base font-medium text-slate-700">Belum ada mata kuliah yang diampu.</p>
                </div>
            @endforelse
        </div>
    </div>
</x-layouts.dashboard>

