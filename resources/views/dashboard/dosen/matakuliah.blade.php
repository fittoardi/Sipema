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

                    <div class="mt-4 pt-3 border-t border-warm-mist flex items-center justify-between text-xs">
                        <span class="font-mono text-[10px] text-sage-gray">#{{ $k->id }}</span>
                        <a href="{{ route('dosen.nilai.index', ['kelas_id' => $k->id]) }}" class="btn-amber">
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
