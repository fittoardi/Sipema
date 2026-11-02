@extends('layouts.admin')

@section('title', 'Ubah Mahasiswa')
@section('header', 'Data Mahasiswa')
@section('window_file', 'mahasiswa_edit.sys')

@section('content')
<div class="space-y-4 max-w-xl">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-base font-bold text-deep-moss">Ubah Data Mahasiswa</h1>
            <p class="text-xs text-olive-char mt-0.5">Perbarui biodata, penempatan fakultas & program studi, atau tahun angkatan.</p>
        </div>
        <a href="{{ route('admin.mahasiswa.index') }}" class="btn-mist">
            &larr; Kembali
        </a>
    </div>

    <div class="bg-paper-white border border-warm-mist rounded-[4px] p-5">
        <form action="{{ route('admin.mahasiswa.update', $mahasiswa) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label for="user_id" class="posthog-label">Akun Pengguna (User Mahasiswa) <span class="text-flame-orange">*</span></label>
                <select name="user_id" id="user_id" required class="posthog-select">
                    <option value="">Pilih Akun Pengguna</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" @selected($user->id == old('user_id', $mahasiswa->user_id))>
                            {{ $user->name }} ({{ $user->email }})
                        </option>
                    @endforeach
                </select>
                @error('user_id')
                    <p class="mt-1 text-xs text-flame-orange">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                <div>
                    <label for="nim" class="posthog-label">NIM <span class="text-flame-orange">*</span></label>
                    <input type="text" name="nim" id="nim" required value="{{ old('nim', $mahasiswa->nim) }}"
                           class="posthog-input font-mono">
                    @error('nim')
                        <p class="mt-1 text-xs text-flame-orange">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="angkatan" class="posthog-label">Tahun Angkatan <span class="text-flame-orange">*</span></label>
                    <input type="number" name="angkatan" id="angkatan" required min="2000" max="2099"
                           value="{{ old('angkatan', $mahasiswa->angkatan) }}" class="posthog-input font-mono">
                    @error('angkatan')
                        <p class="mt-1 text-xs text-flame-orange">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label for="nama" class="posthog-label">Nama Lengkap Mahasiswa <span class="text-flame-orange">*</span></label>
                <input type="text" name="nama" id="nama" required value="{{ old('nama', $mahasiswa->nama) }}"
                       class="posthog-input">
                @error('nama')
                    <p class="mt-1 text-xs text-flame-orange">{{ $message }}</p>
                @enderror
            </div>

            <div x-data="{
                selectedFakultas: '{{ $mahasiswa->programStudi?->fakultas_id ?? '' }}',
                selectedProdi: '{{ old('prodi_id', $mahasiswa->program_studi_id) }}',
                onFakultasChange(value) {
                    this.selectedFakultas = value;
                    if (this.selectedProdi && this.selectedFakultas) {
                        const opt = this.$refs.prodiSelect ? this.$refs.prodiSelect.querySelector(`option[value='${this.selectedProdi}']`) : null;
                        if (opt && opt.dataset.fakultasId && opt.dataset.fakultasId !== this.selectedFakultas) {
                            this.selectedProdi = '';
                        }
                    }
                }
            }" class="grid grid-cols-1 md:grid-cols-2 gap-3">
                <div>
                    <label for="filter_fakultas" class="posthog-label">Pilih Fakultas (Filter Pilihan)</label>
                    <select id="filter_fakultas"
                            x-model="selectedFakultas"
                            @change="onFakultasChange($event.target.value)"
                            class="posthog-select">
                        <option value="">-- Semua Fakultas (Pilihan Bebas) --</option>
                        @foreach($fakultas as $fak)
                            <option value="{{ $fak->id }}">{{ $fak->kode }} - {{ $fak->nama }}</option>
                        @endforeach
                    </select>
                    <p class="mt-1 text-[11px] text-sage-gray">Pilih fakultas untuk mengunci prodi dan menonaktifkan prodi fakultas lain.</p>
                </div>

                <div>
                    <label for="prodi_id" class="posthog-label">Program Studi <span class="text-flame-orange">*</span></label>
                    <select name="prodi_id" id="prodi_id" required
                            x-ref="prodiSelect"
                            x-model="selectedProdi"
                            class="posthog-select">
                        <option value="">-- Pilih Program Studi --</option>
                        @foreach($fakultas as $fak)
                            @if($fak->programStudi->isNotEmpty())
                                <optgroup label="{{ $fak->nama }} ({{ $fak->kode }})"
                                          :disabled="Boolean(selectedFakultas && selectedFakultas != '{{ $fak->id }}')">
                                    @foreach($fak->programStudi as $prodi)
                                        <option value="{{ $prodi->id }}"
                                                data-fakultas-id="{{ $fak->id }}"
                                                :disabled="Boolean(selectedFakultas && selectedFakultas != '{{ $fak->id }}')"
                                                @selected($prodi->id == old('prodi_id', $mahasiswa->program_studi_id))>
                                            {{ $prodi->nama_prodi }} ({{ $prodi->kode_prodi }})
                                        </option>
                                    @endforeach
                                </optgroup>
                            @endif
                        @endforeach
                        @php
                            $unassignedProdis = $prodis->filter(fn ($p) => empty($p->fakultas_id));
                        @endphp
                        @if($unassignedProdis->isNotEmpty())
                            <optgroup label="Program Studi Lainnya (Tanpa Fakultas)"
                                      :disabled="Boolean(selectedFakultas)">
                                @foreach($unassignedProdis as $prodi)
                                    <option value="{{ $prodi->id }}"
                                            data-fakultas-id=""
                                            :disabled="Boolean(selectedFakultas)"
                                            @selected($prodi->id == old('prodi_id', $mahasiswa->program_studi_id))>
                                        {{ $prodi->nama_prodi }} ({{ $prodi->kode_prodi }})
                                    </option>
                                @endforeach
                            </optgroup>
                        @endif
                    </select>
                    @error('prodi_id')
                        <p class="mt-1 text-xs text-flame-orange">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="pt-3 border-t border-warm-mist flex items-center justify-end gap-2">
                <a href="{{ route('admin.mahasiswa.index') }}" class="btn-mist">Batal</a>
                <button type="submit" class="btn-amber">Perbarui Mahasiswa</button>
            </div>
        </form>
    </div>
</div>
@endsection
