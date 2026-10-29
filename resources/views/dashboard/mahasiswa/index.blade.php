<x-layouts.dashboard title="Dashboard Mahasiswa">

<div class="space-y-8">

    <div>

        <h1 class="text-3xl font-bold text-slate-800">
            Dashboard Mahasiswa
        </h1>

        <p class="mt-2 text-gray-500">

            Selamat datang,

            <span class="font-semibold">

                {{ $mahasiswa->user->name }}

            </span>

        </p>

    </div>

    <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-4">

        <div class="rounded-xl bg-white p-6 shadow">

            <p class="text-gray-500">

                Program Studi

            </p>

            <h2 class="mt-3 text-2xl font-bold">

                {{ $mahasiswa->prodi->nama_prodi }}

            </h2>

        </div>

        <div class="rounded-xl bg-white p-6 shadow">

            <p class="text-gray-500">

                Mata Kuliah

            </p>

            <h2 class="mt-3 text-2xl font-bold">

                {{ $jumlahMataKuliah }}

            </h2>

        </div>

        <div class="rounded-xl bg-white p-6 shadow">

            <p class="text-gray-500">

                Nilai

            </p>

            <h2 class="mt-3 text-2xl font-bold">

                {{ $jumlahNilai }}

            </h2>

        </div>

        <div class="rounded-xl bg-white p-6 shadow">

            <p class="text-gray-500">

                Rata-rata Nilai

            </p>

            <h2 class="mt-3 text-2xl font-bold">

                {{ number_format($rataNilai ?? 0,2) }}

            </h2>

        </div>

    </div>

    <div class="flex justify-end">
        <a href="{{ route('mahasiswa.dashboard.download-nilai') }}" class="inline-flex items-center rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2">
            Download Nilai (CSV)
        </a>
    </div>

    <div class="rounded-xl bg-white shadow">

        <div class="border-b px-6 py-4">

            <h2 class="font-semibold">

                Informasi Mahasiswa

            </h2>

        </div>

        <div class="grid gap-6 p-6 md:grid-cols-2">

            <div>

                <p class="text-sm text-gray-500">
                    NIM
                </p>

                <p class="font-semibold">

                    {{ $mahasiswa->nim }}

                </p>

            </div>

            <div>

                <p class="text-sm text-gray-500">
                    Angkatan
                </p>

                <p class="font-semibold">

                    {{ $mahasiswa->angkatan }}

                </p>

            </div>

            <div>

                <p class="text-sm text-gray-500">
                    Program Studi
                </p>

                <p class="font-semibold">

                    {{ $mahasiswa->prodi->nama_prodi }}

                </p>

            </div>

            <div>

                <p class="text-sm text-gray-500">
                    Email
                </p>

                <p class="font-semibold">

                    {{ $mahasiswa->user->email }}

                </p>

            </div>

        </div>

    </div>

</div>

</x-layouts.dashboard>
