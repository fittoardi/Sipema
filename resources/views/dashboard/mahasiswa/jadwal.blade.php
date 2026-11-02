<x-layouts.dashboard title="Jadwal & Kalender Perkuliahan">
    <div class="space-y-6"
         x-data="{
             // Calendar state
             currentDate: new Date(),
             currentYear: new Date().getFullYear(),
             currentMonth: new Date().getMonth(), // 0-indexed
             selectedDate: '{{ $todayDate }}',
             selectedDayName: '{{ $todayHari }}',
             activeTabHari: 'Semua',

             // Schedule and event data from server
             recurringEvents: {{ $calendarEventsJson }},
             datedEvents: {{ $pertemuanEventsJson }},

             dayNamesIndo: ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'],
             monthNamesIndo: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],

             get monthName() {
                 return this.monthNamesIndo[this.currentMonth] + ' ' + this.currentYear;
             },

             prevMonth() {
                 if (this.currentMonth === 0) {
                     this.currentMonth = 11;
                     this.currentYear--;
                 } else {
                     this.currentMonth--;
                 }
             },

             nextMonth() {
                 if (this.currentMonth === 11) {
                     this.currentMonth = 0;
                     this.currentYear++;
                 } else {
                     this.currentMonth++;
                 }
             },

             goToday() {
                 const now = new Date();
                 this.currentYear = now.getFullYear();
                 this.currentMonth = now.getMonth();
                 this.selectDay(now.getFullYear(), now.getMonth(), now.getDate());
             },

             get calendarDays() {
                 const firstDayOfMonth = new Date(this.currentYear, this.currentMonth, 1).getDay(); // 0 is Sunday
                 const daysInMonth = new Date(this.currentYear, this.currentMonth + 1, 0).getDate();
                 const days = [];

                 // Empty slots for previous month
                 for (let i = 0; i < firstDayOfMonth; i++) {
                     days.push({ dayNumber: '', dateStr: '', isCurrentMonth: false, dayOfWeek: i });
                 }

                 // Days of current month
                 for (let d = 1; d <= daysInMonth; d++) {
                     const dateObj = new Date(this.currentYear, this.currentMonth, d);
                     const y = dateObj.getFullYear();
                     const m = String(dateObj.getMonth() + 1).padStart(2, '0');
                     const dayStr = String(d).padStart(2, '0');
                     const dateStr = `${y}-${m}-${dayStr}`;
                     const dayOfWeek = dateObj.getDay();

                     // Check if there are events on this day
                     const dayName = this.dayNamesIndo[dayOfWeek];
                     const hasRecurring = this.recurringEvents.some(e => e.day === dayName);
                     const hasDated = this.datedEvents.some(e => e.date === dateStr);
                     const hasPresensiOpen = this.recurringEvents.some(e => e.day === dayName && e.is_presensi_open) ||
                                             this.datedEvents.some(e => e.date === dateStr && e.is_presensi_open);

                     days.push({
                         dayNumber: d,
                         dateStr: dateStr,
                         dayName: dayName,
                         isCurrentMonth: true,
                         dayOfWeek: dayOfWeek,
                         hasEvents: hasRecurring || hasDated,
                         hasPresensiOpen: hasPresensiOpen,
                         isToday: dateStr === '{{ $todayDate }}',
                         isSelected: dateStr === this.selectedDate
                     });
                 }

                 return days;
             },

             selectDay(year, month, day) {
                 const dateObj = new Date(year, month, day);
                 const y = dateObj.getFullYear();
                 const m = String(dateObj.getMonth() + 1).padStart(2, '0');
                 const dayStr = String(day).padStart(2, '0');
                 this.selectedDate = `${y}-${m}-${dayStr}`;
                 this.selectedDayName = this.dayNamesIndo[dateObj.getDay()];
             },

             get selectedDateEvents() {
                 // Return classes occurring on this day of week
                 const recurring = this.recurringEvents.filter(e => e.day === this.selectedDayName);
                 const dated = this.datedEvents.filter(e => e.date === this.selectedDate);
                 return { recurring, dated };
             }
         }">

        {{-- Window Card Header --}}
        <div class="window-card overflow-hidden">
            <div class="window-titlebar flex items-center justify-between px-4 py-2 border-b border-warm-mist bg-soft-linen">
                <div class="flex items-center gap-2">
                    <span class="font-mono text-xs font-bold text-deep-moss">schedule_calendar.sys</span>
                    <span class="tag-pill tag-pill-blue text-[10px]">Mahasiswa Portal</span>
                </div>
                <div class="flex items-center gap-1.5">
                    <div class="window-control window-control-red"></div>
                    <div class="window-control window-control-yellow"></div>
                    <div class="window-control window-control-green"></div>
                </div>
            </div>

            <div class="p-5 bg-cream-paper flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <span class="tag-pill tag-pill-amber text-[10px] font-mono">Angkatan {{ $mahasiswa->angkatan }}</span>
                        <span class="text-xs text-olive-char font-mono">NIM: {{ $mahasiswa->nim }}</span>
                        @if($mahasiswa->programStudi?->fakultas)
                            <span class="tag-pill tag-pill-stone text-[10px] font-bold">{{ $mahasiswa->programStudi->fakultas->kode }}</span>
                        @endif
                    </div>
                    <h1 class="text-xl font-black tracking-tight text-deep-moss font-sans">
                        Jadwal Perkuliahan & Kalender Akademik
                    </h1>
                    <p class="text-xs text-olive-char mt-0.5 font-mono">
                        {{ $mahasiswa->nama }} &bull; {{ $mahasiswa->prodi->nama_prodi }}
                    </p>
                </div>

                <div class="flex items-center gap-2">
                    <a href="{{ route('mahasiswa.krs.index') }}" class="btn-amber text-xs inline-flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        KRS Online
                    </a>
                    <a href="{{ route('mahasiswa.dashboard') }}" class="btn-mist text-xs">
                        &larr; Dashboard
                    </a>
                </div>
            </div>
        </div>

        {{-- Metric Overview Cards --}}
        <div class="grid gap-3 grid-cols-2 sm:grid-cols-4">
            <div class="metric-card">
                <p class="text-[11px] font-bold text-olive-char uppercase tracking-wider font-mono">Total Kelas</p>
                <div class="mt-1 text-2xl font-black text-deep-moss font-mono">{{ $jadwals->count() }}</div>
                <div class="text-[10px] text-ash-green font-mono">Mata kuliah aktif</div>
            </div>

            <div class="metric-card">
                <p class="text-[11px] font-bold text-olive-char uppercase tracking-wider font-mono">Beban SKS</p>
                <div class="mt-1 text-2xl font-black text-deep-moss font-mono">{{ $totalSks }}</div>
                <div class="text-[10px] text-ash-green font-mono">SKS semester ini</div>
            </div>

            <div class="metric-card">
                <p class="text-[11px] font-bold text-olive-char uppercase tracking-wider font-mono">Kuliah Hari Ini</p>
                <div class="mt-1 text-2xl font-black {{ $jadwalHariIni->isNotEmpty() ? 'text-signal-blue' : 'text-deep-moss' }} font-mono">
                    {{ $jadwalHariIni->count() }} Sesi
                </div>
                <div class="text-[10px] text-ash-green font-mono">{{ $todayHari }} ({{ date('d M Y') }})</div>
            </div>

            <div class="metric-card">
                <p class="text-[11px] font-bold text-olive-char uppercase tracking-wider font-mono">Presensi Live</p>
                @php
                    $presensiBukaCount = $jadwals->filter(fn ($j) => $j->pertemuan_buka !== null)->count();
                @endphp
                <div class="mt-1 text-2xl font-black {{ $presensiBukaCount > 0 ? 'text-flame-orange animate-pulse' : 'text-deep-moss' }} font-mono">
                    {{ $presensiBukaCount > 0 ? $presensiBukaCount . ' Buka' : 'Tutup' }}
                </div>
                <div class="text-[10px] text-ash-green font-mono">Selfie & GPS Ready</div>
            </div>
        </div>

        {{-- Calendar & Agenda Interactive Section --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-5">
            {{-- Interactive Month Calendar (7 Cols) --}}
            <div class="lg:col-span-7 bg-paper-white border border-warm-mist rounded-[4px] p-4 shadow-2xs">
                <div class="flex items-center justify-between pb-3 border-b border-warm-mist">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-signal-blue"></span>
                        <h2 class="text-sm font-bold text-deep-moss" x-text="monthName"></h2>
                    </div>
                    <div class="flex items-center gap-1.5 text-xs font-mono">
                        <button type="button" @click="prevMonth()" class="btn-mist px-2.5 py-1 text-xs" title="Bulan Sebelumnya">
                            &larr;
                        </button>
                        <button type="button" @click="goToday()" class="btn-mist px-2.5 py-1 text-xs font-bold text-deep-moss" title="Hari Ini">
                            Hari Ini
                        </button>
                        <button type="button" @click="nextMonth()" class="btn-mist px-2.5 py-1 text-xs" title="Bulan Selanjutnya">
                            &rarr;
                        </button>
                    </div>
                </div>

                {{-- Day Headers --}}
                <div class="grid grid-cols-7 gap-1 text-center font-mono text-[11px] font-bold text-olive-char py-2 border-b border-warm-mist bg-cream-paper rounded-t-[4px] mt-3">
                    <span class="text-flame-orange">Min</span>
                    <span>Sen</span>
                    <span>Sel</span>
                    <span>Rab</span>
                    <span>Kam</span>
                    <span>Jum</span>
                    <span class="text-olive-char">Sab</span>
                </div>

                {{-- Calendar Days Grid --}}
                <div class="grid grid-cols-7 gap-1 mt-1 text-xs">
                    <template x-for="(cell, index) in calendarDays" :key="index">
                        <div class="h-14 p-1 border rounded-[3px] transition flex flex-col justify-between"
                             :class="{
                                 'bg-cream-paper/40 border-transparent text-sage-gray/40 pointer-events-none': !cell.isCurrentMonth,
                                 'bg-paper-white border-warm-mist hover:border-signal-blue cursor-pointer': cell.isCurrentMonth,
                                 'ring-2 ring-signal-blue border-signal-blue bg-blue-50/40': cell.isCurrentMonth && cell.dateStr === selectedDate,
                                 'border-amber-glow bg-amber-50/40': cell.isCurrentMonth && cell.isToday && cell.dateStr !== selectedDate
                             }"
                             @click="cell.isCurrentMonth && selectDay(currentYear, currentMonth, cell.dayNumber)">
                            <div class="flex items-center justify-between text-[11px] font-mono">
                                <span :class="{
                                    'font-bold text-signal-blue': cell.isCurrentMonth && cell.dateStr === selectedDate,
                                    'font-bold text-amber-700': cell.isCurrentMonth && cell.isToday && cell.dateStr !== selectedDate,
                                    'text-flame-orange': cell.dayOfWeek === 0 && cell.isCurrentMonth,
                                    'text-olive-char': cell.dayOfWeek !== 0 && cell.isCurrentMonth
                                }" x-text="cell.dayNumber"></span>

                                <template x-if="cell.isToday">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500" title="Hari Ini"></span>
                                </template>
                            </div>

                            <div class="flex flex-wrap gap-1 items-center justify-end">
                                <template x-if="cell.hasPresensiOpen">
                                    <span class="w-2 h-2 rounded-full bg-flame-orange animate-ping" title="Presensi Terbuka"></span>
                                </template>
                                <template x-if="cell.hasEvents">
                                    <span class="inline-block w-2 h-2 rounded-full bg-signal-blue" title="Ada Perkuliahan"></span>
                                </template>
                            </div>
                        </div>
                    </template>
                </div>

                {{-- Legend --}}
                <div class="mt-3 pt-3 border-t border-warm-mist flex flex-wrap items-center justify-between text-[11px] text-olive-char font-mono">
                    <div class="flex items-center gap-3">
                        <span class="flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-signal-blue"></span> Perkuliahan Terjadwal
                        </span>
                        <span class="flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-flame-orange"></span> Presensi Dibuka
                        </span>
                        <span class="flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-amber-500"></span> Hari Ini
                        </span>
                    </div>
                    <span class="text-sage-gray">Klik tanggal untuk cek rincian</span>
                </div>
            </div>

            {{-- Selected Date Agenda Panel (5 Cols) --}}
            <div class="lg:col-span-5 bg-paper-white border border-warm-mist rounded-[4px] p-4 flex flex-col justify-between shadow-2xs">
                <div>
                    <div class="flex items-center justify-between pb-3 border-b border-warm-mist">
                        <div>
                            <span class="text-[10px] font-bold text-olive-char uppercase tracking-wider font-mono">Agenda Kuliah</span>
                            <h2 class="text-sm font-bold text-deep-moss">
                                <span x-text="selectedDayName"></span>, <span x-text="selectedDate"></span>
                            </h2>
                        </div>
                        <template x-if="selectedDate === '{{ $todayDate }}'">
                            <span class="tag-pill tag-pill-amber font-mono text-[10px] font-bold">Hari Ini</span>
                        </template>
                    </div>

                    {{-- Agenda Cards for Selected Date --}}
                    <div class="mt-3 space-y-2.5 max-h-[380px] overflow-y-auto pr-1">
                        {{-- Recurring Classes for Selected Day --}}
                        <template x-if="selectedDateEvents.recurring.length === 0 && selectedDateEvents.dated.length === 0">
                            <div class="py-10 text-center text-sage-gray">
                                <svg class="w-9 h-9 mx-auto mb-2 text-warm-mist" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                <p class="text-xs font-bold text-olive-char">Tidak Ada Perkuliahan</p>
                                <p class="text-[11px] text-sage-gray mt-0.5">Tidak ada jadwal kelas rutin atau sesi pertemuan pada tanggal ini.</p>
                            </div>
                        </template>

                        <template x-for="item in selectedDateEvents.recurring" :key="item.kelas_id">
                            <div class="p-3 rounded-[4px] border border-warm-mist bg-cream-paper hover:bg-white transition space-y-2">
                                <div class="flex items-start justify-between gap-2">
                                    <div>
                                        <div class="flex items-center gap-1.5 mb-0.5">
                                            <span class="font-mono text-[10px] font-bold px-1.5 py-0.5 rounded bg-paper-white border border-warm-mist text-deep-moss" x-text="item.kode"></span>
                                            <span class="font-mono text-[10px] text-olive-char" x-text="'Kelas ' + item.nama_kelas"></span>
                                            <span class="tag-pill text-[9px]" :class="item.tipe === 'online' ? 'tag-pill-blue' : 'tag-pill-green'" x-text="item.tipe.toUpperCase()"></span>
                                        </div>
                                        <h3 class="text-xs font-bold text-deep-moss" x-text="item.mata_kuliah"></h3>
                                    </div>
                                    <div class="text-right flex-shrink-0">
                                        <div class="text-[11px] font-mono font-bold text-deep-moss" x-text="item.jam"></div>
                                        <div class="text-[10px] font-mono text-sage-gray" x-text="item.ruangan"></div>
                                    </div>
                                </div>

                                <div class="flex items-center justify-between pt-1 border-t border-warm-mist/60 text-[11px]">
                                    <div class="text-olive-char font-mono truncate" x-text="'Dosen: ' + item.dosen"></div>
                                    <template x-if="item.is_presensi_open && item.presensi_url">
                                        <a :href="item.presensi_url" class="btn-amber text-[10px] py-1 px-2.5 font-bold flex items-center gap-1">
                                            <span class="w-1.5 h-1.5 rounded-full bg-white animate-ping"></span>
                                            Absen Sekarang &rarr;
                                        </a>
                                    </template>
                                </div>
                            </div>
                        </template>

                        {{-- Specific Dated Pertemuan Events --}}
                        <template x-for="(meeting, idx) in selectedDateEvents.dated" :key="'dated-' + idx">
                            <div class="p-2.5 rounded-[4px] border border-signal-blue/30 bg-blue-50/40 text-xs space-y-1">
                                <div class="flex items-center justify-between">
                                    <span class="tag-pill tag-pill-blue text-[9px] font-mono" x-text="'Sesi Ke-' + meeting.pertemuan_ke"></span>
                                    <template x-if="meeting.is_presensi_open && meeting.presensi_url">
                                        <a :href="meeting.presensi_url" class="text-[10px] font-bold text-flame-orange hover:underline font-mono">
                                            Presensi Dibuka &rarr;
                                        </a>
                                    </template>
                                </div>
                                <div class="font-bold text-deep-moss text-xs" x-text="meeting.judul"></div>
                                <div class="text-[11px] text-olive-char font-mono" x-text="meeting.mata_kuliah + ' (' + meeting.kelas_nama + ')'"></div>
                                <template x-if="meeting.tautan_meeting">
                                    <a :href="meeting.tautan_meeting" target="_blank" class="inline-flex items-center gap-1 text-[11px] text-signal-blue hover:underline font-mono mt-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                        Buka Link Meeting (Zoom/GMeet)
                                    </a>
                                </template>
                            </div>
                        </template>
                    </div>
                </div>

                <div class="pt-3 border-t border-warm-mist mt-3 flex items-center justify-between text-[11px] font-mono text-olive-char">
                    <span>{{ $jadwals->count() }} Kelas Aktif</span>
                    <button type="button" @click="activeTabHari = 'Semua'" class="text-signal-blue hover:underline font-bold">
                        Lihat Jadwal Lengkap &darr;
                    </button>
                </div>
            </div>
        </div>

        {{-- Weekly Timetable Section (Full Grid) --}}
        <div class="bg-paper-white border border-warm-mist rounded-[4px] overflow-hidden shadow-2xs">
            <div class="px-4 py-3 border-b border-warm-mist bg-cream-paper flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 text-xs">
                <div>
                    <h2 class="font-bold text-deep-moss uppercase tracking-wider text-xs">Jadwal Perkuliahan Mingguan</h2>
                    <p class="text-[11px] text-olive-char">Daftar kelas rutin berdasarkan Kartu Rencana Studi (KRS) aktif Anda.</p>
                </div>

                {{-- Day Filter Tabs --}}
                <div class="flex items-center gap-1 overflow-x-auto text-xs">
                    @php
                        $daysFilter = ['Semua', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                    @endphp
                    @foreach($daysFilter as $day)
                        <button type="button"
                                @click="activeTabHari = '{{ $day }}'"
                                :class="activeTabHari === '{{ $day }}' ? 'bg-deep-moss text-paper-white font-bold' : 'bg-paper-white text-olive-char hover:bg-pale-stone'"
                                class="px-2.5 py-1 rounded-[3px] border border-warm-mist font-mono text-[11px] transition whitespace-nowrap">
                            {{ $day }}
                        </button>
                    @endforeach
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-soft-linen border-b border-warm-mist font-semibold text-olive-char uppercase tracking-wider text-[11px]">
                        <tr>
                            <th class="px-4 py-2.5">Hari & Jam</th>
                            <th class="px-4 py-2.5">Mata Kuliah</th>
                            <th class="px-3 py-2.5 text-center">SKS</th>
                            <th class="px-3 py-2.5 text-center">Kelas</th>
                            <th class="px-4 py-2.5">Ruangan / Tempat</th>
                            <th class="px-4 py-2.5">Dosen Pengampu</th>
                            <th class="px-4 py-2.5 text-right">Status / Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-warm-mist">
                        @forelse($jadwals as $j)
                            @php
                                $isToday = $j->hari === $todayHari;
                                $dayBadge = match($j->hari) {
                                    'Senin' => 'tag-pill-blue',
                                    'Selasa' => 'tag-pill-green',
                                    'Rabu' => 'tag-pill-amber',
                                    'Kamis' => 'tag-pill-orange',
                                    default => 'tag-pill-stone',
                                };
                            @endphp
                            <tr class="hover:bg-cream-paper transition"
                                x-show="activeTabHari === 'Semua' || activeTabHari === '{{ $j->hari }}'">
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <div class="flex items-center gap-1.5 mb-1">
                                        <span class="tag-pill {{ $dayBadge }} font-bold text-[10px]">
                                            {{ $j->hari }}
                                        </span>
                                        @if($isToday)
                                            <span class="tag-pill tag-pill-amber text-[9px] font-bold">Hari Ini</span>
                                        @endif
                                    </div>
                                    <div class="font-mono text-deep-moss font-semibold text-[11px]">
                                        {{ $j->jam_rentang }}
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="font-bold text-deep-moss text-xs">{{ $j->mataKuliah?->nama ?? '-' }}</div>
                                    <div class="text-[11px] font-mono text-sage-gray mt-0.5">
                                        Kode: {{ $j->mataKuliah?->kode ?? '-' }}
                                    </div>
                                </td>
                                <td class="px-3 py-3 text-center font-mono font-bold text-deep-moss">
                                    {{ $j->sks }}
                                </td>
                                <td class="px-3 py-3 text-center">
                                    <span class="font-mono font-bold text-xs text-deep-moss bg-cream-paper px-2 py-0.5 rounded border border-warm-mist">
                                        {{ $j->nama_kelas }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="font-mono text-deep-moss font-medium text-xs">{{ $j->ruangan }}</div>
                                    <div class="text-[10px] text-sage-gray font-mono mt-0.5">
                                        Tipe: <span class="capitalize">{{ $j->tipe_kelas }}</span> &bull; {{ $j->gedung }}
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="font-medium text-deep-moss text-xs">{{ $j->dosen_nama }}</div>
                                    <div class="text-[10px] font-mono text-sage-gray mt-0.5">NIDN: {{ $j->dosen_nidn }}</div>
                                </td>
                                <td class="px-4 py-3 text-right">
                                    @if($j->pertemuan_buka)
                                        <a href="{{ route('mahasiswa.presensi.show', $j->pertemuan_buka->id) }}"
                                           class="btn-amber text-xs py-1 px-2.5 inline-flex items-center gap-1 font-bold">
                                            <span class="w-1.5 h-1.5 rounded-full bg-white animate-ping"></span>
                                            Presensi Dibuka &rarr;
                                        </a>
                                    @else
                                        <span class="text-[11px] font-mono text-sage-gray bg-soft-linen px-2 py-1 rounded border border-warm-mist">
                                            Terjadwal Rutin
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-10 text-center text-sage-gray">
                                    <svg class="w-10 h-10 mx-auto mb-2 text-warm-mist" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    <p class="text-xs font-bold text-deep-moss">Belum ada jadwal kelas yang diambil.</p>
                                    <p class="text-[11px] text-olive-char mt-0.5">Silakan isi Kartu Rencana Studi (KRS) untuk memilih kelas perkuliahan semester ini.</p>
                                    <a href="{{ route('mahasiswa.krs.index') }}" class="btn-amber text-xs mt-3 inline-flex">
                                        Buka KRS Online
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-layouts.dashboard>

