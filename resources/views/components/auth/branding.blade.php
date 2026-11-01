<section class="hidden lg:flex relative overflow-hidden bg-cream-paper border-r border-warm-mist p-12 flex-col justify-between">
    {{-- Top Section --}}
    <div>
        {{-- App Identification --}}
        <div class="flex items-center gap-3">
            <div class="flex items-center justify-center w-12 h-12 rounded-[4px] bg-paper-white border border-warm-mist shadow-none">
                <img src="{{ asset('images/logo.png') }}" alt="Logo SIPEMA" class="w-8 h-8 object-contain">
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <h1 class="text-2xl font-black text-deep-moss font-sans tracking-tight">SIPEMA</h1>
                    <span class="tag-pill tag-pill-amber text-[10px] font-mono">v2.0-os</span>
                </div>
                <p class="text-xs text-olive-char font-mono">Sistem Pengelolaan Nilai Mahasiswa</p>
            </div>
        </div>

        {{-- Hero Context --}}
        <div class="mt-16 max-w-lg">
            <div class="inline-flex items-center gap-2 px-2.5 py-1 rounded-[4px] bg-soft-linen border border-warm-mist text-xs font-mono text-deep-moss mb-4">
                <span class="w-2 h-2 rounded-full bg-signal-blue animate-pulse"></span>
                <span>Tactile Academic Management OS</span>
            </div>

            <h2 class="text-4xl font-black leading-tight text-deep-moss tracking-tight font-sans">
                Kelola Nilai,<br>
                Wujudkan Prestasi.
            </h2>

            <p class="mt-4 text-sm leading-relaxed text-olive-char">
                Platform terpadu untuk dosen, mahasiswa, dan administrator dalam manajemen evaluasi kurikulum, bobot penilaian dinamis, dan kalkulasi transkrip akademik secara instan dan presisi.
            </p>

            {{-- Feature Bullets in Tactile Cards --}}
            <div class="mt-8 space-y-2">
                <div class="flex items-center gap-2.5 text-xs text-deep-moss font-mono bg-paper-white p-2.5 rounded-[4px] border border-warm-mist">
                    <span class="text-signal-blue font-bold">✓</span>
                    <span>Dynamic Weight & Scale Engine (Kuis, Tugas, UTS, UAS)</span>
                </div>
                <div class="flex items-center gap-2.5 text-xs text-deep-moss font-mono bg-paper-white p-2.5 rounded-[4px] border border-warm-mist">
                    <span class="text-signal-blue font-bold">✓</span>
                    <span>Real-time Transcripts & Batch Grade Recalculation</span>
                </div>
                <div class="flex items-center gap-2.5 text-xs text-deep-moss font-mono bg-paper-white p-2.5 rounded-[4px] border border-warm-mist">
                    <span class="text-signal-blue font-bold">✓</span>
                    <span>Clean PostHog Tactile Desktop Metaphor</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Bottom Stats --}}
    <div class="pt-8 border-t border-warm-mist">
        <div class="grid grid-cols-3 gap-3">
            <div class="rounded-[4px] bg-paper-white border border-warm-mist p-3">
                <p class="text-[10px] font-mono font-bold uppercase text-olive-char">Mahasiswa</p>
                <h3 class="text-xl font-black text-deep-moss font-mono mt-0.5">1.200+</h3>
            </div>
            <div class="rounded-[4px] bg-paper-white border border-warm-mist p-3">
                <p class="text-[10px] font-mono font-bold uppercase text-olive-char">Dosen</p>
                <h3 class="text-xl font-black text-deep-moss font-mono mt-0.5">75+</h3>
            </div>
            <div class="rounded-[4px] bg-paper-white border border-warm-mist p-3">
                <p class="text-[10px] font-mono font-bold uppercase text-olive-char">Mata Kuliah</p>
                <h3 class="text-xl font-black text-deep-moss font-mono mt-0.5">30+</h3>
            </div>
        </div>
        <p class="mt-3 text-[11px] text-ash-green font-mono">
            &copy; {{ date('Y') }} SIPEMA Core Architecture. All rights reserved.
        </p>
    </div>
</section>
