<div class="flex flex-col w-full">
    <!-- Top Ambient Banner & Breadcrumbs -->
    <section class="w-full bg-surface-white px-4 md:px-8 pt-6 pb-8 shadow-xs border-b border-border-subtle">
        <div class="max-w-7xl mx-auto flex flex-col gap-4">
            <!-- Breadcrumb -->
            <nav aria-label="Breadcrumb" class="flex items-center gap-1.5 text-xs text-text-muted">
                <a href="{{ route('home') }}" wire:navigate class="hover:text-primary transition-colors flex items-center gap-1">
                    <span class="material-symbols-outlined text-[16px]">home</span>
                    Beranda
                </a>
                <span class="material-symbols-outlined text-[14px]">chevron_right</span>
                <span class="text-primary font-bold">Jadwal</span>
            </nav>

            <!-- Page Title & Operational Status Headline -->
            <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-6">
                <div class="flex flex-col gap-2 max-w-3xl">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-status-tersedia-bg text-secondary w-fit text-xs font-bold">
                        <span class="w-2 h-2 rounded-full bg-status-tersedia animate-pulse"></span>
                        Sistem Pemantauan Terpadu 07.30 - 16.00 WIB
                    </div>
                    <h1 class="text-2xl sm:text-3xl md:text-4xl font-extrabold text-primary tracking-tight">
                        Jadwal Ketersediaan Lab & Simulator SPP
                    </h1>
                    <p class="text-xs sm:text-sm text-text-muted leading-relaxed">
                        Pantau slot pemakaian 8 laboratorium & simulator SPP STIP Jakarta secara real-time. Informasi terverifikasi tanpa memuat data pribadi pemohon untuk kepatuhan tata kelola maritim.
                    </p>
                </div>

                <!-- Quick Summary Mini-Metric Chips -->
                <div class="flex items-center gap-3 overflow-x-auto pb-1 lg:pb-0">
                    <div class="px-4 py-3 bg-canvas-bg rounded-2xl flex items-center gap-3 shrink-0 border border-border-subtle">
                        <div class="w-9 h-9 rounded-xl bg-status-tersedia-bg flex items-center justify-center text-status-tersedia">
                            <span class="material-symbols-outlined text-[20px]">check_circle</span>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-[10px] text-text-muted uppercase font-bold tracking-wider">Kesiapan Lab</span>
                            <span class="font-bold text-sm text-primary">{{ $availableLabEstimate }} Lab Siap Operasi</span>
                        </div>
                    </div>

                    <div class="px-4 py-3 bg-canvas-bg rounded-2xl flex items-center gap-3 shrink-0 border border-border-subtle">
                        <div class="w-9 h-9 rounded-xl bg-status-inoperative-bg flex items-center justify-center text-status-inoperative">
                            <span class="material-symbols-outlined text-[20px]">build</span>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-[10px] text-text-muted uppercase font-bold tracking-wider">Blackout / Perawatan</span>
                            <span class="font-bold text-sm text-primary">{{ $activeBlackoutCount }} Fasilitas</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Interactive Control Deck & Filters -->
    <section class="w-full px-4 md:px-8 py-4 bg-canvas-bg sticky top-20 z-30 backdrop-blur-md bg-canvas-bg/95 border-b border-border-subtle">
        <div class="max-w-7xl mx-auto flex flex-col gap-3">
            <div class="bg-surface-white p-4 rounded-2xl shadow-sm border border-border-subtle flex flex-col lg:flex-row items-center justify-between gap-4">
                <!-- Date Navigator -->
                <div class="flex flex-wrap items-center gap-3 w-full lg:w-auto">
                    <div class="inline-flex items-center bg-canvas-bg rounded-xl p-1 border border-border-subtle">
                        <button type="button" wire:click="prevWeek"
                                class="w-9 h-9 rounded-lg flex items-center justify-center text-primary hover:bg-surface-white transition-colors"
                                title="Minggu Sebelumnya">
                            <span class="material-symbols-outlined text-[20px]">chevron_left</span>
                        </button>
                        <button type="button" wire:click="currentWeek"
                                class="px-3 h-9 rounded-lg text-xs font-bold text-primary hover:bg-surface-white transition-colors">
                            Minggu Ini
                        </button>
                        <button type="button" wire:click="nextWeek"
                                class="w-9 h-9 rounded-lg flex items-center justify-center text-primary hover:bg-surface-white transition-colors"
                                title="Minggu Berikutnya">
                            <span class="material-symbols-outlined text-[20px]">chevron_right</span>
                        </button>
                    </div>

                    <div class="flex items-center gap-2 px-3.5 py-2 bg-surface-container-low rounded-xl text-primary border border-border-subtle">
                        <span class="material-symbols-outlined text-secondary text-[20px]">calendar_month</span>
                        <span class="font-bold text-xs sm:text-sm tracking-tight text-primary">{{ $weekRangeLabel }}</span>
                    </div>
                </div>

                <!-- Jurusan & Lab Filters -->
                <div class="flex flex-wrap items-center gap-2 w-full lg:w-auto justify-between lg:justify-end">
                    <div class="flex items-center gap-1 p-1 bg-canvas-bg rounded-xl border border-border-subtle">
                        <button type="button" wire:click="$set('selectedDepartment', 'all')"
                                class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all {{ $selectedDepartment === 'all' ? 'bg-primary text-white shadow-xs' : 'text-text-muted hover:text-primary' }}">
                            Semua
                        </button>
                        <button type="button" wire:click="$set('selectedDepartment', 'teknika')"
                                class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all {{ $selectedDepartment === 'teknika' ? 'bg-primary text-white shadow-xs' : 'text-text-muted hover:text-primary' }}">
                            Teknika
                        </button>
                        <button type="button" wire:click="$set('selectedDepartment', 'nautika')"
                                class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all {{ $selectedDepartment === 'nautika' ? 'bg-primary text-white shadow-xs' : 'text-text-muted hover:text-primary' }}">
                            Nautika
                        </button>
                        <button type="button" wire:click="$set('selectedDepartment', 'kalk')"
                                class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all {{ $selectedDepartment === 'kalk' ? 'bg-primary text-white shadow-xs' : 'text-text-muted hover:text-primary' }}">
                            KALK
                        </button>
                    </div>

                    <!-- Room Selector -->
                    <select wire:model.live="selectedRoomCode"
                            class="h-10 px-3 pr-8 rounded-xl bg-canvas-bg text-primary text-xs font-bold border border-border-subtle focus:outline-none focus:border-secondary cursor-pointer">
                        <option value="all">Pilih Semua Lab</option>
                        @foreach (['CHL', 'EEL', 'EWS', 'MEL', 'CBT', 'ERCS', 'LTL', 'ACSL'] as $rCode)
                            <option value="{{ $rCode }}">{{ $rCode }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </section>

    <!-- Matrix Schedule Grid (5 Columns: Senin - Jumat) -->
    <main class="max-w-7xl mx-auto px-4 md:px-8 py-8 w-full">
        <!-- Loading State -->
        <div wire:loading class="w-full text-center py-6">
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-surface-white shadow-md text-sm text-secondary font-semibold">
                <span class="material-symbols-outlined animate-spin text-[20px]">progress_activity</span>
                <span>Memuat kalender jadwal...</span>
            </div>
        </div>

        <div wire:loading.remove class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
            @foreach ($days as $day)
                @php
                    $dayData = $scheduleMatrix[$day['date']] ?? ['bookings' => collect(), 'blackouts' => collect()];
                    $dayBookings = $dayData['bookings'];
                    $dayBlackouts = $dayData['blackouts'];
                @endphp

                <div wire:key="col-{{ $day['date'] }}"
                     class="bg-surface-white rounded-2xl border {{ $day['is_today'] ? 'border-secondary shadow-md ring-1 ring-secondary/30' : 'border-border-subtle' }} p-4 flex flex-col gap-3 min-h-[460px]">
                    <!-- Column Header -->
                    <div class="pb-3 border-b border-border-subtle flex items-center justify-between">
                        <div class="flex flex-col">
                            <span class="font-extrabold text-sm text-primary uppercase">{{ $day['day_name'] }}</span>
                            <span class="text-xs text-text-muted">{{ $day['formatted'] }}</span>
                        </div>
                        @if ($day['is_today'])
                            <span class="px-2 py-0.5 rounded-full bg-status-tersedia-bg text-secondary text-[10px] font-bold uppercase tracking-wider">
                                Hari Ini
                            </span>
                        @endif
                    </div>

                    <!-- Column Content: Blackouts & Sesi -->
                    <div class="flex flex-col gap-2.5 flex-grow">
                        <!-- Blackout Alerts -->
                        @foreach ($dayBlackouts as $bo)
                            <div class="p-3 rounded-xl bg-status-inoperative-bg text-status-inoperative border border-status-inoperative/30 flex flex-col gap-1 shadow-2xs">
                                <div class="flex items-center justify-between">
                                    <span class="font-bold text-xs uppercase flex items-center gap-1">
                                        <span class="material-symbols-outlined text-[14px]">warning</span>
                                        {{ $bo->room?->code ?? 'Semua Lab' }}
                                    </span>
                                    <span class="text-[9px] uppercase font-bold bg-status-inoperative text-white px-1.5 py-0.2 rounded">
                                        Perawatan
                                    </span>
                                </div>
                                <span class="text-[11px] leading-tight font-medium">{{ $bo->reason }}</span>
                            </div>
                        @endforeach

                        <!-- Booking Sessions -->
                        @forelse ($dayBookings as $bk)
                            @php
                                $startTime = \Carbon\Carbon::parse($bk->start_at)->format('H.i');
                                $endTime = \Carbon\Carbon::parse($bk->end_at)->format('H.i');
                            @endphp
                            <div wire:click="openSession({{ $bk->id }})"
                                 class="p-3 rounded-xl bg-status-terisi-bg hover:bg-surface-container cursor-pointer transition-all border border-border-subtle flex flex-col gap-2 group shadow-2xs">
                                <div class="flex items-center justify-between">
                                    <span class="px-2 py-0.5 rounded bg-primary text-white font-bold text-[11px] tracking-wider">
                                        {{ $bk->room?->code }}
                                    </span>
                                    <span class="text-[10px] font-bold text-secondary flex items-center gap-1">
                                        <span class="material-symbols-outlined text-[12px]">schedule</span>
                                        {{ $startTime }} - {{ $endTime }}
                                    </span>
                                </div>

                                <div class="flex flex-col">
                                    <span class="font-bold text-xs text-primary group-hover:text-secondary transition-colors line-clamp-2">
                                        {{ $bk->subject?->name ?? $bk->purpose }}
                                    </span>
                                    <span class="text-[11px] text-text-muted mt-0.5">
                                        Kelas: {{ $bk->class_group }} ({{ $bk->participant_count }} Taruna)
                                    </span>
                                </div>

                                <div class="pt-1.5 border-t border-border-subtle/60 flex items-center justify-between text-[10px]">
                                    <span class="text-status-tersedia font-bold uppercase tracking-wider flex items-center gap-1">
                                        <span class="w-1.5 h-1.5 rounded-full bg-status-tersedia"></span>
                                        Terverifikasi
                                    </span>
                                    <span class="text-secondary font-bold group-hover:underline">Detail →</span>
                                </div>
                            </div>
                        @empty
                            @if ($dayBlackouts->isEmpty())
                                <div class="py-8 text-center flex flex-col items-center justify-center gap-2 text-text-muted/70 flex-grow">
                                    <span class="material-symbols-outlined text-[28px]">event_available</span>
                                    <span class="text-xs">Slot Praktikum Kosong</span>
                                    <span class="text-[10px] text-status-tersedia font-bold">Tersedia untuk Booking</span>
                                </div>
                            @endif
                        @endforelse
                    </div>

                    <!-- Bottom Daily Summary -->
                    <div class="pt-2 border-t border-border-subtle text-[11px] text-text-muted flex items-center justify-between">
                        <span>{{ $dayBookings->count() }} Sesi Terjadwal</span>
                        <a href="{{ route('lab.catalog') }}" wire:navigate class="text-secondary font-bold hover:underline">
                            + Booking
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </main>

    <!-- Quick View Session Modal Dialog -->
    @if ($showModal && $selectedSession)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-xs p-4"
             x-transition.opacity>
            <div class="bg-surface-white rounded-3xl max-w-lg w-full p-6 shadow-2xl border border-border-subtle flex flex-col gap-4 relative animate-in fade-in zoom-in-95 duration-200">
                <!-- Close Button -->
                <button type="button" wire:click="closeModal"
                        class="absolute top-4 right-4 w-8 h-8 rounded-full bg-canvas-bg hover:bg-surface-container flex items-center justify-center text-primary transition-colors">
                    <span class="material-symbols-outlined text-[18px]">close</span>
                </button>

                <div class="flex items-center gap-2">
                    <span class="px-2.5 py-1 rounded-lg bg-primary text-white font-bold text-xs tracking-wider">
                        {{ $selectedSession['room_code'] }}
                    </span>
                    <span class="text-xs text-text-muted">No. Booking: {{ $selectedSession['booking_number'] }}</span>
                </div>

                <div class="flex flex-col gap-1">
                    <h3 class="text-lg font-bold text-primary">{{ $selectedSession['room_name'] }}</h3>
                    <p class="text-xs text-text-muted">{{ $selectedSession['location'] }}</p>
                </div>

                <div class="p-4 rounded-2xl bg-canvas-bg border border-border-subtle flex flex-col gap-2.5 text-xs">
                    <div class="flex items-center justify-between">
                        <span class="text-text-muted">Mata Kuliah:</span>
                        <span class="font-bold text-primary">{{ $selectedSession['subject_name'] }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-text-muted">Grup / Angkatan:</span>
                        <span class="font-bold text-primary">{{ $selectedSession['class_group'] }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-text-muted">Peserta Praktikum:</span>
                        <span class="font-bold text-primary">{{ $selectedSession['participant_count'] }} Taruna</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-text-muted">Waktu Pelaksanaan:</span>
                        <span class="font-bold text-secondary">{{ $selectedSession['start_at'] }} - {{ $selectedSession['end_at'] }}</span>
                    </div>
                    <div class="flex flex-col gap-0.5 pt-2 border-t border-border-subtle">
                        <span class="text-text-muted">Tujuan Sesi:</span>
                        <span class="font-medium text-primary">{{ $selectedSession['purpose'] }}</span>
                    </div>
                </div>

                <div class="p-3 rounded-xl bg-status-tersedia-bg text-secondary text-xs flex items-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">verified</span>
                    <span>Telah diverifikasi Petugas SPP & disetujui Kepala Unit Sarana Praktik Pelaut.</span>
                </div>

                <div class="flex items-center justify-end gap-2 pt-2">
                    <button type="button" wire:click="closeModal"
                            class="px-4 py-2.5 rounded-xl bg-canvas-bg hover:bg-surface-container text-xs font-bold text-primary transition-colors">
                        Tutup
                    </button>
                    <a href="{{ route('lab.detail', $selectedSession['room_code']) }}" wire:navigate
                       class="px-4 py-2.5 rounded-xl bg-primary hover:bg-navy-light text-white text-xs font-bold transition-colors">
                        Lihat Fasilitas Lab →
                    </a>
                </div>
            </div>
        </div>
    @endif
</div>
