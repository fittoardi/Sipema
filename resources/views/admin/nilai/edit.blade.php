@extends('layouts.admin')

@section('title', 'Ubah Nilai')
@section('header', 'Penilaian Akademik')

@section('content')
<div class="space-y-6 max-w-2xl">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-extrabold text-slate-800 tracking-tight">Ubah Penilaian Mahasiswa</h1>
            <p class="text-xs text-slate-500 mt-0.5">Perbarui skor kuis, tugas, UTS, atau UAS mahasiswa</p>
        </div>
        <a href="{{ route('admin.nilai.index') }}"
           class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 text-xs font-semibold shadow-2xs transition">
            &larr; Kembali
        </a>
    </div>

    <div class="rounded-2xl bg-white p-8 shadow-xs border border-slate-200/80">
        <form action="{{ route('admin.nilai.update', $nilai) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label for="kelas_id" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                    Kelas Perkuliahan <span class="text-rose-500">*</span>
                </label>
                <select name="kelas_id"
                        id="kelas_id"
                        required
                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm text-slate-900 focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition shadow-2xs">
                    <option value="">Pilih Rombel Kelas</option>
                    @foreach($kelas as $k)
                        <option value="{{ $k->id }}" @selected($k->id == old('kelas_id', $nilai->kelas_id))>
                            {{ $k->mataKuliah?->nama ?? 'MK' }} - Kelas {{ $k->nama }} ({{ $k->semester }} {{ $k->tahun_akademik }})
                        </option>
                    @endforeach
                </select>
                @error('kelas_id')
                    <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="mahasiswa_id" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                    Mahasiswa <span class="text-rose-500">*</span>
                </label>
                <select name="mahasiswa_id"
                        id="mahasiswa_id"
                        required
                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm text-slate-900 focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition shadow-2xs">
                    <option value="">Pilih Mahasiswa</option>
                    @foreach($mahasiswas as $mhs)
                        <option value="{{ $mhs->id }}" @selected($mhs->id == old('mahasiswa_id', $nilai->mahasiswa_id))>
                            {{ $mhs->nim }} - {{ $mhs->nama }}
                        </option>
                    @endforeach
                </select>
                @error('mahasiswa_id')
                    <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 pt-2">
                <div>
                    <label for="kuis" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        Kuis (15%)
                    </label>
                    <input type="number"
                           step="0.01"
                           min="0"
                           max="100"
                           name="kuis"
                           id="kuis"
                           value="{{ old('kuis', $nilai->kuis) }}"
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm text-slate-900 focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition shadow-2xs font-mono text-center">
                    @error('kuis')
                        <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="tugas" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        Tugas (25%)
                    </label>
                    <input type="number"
                           step="0.01"
                           min="0"
                           max="100"
                           name="tugas"
                           id="tugas"
                           value="{{ old('tugas', $nilai->tugas) }}"
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm text-slate-900 focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition shadow-2xs font-mono text-center">
                    @error('tugas')
                        <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="uts" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        UTS (25%)
                    </label>
                    <input type="number"
                           step="0.01"
                           min="0"
                           max="100"
                           name="uts"
                           id="uts"
                           value="{{ old('uts', $nilai->uts) }}"
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm text-slate-900 focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition shadow-2xs font-mono text-center">
                    @error('uts')
                        <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="uas" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        UAS (35%)
                    </label>
                    <input type="number"
                           step="0.01"
                           min="0"
                           max="100"
                           name="uas"
                           id="uas"
                           value="{{ old('uas', $nilai->uas) }}"
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm text-slate-900 focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition shadow-2xs font-mono text-center">
                    @error('uas')
                        <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="p-4 rounded-xl bg-slate-50 border border-slate-200/80 flex items-center justify-between text-xs">
                <div>
                    <span class="font-semibold text-slate-800">Nilai Saat Ini:</span>
                    <span class="font-mono font-bold text-slate-900 ml-1">{{ number_format($nilai->nilai_akhir, 2) }}</span>
                    <span class="ml-1 text-slate-500">(Grade: <strong>{{ $nilai->grade }}</strong>, Status: <strong>{{ $nilai->status }}</strong>)</span>
                </div>
                <span class="text-[11px] text-slate-400">Dihitung otomatis ulang saat disimpan</span>
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                <a href="{{ route('admin.nilai.index') }}"
                   class="px-4 py-2.5 rounded-xl border border-slate-200 text-slate-600 text-xs font-semibold hover:bg-slate-50 transition">
                    Batal
                </a>
                <button type="submit"
                        class="px-6 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold shadow-xs shadow-blue-500/20 transition">
                    Perbarui Nilai
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
