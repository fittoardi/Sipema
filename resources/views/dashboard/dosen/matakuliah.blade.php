<x-layouts.dashboard title="Mata Kuliah yang Diampu">
    <div class="space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h1 class="text-base font-bold text-deep-moss">Mata Kuliah yang Diampu</h1>
                <p class="text-xs text-olive-char mt-0.5">Daftar seluruh mata kuliah dan rombongan belajar perkuliahan binaan Anda.</p>
            </div>

            <a href="{{ route('dosen.nilai.index') }}" class="btn-amber">
                Kelola Nilai
            </a>
        </div>

        <div class="grid gap-3 md:grid-cols-2 xl:grid-cols-3">
            @forelse($kelas as $k)
                <div class="bg-paper-white border border-warm-mist rounded-[4px] p-4 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between gap-2 pb-2 border-b border-warm-mist">
                            <span class="font-mono font-bold text-signal-blue text-xs">
                                {{ $k->mataKuliah?->kode ?? '-' }}
                            </span>
                            <span class="tag-pill tag-pill-stone font-mono font-bold text-[10px]">
                                {{ $k->nama }}
                            </span>
                        </div>

                        <h3 class="text-sm font-bold text-deep-moss mt-2.5">
                            {{ $k->mataKuliah?->nama ?? 'Tanpa Mata Kuliah' }}
                        </h3>

                        <div class="mt-3 space-y-1 text-xs text-olive-char">
                            <div class="flex items-center justify-between">
                                <span class="text-sage-gray">Prodi:</span>
                                <span class="font-medium text-deep-moss">{{ $k->mataKuliah?->prodi?->nama_prodi ?? '-' }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sage-gray">SKS:</span>
                                <span class="font-mono font-bold text-deep-moss">{{ $k->mataKuliah?->sks ?? 0 }} SKS</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sage-gray">Semester / TA:</span>
                                <span class="text-deep-moss">{{ $k->semester }} {{ $k->tahun_akademik }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sage-gray">Mahasiswa:</span>
                                <span class="font-mono font-bold text-deep-moss">{{ $k->mahasiswas_count }} orang</span>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 pt-3 border-t border-warm-mist flex flex-wrap items-center justify-between gap-2 text-xs">
                        <a href="{{ route('dosen.pertemuan.index', $k->id) }}" class="btn-mist inline-flex items-center gap-1 text-[11px]">
                            <svg class="w-3.5 h-3.5 text-signal-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                            Buka LMS
                        </a>
                        <a href="{{ route('dosen.nilai.index', ['kelas_id' => $k->id]) }}" class="btn-amber text-[11px]">
                            Input Nilai &rarr;
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-full bg-paper-white border border-warm-mist rounded-[4px] p-8 text-center text-xs text-sage-gray">
                    Belum ada mata kuliah yang diampu.
                </div>
            @endforelse
        </div>
    </div>
</x-layouts.dashboard>
