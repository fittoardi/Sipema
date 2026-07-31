<section
    class="hidden lg:flex relative overflow-hidden bg-gradient-to-br from-blue-700 via-blue-600 to-indigo-900">

    {{-- Background Decoration --}}
    <div
        class="absolute -top-24 -left-24 w-80 h-80 rounded-full bg-white/10 blur-3xl">
    </div>

    <div
        class="absolute bottom-0 right-0 w-[450px] h-[450px] rounded-full bg-cyan-400/10 blur-3xl">
    </div>

    <div class="relative z-10 flex flex-col justify-between w-full px-16 py-14">

        {{-- Header --}}
        <div>

            <div class="flex items-center gap-4">

                <div
                    class="flex items-center justify-center w-16 h-16 rounded-2xl bg-white shadow-xl">

                    <img
                        src="{{ asset('images/logo.png') }}"
                        alt="Logo SIPEMA"
                        class="w-10 h-10 object-contain">

                </div>

                <div>

                    <h1 class="text-5xl font-black text-white">
                        SIPEMA
                    </h1>

                    <p class="text-blue-100">
                        Sistem Pengelolaan Nilai Mahasiswa
                    </p>

                </div>

            </div>

            {{-- Hero Text --}}
            <div class="mt-24">

                <span
                    class="inline-flex rounded-full bg-white/20 px-4 py-2 text-sm backdrop-blur">

                    🎓 Sistem Akademik Modern

                </span>

                <h2
                    class="mt-8 text-6xl font-black leading-tight text-white">

                    Kelola Nilai

                    <br>

                    Wujudkan Prestasi

                </h2>

                <p
                    class="mt-8 max-w-xl text-lg leading-9 text-blue-100">

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

            <div class="grid grid-cols-3 gap-5 mb-10">

                <div class="rounded-2xl bg-white/10 p-5 backdrop-blur">

                    <h3 class="text-3xl font-bold text-white">

                        1.200+

                    </h3>

                    <p class="mt-2 text-blue-100 text-sm">

                        Mahasiswa

                    </p>

                </div>

                <div class="rounded-2xl bg-white/10 p-5 backdrop-blur">

                    <h3 class="text-3xl font-bold text-white">

                        75+

                    </h3>

                    <p class="mt-2 text-blue-100 text-sm">

                        Dosen

                    </p>

                </div>

                <div class="rounded-2xl bg-white/10 p-5 backdrop-blur">

                    <h3 class="text-3xl font-bold text-white">

                        30+

                    </h3>

                    <p class="mt-2 text-blue-100 text-sm">

                        Mata Kuliah

                    </p>

                </div>

            </div>

            {{-- Illustration --}}

            <div class="flex justify-center">

                <img
                    src="{{ asset('images/login.svg') }}"
                    alt="Login Illustration"
                    class="w-[85%] max-w-lg drop-shadow-2xl">

            </div>

        </div>

    </div>

</section>
