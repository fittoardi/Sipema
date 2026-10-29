<section
    class="hidden lg:flex relative overflow-hidden bg-[#1E3A5F]">

    {{-- Background Decoration --}}
    <div
        class="absolute -top-24 -left-24 w-80 h-80 rounded-full bg-[#B45309]/20 blur-2xl">
    </div>

    <div class="relative z-10 flex flex-col justify-between w-full px-16 py-14">

        {{-- Header --}}
        <div>

            <div class="flex items-center gap-4">

                <div
                    class="flex items-center justify-center w-16 h-16 rounded-xl bg-white shadow">

                    <img
                        src="{{ asset('images/logo.png') }}"
                        alt="Logo SIPEMA"
                        class="w-10 h-10 object-contain">

                </div>

                <div>

                    <h1 class="text-4xl font-bold text-white font-serif">
                        SIPEMA
                    </h1>

                    <p class="text-blue-200 text-sm">
                        Sistem Pengelolaan Nilai Mahasiswa
                    </p>

                </div>

            </div>

            {{-- Hero Text --}}
            <div class="mt-20">

                <span
                    class="inline-flex rounded-md bg-[#B45309] px-3 py-1 text-xs font-semibold text-white uppercase tracking-wider">
                    Akademik Modern
                </span>

                <h2
                    class="mt-6 text-5xl font-bold leading-tight text-white font-serif">
                    Kelola Nilai
                    <br>
                    Wujudkan Prestasi
                </h2>

                <p
                    class="mt-6 max-w-xl text-base leading-relaxed text-blue-100">
                    SIPEMA membantu dosen, admin,
                    dan program studi mengelola nilai,
                    KHS, transkrip, serta seluruh
                    aktivitas akademik secara cepat,
                    aman, dan terintegrasi.
                </p>

            </div>

        </div>

        {{-- Bottom --}}
        <div>

            {{-- Statistics --}}
            <div class="grid grid-cols-3 gap-4">

                <div class="rounded-xl bg-white/5 border border-white/10 p-4">

                    <h3 class="text-2xl font-bold text-white">
                        1.200+
                    </h3>

                    <p class="mt-1 text-blue-200 text-xs">
                        Mahasiswa
                    </p>

                </div>

                <div class="rounded-xl bg-white/5 border border-white/10 p-4">

                    <h3 class="text-2xl font-bold text-white">
                        75+
                    </h3>

                    <p class="mt-1 text-blue-200 text-xs">
                        Dosen
                    </p>

                </div>

                <div class="rounded-xl bg-white/5 border border-white/10 p-4">

                    <h3 class="text-2xl font-bold text-white">
                        30+
                    </h3>

                    <p class="mt-1 text-blue-200 text-xs">
                        Mata Kuliah
                    </p>

                </div>

            </div>

        </div>

    </div>

</section>
