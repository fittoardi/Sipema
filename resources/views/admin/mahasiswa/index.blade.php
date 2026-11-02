@extends('layouts.admin')

@section('title', 'Data Mahasiswa')
@section('header', 'Data Mahasiswa')
@section('window_file', 'mahasiswa.sys')

@section('content')
<div class="space-y-4">
    {{-- Header & Action Button --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h1 class="text-base font-bold text-deep-moss">Manajemen Data Mahasiswa</h1>
            <p class="text-xs text-olive-char mt-0.5">Kelola data mahasiswa terorganisir per fakultas, program studi, dan angkatan.</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.fakultas.index') }}" class="btn-mist">
                Master Fakultas
            </a>
            <a href="{{ route('admin.mahasiswa.create') }}" class="btn-amber">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Tambah Mahasiswa
            </a>
        </div>
    </div>

    {{-- Faculty Filter Tabs --}}
    <div class="flex items-center gap-2 overflow-x-auto pb-1 text-xs">
        <a href="{{ route('admin.mahasiswa.index', array_filter(['search' => $search])) }}"
           class="px-3 py-1.5 rounded-[4px] border font-mono transition whitespace-nowrap {{ empty($fakultasId) ? 'bg-deep-moss text-paper-white border-deep-moss font-bold shadow-sm' : 'bg-paper-white text-olive-char border-warm-mist hover:bg-cream-paper' }}">
            Semua Fakultas ({{ $totalMahasiswa }})
        </a>
        @foreach($fakultasList as $fak)
            @php
                $isActive = $fakultasId === $fak->id;
            @endphp
            <a href="{{ route('admin.mahasiswa.index', array_filter(['fakultas_id' => $fak->id, 'search' => $search])) }}"
               class="px-3 py-1.5 rounded-[4px] border font-mono transition whitespace-nowrap {{ $isActive ? 'bg-signal-blue text-paper-white border-signal-blue font-bold shadow-sm' : 'bg-paper-white text-olive-char border-warm-mist hover:bg-cream-paper' }}">
                <span class="font-bold">{{ $fak->kode }}</span> &bull; {{ $fak->nama }}
            </a>
        @endforeach
    </div>

    {{-- Filter & Search Form --}}
    <div class="bg-paper-white border border-warm-mist rounded-[4px] p-3"
         x-data="{
             selectedFakultas: '{{ $fakultasId ?? '' }}',
             selectedProdi: '{{ $prodiId ?? '' }}',
             onFakultasChange(newFakId) {
                 this.selectedFakultas = newFakId;
                 if (this.selectedFakultas && this.selectedProdi) {
                     const selectedOpt = this.$refs.prodiSelect ? this.$refs.prodiSelect.querySelector(`option[value='${this.selectedProdi}']`) : null;
                     if (selectedOpt && selectedOpt.dataset.fakultasId && selectedOpt.dataset.fakultasId !== this.selectedFakultas) {
                         this.selectedProdi = '';
                     }
                 }
             }
         }">
        <form method="GET" action="{{ route('admin.mahasiswa.index') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-2 text-xs">
            {{-- Filter Fakultas --}}
            <div class="sm:col-span-3">
                <label class="block text-[10px] font-bold text-olive-char uppercase tracking-wider mb-1">
                    Fakultas
                </label>
                <select name="fakultas_id"
                        x-model="selectedFakultas"
                        @change="onFakultasChange($event.target.value); $el.form.submit()"
                        class="posthog-select text-xs">
                    <option value="">-- Semua Fakultas --</option>
                    @foreach($fakultasList as $fak)
                        <option value="{{ $fak->id }}" @selected($fakultasId == $fak->id)>
                            {{ $fak->kode }} - {{ $fak->nama }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Filter Prodi --}}
            <div class="sm:col-span-4">
                <div class="flex items-center justify-between mb-1">
                    <label class="block text-[10px] font-bold text-olive-char uppercase tracking-wider">
                        Program Studi
                    </label>
                    <span x-show="selectedFakultas" class="text-[10px] font-mono text-signal-blue font-semibold" style="display: none;">
                        &bull; Hanya Fakultas Terpilih
                    </span>
                </div>
                <select name="prodi_id"
                        x-ref="prodiSelect"
                        x-model="selectedProdi"
                        @change="$el.form.submit()"
                        class="posthog-select text-xs">
                    <option value="">-- Semua Program Studi --</option>
                    @foreach($fakultasList as $fak)
                        @if($fak->programStudi->isNotEmpty())
                            @php
                                $isFakDisabled = !empty($fakultasId) && (string)$fakultasId !== (string)$fak->id;
                            @endphp
                            <optgroup label="{{ $fak->nama }} ({{ $fak->kode }})"
                                      :disabled="Boolean(selectedFakultas && selectedFakultas != '{{ $fak->id }}')"
                                      @disabled($isFakDisabled)>
                                @foreach($fak->programStudi as $prodi)
                                    @php
                                        $isProdiDisabled = !empty($fakultasId) && (string)$fakultasId !== (string)$fak->id;
                                    @endphp
                                    <option value="{{ $prodi->id }}"
                                            data-fakultas-id="{{ $fak->id }}"
                                            :disabled="Boolean(selectedFakultas && selectedFakultas != '{{ $fak->id }}')"
                                            @disabled($isProdiDisabled)
                                            @selected($prodiId == $prodi->id)>
                                        {{ $prodi->nama_prodi }} ({{ $prodi->kode_prodi }})
                                        @if($isProdiDisabled) [Nonaktif - Beda Fakultas] @endif
                                    </option>
                                @endforeach
                            </optgroup>
                        @endif
                    @endforeach
                    @php
                        $unassignedProdis = $prodisList->filter(fn ($p) => empty($p->fakultas_id));
                    @endphp
                    @if($unassignedProdis->isNotEmpty())
                        <optgroup label="Program Studi Tanpa Fakultas"
                                  :disabled="Boolean(selectedFakultas)"
                                  @disabled(!empty($fakultasId))>
                            @foreach($unassignedProdis as $prodi)
                                <option value="{{ $prodi->id }}"
                                        data-fakultas-id=""
                                        :disabled="Boolean(selectedFakultas)"
                                        @disabled(!empty($fakultasId))
                                        @selected($prodiId == $prodi->id)>
                                    {{ $prodi->nama_prodi }} ({{ $prodi->kode_prodi }})
                                    @if(!empty($fakultasId)) [Nonaktif - Tanpa Fakultas] @endif
                                </option>
                            @endforeach
                        </optgroup>
                    @endif
                </select>
            </div>

            {{-- Search Input --}}
            <div class="sm:col-span-4">
                <label class="block text-[10px] font-bold text-olive-char uppercase tracking-wider mb-1">
                    Pencarian Mahasiswa
                </label>
                <div class="relative">
                    <input type="text" name="search" value="{{ $search }}"
                           placeholder="Cari NIM, Nama, atau Email..."
                           class="posthog-input text-xs pl-8">
                    <svg class="w-3.5 h-3.5 text-sage-gray absolute left-2.5 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="sm:col-span-1 flex flex-col justify-end">
                <div class="flex items-center gap-1">
                    <button type="submit" class="btn-amber w-full justify-center text-xs py-1.5" title="Terapkan Filter / Cari">
                        Cari
                    </button>
                    @if($fakultasId || $prodiId || $search)
                        <a href="{{ route('admin.mahasiswa.index') }}" class="btn-mist px-2 py-1.5 text-xs text-flame-orange hover:bg-rose-50" title="Reset Filter">
                            &times;
                        </a>
                    @endif
                </div>
            </div>
        </form>
    </div>

    {{-- Mahasiswa Table --}}
    <div class="bg-paper-white border border-warm-mist rounded-[4px] overflow-hidden">
        <div class="px-4 py-2.5 border-b border-warm-mist bg-cream-paper flex items-center justify-between text-xs">
            <div class="flex items-center gap-2">
                <span class="font-bold text-deep-moss">Daftar Mahasiswa Terorganisir</span>
                @if($fakultasId)
                    @php $selectedFak = $fakultasList->firstWhere('id', $fakultasId); @endphp
                    @if($selectedFak)
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-mono font-bold bg-signal-blue/10 text-signal-blue border border-signal-blue/20">
                            Fakultas: {{ $selectedFak->kode }}
                        </span>
                    @endif
                @endif
                @if($prodiId)
                    @php $selectedProdi = $prodisList->firstWhere('id', $prodiId); @endphp
                    @if($selectedProdi)
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-mono font-bold bg-amber-glow/20 text-deep-moss border border-burnished-gold/30">
                            Prodi: {{ $selectedProdi->kode_prodi }}
                        </span>
                    @endif
                @endif
            </div>
            <span class="font-mono text-sage-gray">{{ $mahasiswas->count() }} mahasiswa ditampilkan</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-cream-paper border-b border-warm-mist font-semibold text-olive-char uppercase tracking-wider text-[11px]">
                    <tr>
                        <th class="px-4 py-2.5">NIM & Mahasiswa</th>
                        <th class="px-4 py-2.5">Kategori Fakultas</th>
                        <th class="px-4 py-2.5">Program Studi</th>
                        <th class="px-4 py-2.5 text-center">Angkatan</th>
                        <th class="px-4 py-2.5">Dosen Wali</th>
                        <th class="px-4 py-2.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-warm-mist">
                    @forelse($mahasiswas as $mhs)
                        @php
                            $fakultas = $mhs->programStudi?->fakultas;
                        @endphp
                        <tr class="hover:bg-cream-paper transition">
                            {{-- NIM & Nama --}}
                            <td class="px-4 py-3">
                                <div class="font-bold text-deep-moss">{{ $mhs->nama }}</div>
                                <div class="flex items-center gap-1.5 mt-0.5">
                                    <span class="font-mono font-bold text-deep-moss text-[11px]">{{ $mhs->nim }}</span>
                                    <span class="text-sage-gray">&bull;</span>
                                    <span class="font-mono text-sage-gray text-[10px]">{{ $mhs->user?->email ?? '-' }}</span>
                                </div>
                            </td>

                            {{-- Fakultas --}}
                            <td class="px-4 py-3">
                                @if($fakultas)
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[11px] font-semibold border {{ $mhs->fakultas_badge_color }}">
                                        <span class="font-mono font-bold uppercase">{{ $fakultas->kode }}</span>
                                        <span>&bull;</span>
                                        <span class="truncate max-w-[140px]">{{ $fakultas->nama }}</span>
                                    </span>
                                @else
                                    <span class="text-[11px] text-sage-gray italic">Tanpa Fakultas</span>
                                @endif
                            </td>

                            {{-- Program Studi --}}
                            <td class="px-4 py-3">
                                <span class="tag-pill tag-pill-stone text-[11px] font-medium">
                                    {{ $mhs->prodi->nama_prodi ?? '-' }}
                                    @if($mhs->prodi?->kode_prodi)
                                        <span class="font-mono text-[10px] text-olive-char font-bold">({{ $mhs->prodi->kode_prodi }})</span>
                                    @endif
                                </span>
                            </td>

                            {{-- Angkatan --}}
                            <td class="px-4 py-3 text-center">
                                <span class="inline-flex items-center px-2 py-0.5 rounded font-mono font-bold text-xs bg-soft-linen text-deep-moss border border-warm-mist">
                                    {{ $mhs->angkatan }}
                                </span>
                            </td>

                            {{-- Dosen Wali --}}
                            <td class="px-4 py-3 text-olive-char">
                                @if($mhs->dosenWali)
                                    <div class="font-medium text-deep-moss text-[11px]">{{ $mhs->dosenWali->nama }}</div>
                                    <div class="text-[10px] font-mono text-sage-gray">NIDN: {{ $mhs->dosenWali->nidn }}</div>
                                @else
                                    <span class="text-sage-gray italic text-[11px]">Belum ditentukan</span>
                                @endif
                            </td>

                            {{-- Aksi --}}
                            <td class="px-4 py-3 text-right">
                                <div class="inline-flex items-center gap-1.5">
                                    <a href="{{ route('admin.mahasiswa.edit', $mhs) }}" class="btn-mist">
                                        Edit
                                    </a>
                                    <form action="{{ route('admin.mahasiswa.destroy', $mhs) }}" method="POST" class="inline" onsubmit="return confirm('Hapus data mahasiswa ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-danger-mist">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-xs text-sage-gray">
                                <div class="max-w-xs mx-auto">
                                    <p class="font-medium text-deep-moss mb-1">Tidak ada data mahasiswa yang sesuai.</p>
                                    <p class="text-[11px]">Silakan ubah kata kunci pencarian atau reset filter fakultas/prodi.</p>
                                    @if($fakultasId || $prodiId || $search)
                                        <a href="{{ route('admin.mahasiswa.index') }}" class="btn-mist inline-block mt-3 text-xs">
                                            Reset Filter
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
