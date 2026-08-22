<x-layouts.dashboard title="Input Nilai Dosen">
    <div class="space-y-8">
        <div>
            <h1 class="text-3xl font-bold text-slate-800">Input Nilai</h1>
            <p class="mt-2 text-gray-500">Kelola nilai mahasiswa pada kelas yang Anda ampu.</p>
        </div>

        @if (session('status'))
            <div class="rounded-xl border border-green-200 bg-green-50 p-4 text-sm text-green-700">{{ session('status') }}</div>
        @endif

        @if ($errors->any())
            <div class="rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">{{ $errors->first() }}</div>
        @endif

        @forelse ($kelas as $class)
            <section class="overflow-hidden rounded-xl bg-white shadow">
                <div class="flex flex-wrap items-center justify-between gap-4 border-b px-6 py-4">
                    <div>
                        <h2 class="font-semibold text-slate-800">{{ $class->mataKuliah->kode }} - {{ $class->mataKuliah->nama }}</h2>
                        <p class="text-sm text-gray-500">{{ $class->nama }} | {{ $class->tahun_akademik }} | {{ $class->semester }}</p>
                    </div>
                    <span class="text-sm text-gray-500">{{ $class->mahasiswas->count() }} peserta</span>
                </div>

                @foreach ($class->mahasiswas as $student)
                    <form id="score-form-{{ $class->id }}-{{ $student->id }}" method="POST" action="{{ route('dosen.nilai.store') }}">
                        @csrf
                        <input type="hidden" name="kelas_id" value="{{ $class->id }}">
                        <input type="hidden" name="mahasiswa_id" value="{{ $student->id }}">
                    </form>
                @endforeach

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50 text-left text-gray-600">
                            <tr>
                                <th class="px-6 py-3">Mahasiswa</th>
                                <th class="px-3 py-3">Kuis</th>
                                <th class="px-3 py-3">Tugas</th>
                                <th class="px-3 py-3">UTS</th>
                                <th class="px-3 py-3">UAS</th>
                                <th class="px-6 py-3">Hasil</th>
                                <th class="px-6 py-3"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach ($class->mahasiswas as $student)
                                @php($score = $class->nilais->firstWhere('mahasiswa_id', $student->id))
                                <tr>
                                    @php($formId = 'score-form-'.$class->id.'-'.$student->id)
                                        <td class="px-6 py-4">
                                            <div class="font-medium text-slate-800">{{ $student->user->name }}</div>
                                            <div class="text-gray-500">{{ $student->nim }}</div>
                                        </td>
                                        @foreach (['kuis', 'tugas', 'uts', 'uas'] as $component)
                                            <td class="px-3 py-4">
                                                <input form="{{ $formId }}" class="w-20 rounded-lg border border-gray-300 px-2 py-2" type="number" name="{{ $component }}" min="0" max="100" step="0.01" value="{{ old($component, $score?->{$component} ?? 0) }}" required>
                                            </td>
                                        @endforeach
                                        <td class="px-6 py-4">
                                            <div class="font-semibold">{{ $score?->nilai_akhir ?? '-' }}</div>
                                            <div class="text-gray-500">{{ $score?->grade ?? '-' }} / {{ $score?->status ?? '-' }}</div>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <button form="{{ $formId }}" class="rounded-lg bg-blue-600 px-4 py-2 font-medium text-white hover:bg-blue-700" type="submit">Simpan</button>
                                        </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </section>
        @empty
            <div class="rounded-xl bg-white p-8 text-center text-gray-500 shadow">Belum ada kelas yang diampu.</div>
        @endforelse
    </div>
</x-layouts.dashboard>
