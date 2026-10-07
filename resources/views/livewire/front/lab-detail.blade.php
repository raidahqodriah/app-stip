@php
    $sampleImages = [
        'CHL' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuClflNteZGS9CTFPvPREGXJ7F38dmVo3nJytm9VqV4y5J5gpmNcwwzP8nEAZNmVDCeFjTl60-JcH1uY5HakrLEWrRzzaktGbiqCmMWJ-vyOnqwtsF128yu2UxeX81YBVo5PwgELHBJ4EXQPOisG6kPvo6VyYI8vcBUgOoIxWJxH8GNROMGNY9JNwMXm-wveqf9JKlnhEXOpG70QcUet-n3ejDxeCBXLSKX9Vm9mHXp9',
        'ERCS' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuAmfDjv-x0IYGtM5HMWeRME9x101-bw19rs22ExYRb0h_kPDC-v20h1eJiPj1Jx1_AKO0AONyuye2okLb-08psOYyiLrNO4WNgCcN-1qGOCJr_zPpZ1ACgxh_8ibVTIS1-bP42-5CXXYxEr7FlZ3ba6SG_o_fOHrB2q6OuWfhfDf3UY9f81YY95vJ3aTjlZTLpgnyplO3lDHHFaHyJC1fB4gsOPvVf__uzNRS1hGcAV',
        'MEL' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuDFXW_lX5gZf9eXg17iT_l71zZ36G75j47-V_j02T3v18eF3C9M2rB-C8Y-E_1_T1P2nF1jA_tX',
        'EEL' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuGfT9H2kM_10N1aT8L4F1cW9E-A2_R0_V3D-L_9X7j_K4L1aM2',
        'CBT' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuHkL12_X8fD9T-N1aR3M9C_0B7F4L2aJ1K9E-N2',
        'EWS' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuE8F2_T9aK1L3M7R0D_V1bN9C_2X3f-G4L1',
        'LTL' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuK9M2_D1aT8L4F1cW9E-A2_R0_V3D-L_9X7',
        'ACSL' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuL4F1cW9E-A2_R0_V3D-L_9X7j_K4L1aM2_E8',
    ];
    $imgUrl = $room->photo_path ? asset('storage/' . $room->photo_path) : ($sampleImages[$room->code] ?? $sampleImages['ERCS']);
@endphp

<div class="flex flex-col w-full">
    <!-- Breadcrumb -->
    <div class="w-full bg-surface-container-low py-4 border-b border-border-subtle">
        <div class="max-w-7xl mx-auto px-4 md:px-8">
            <nav class="flex items-center gap-1.5 text-xs text-text-muted">
                <a href="{{ route('home') }}" wire:navigate class="hover:text-primary transition-colors flex items-center gap-1">
                    <span class="material-symbols-outlined text-[16px]">home</span>
                    Beranda
                </a>
                <span class="material-symbols-outlined text-[14px]">chevron_right</span>
                <a href="{{ route('lab.catalog') }}" wire:navigate class="hover:text-primary transition-colors">
                    Katalog Lab
                </a>
                <span class="material-symbols-outlined text-[14px]">chevron_right</span>
                <span class="text-primary font-bold">{{ $room->code }}</span>
            </nav>
        </div>
    </div>

    <!-- Main Detail Content -->
    <section class="w-full bg-surface-white pb-16 pt-8">
        <div class="max-w-7xl mx-auto px-4 md:px-8 flex flex-col gap-8">
            <!-- Header Badges & Actions -->
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div class="flex flex-wrap items-center gap-2">
                    <span class="bg-primary text-white font-bold text-xs px-3 py-1.5 rounded-lg tracking-wider">
                        {{ $room->code }}
                    </span>
                    <span class="bg-surface-container text-primary text-xs font-bold px-3 py-1.5 rounded-lg">
                        Program Studi {{ ucfirst($room->lab_category) }}
                    </span>
                    @if ($hasBlackout)
                        <span class="bg-status-inoperative-bg text-status-inoperative text-xs font-bold px-3 py-1.5 rounded-lg flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-status-inoperative"></span>
                            Dalam Pemeliharaan Terjadwal
                        </span>
                    @elseif ($availableSlotsCount > 0)
                        <span class="bg-status-tersedia-bg text-secondary text-xs font-bold px-3 py-1.5 rounded-lg flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-status-tersedia animate-pulse"></span>
                            {{ $availableSlotsCount }} Slot Tersedia Hari Ini
                        </span>
                    @else
                        <span class="bg-status-terisi-bg text-status-terisi text-xs font-bold px-3 py-1.5 rounded-lg flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-status-terisi"></span>
                            Terisi Penuh Hari Ini
                        </span>
                    @endif
                </div>

                <div class="flex items-center gap-2" x-data="{ copied: false }">
                    <button type="button"
                            @click="navigator.clipboard.writeText(window.location.href); copied = true; setTimeout(() => copied = false, 2500)"
                            class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl bg-canvas-bg hover:bg-surface-container text-xs font-bold text-primary transition-colors border border-border-subtle">
                        <span class="material-symbols-outlined text-[18px]">share</span>
                        <span x-text="copied ? 'Tautan Disalin!' : 'Bagikan'">Bagikan</span>
                    </button>
                    <a href="{{ route('lab.schedule', ['room' => $room->code]) }}" wire:navigate
                       class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl bg-canvas-bg hover:bg-surface-container text-xs font-bold text-primary transition-colors border border-border-subtle">
                        <span class="material-symbols-outlined text-[18px]">calendar_month</span>
                        <span>Jadwal Mingguan</span>
                    </a>
                </div>
            </div>

            <!-- Title & Subtitle -->
            <div class="flex flex-col gap-2">
                <h1 class="text-2xl sm:text-3xl md:text-4xl font-extrabold text-primary tracking-tight">
                    {{ $room->name }} ({{ $room->code }})
                </h1>
                <p class="text-sm md:text-base text-text-muted max-w-4xl leading-relaxed">
                    {{ $room->description ?? 'Fasilitas simulator praktikum maritim berstandar internasional IMO STCW untuk perwira pelayaran niaga di STIP Jakarta.' }}
                </p>
            </div>

            <!-- 4 Quick Key Metrics -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 p-4 rounded-2xl bg-canvas-bg border border-border-subtle">
                <div class="flex items-center gap-3">
                    <div class="w-11 h-11 rounded-xl bg-surface-white flex items-center justify-center text-primary shadow-xs">
                        <span class="material-symbols-outlined text-[22px]">group</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-xs text-text-muted">Kapasitas Maksimal</span>
                        <span class="text-sm font-bold text-primary">{{ $room->capacity }} Taruna / Sesi</span>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <div class="w-11 h-11 rounded-xl bg-surface-white flex items-center justify-center text-secondary shadow-xs">
                        <span class="material-symbols-outlined text-[22px]">location_on</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-xs text-text-muted">Lokasi Fasilitas</span>
                        <span class="text-sm font-bold text-primary">{{ $room->location ?? 'Gedung SPP' }}</span>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <div class="w-11 h-11 rounded-xl bg-surface-white flex items-center justify-center text-primary shadow-xs">
                        <span class="material-symbols-outlined text-[22px]">schedule</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-xs text-text-muted">Jam Operasional</span>
                        <span class="text-sm font-bold text-primary">07.30 – 16.00 WIB</span>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <div class="w-11 h-11 rounded-xl bg-surface-white flex items-center justify-center text-on-tertiary-container shadow-xs">
                        <span class="material-symbols-outlined text-[22px]">verified</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-xs text-text-muted">Standar Maritime</span>
                        <span class="text-sm font-bold text-primary">IMO STCW 1978 / Manila</span>
                    </div>
                </div>
            </div>

            <!-- Image Preview & Facility Intro -->
            <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
                <div class="md:col-span-8 rounded-2xl overflow-hidden shadow-md group relative">
                    <img src="{{ $imgUrl }}"
                         alt="{{ $room->name }}"
                         class="w-full h-80 md:h-[420px] object-cover transition-transform duration-500 group-hover:scale-[1.02]"
                         onerror="this.src='{{ $sampleImages['ERCS'] }}'"/>
                    <div class="absolute bottom-0 inset-x-0 bg-gradient-to-t from-primary/95 via-primary/50 to-transparent p-6 flex items-end justify-between">
                        <div class="text-white">
                            <span class="text-xs font-bold uppercase tracking-wider text-secondary-fixed">Fasilitas Utama Simulator</span>
                            <p class="text-lg font-bold">{{ $room->name }}</p>
                        </div>
                        <span class="bg-primary/80 backdrop-blur-md px-3 py-1.5 rounded-lg text-white text-xs font-bold flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-[16px]">verified</span>
                            <span>Standard IMO</span>
                        </span>
                    </div>
                </div>

                <!-- Right Side: Fast Booking Info Card -->
                <div class="md:col-span-4 bg-surface-container-low rounded-2xl p-6 flex flex-col justify-between border border-border-subtle">
                    <div class="flex flex-col gap-4">
                        <div class="flex items-center justify-between pb-3 border-b border-border-subtle">
                            <span class="text-xs font-bold uppercase text-secondary tracking-wider">Status Ketersediaan</span>
                            <span class="text-xs text-text-muted">Hari Ini</span>
                        </div>

                        <div class="flex flex-col gap-2">
                            <div class="flex items-center justify-between text-xs">
                                <span class="text-text-muted">Total Slot Hari Ini:</span>
                                <span class="font-bold text-primary">17 Slot (30 mnt)</span>
                            </div>
                            <div class="flex items-center justify-between text-xs">
                                <span class="text-text-muted">Slot Kosong:</span>
                                <span class="font-bold text-status-tersedia">{{ $availableSlotsCount }} Slot</span>
                            </div>
                            <div class="flex items-center justify-between text-xs">
                                <span class="text-text-muted">Unit Pengelola:</span>
                                <span class="font-bold text-primary">{{ $room->unit?->name ?? 'Unit Sarana Praktik Pelaut' }}</span>
                            </div>
                            <div class="flex items-center justify-between text-xs">
                                <span class="text-text-muted">PIC Fasilitas:</span>
                                <span class="font-bold text-primary">{{ $room->picEmployee?->name ?? 'Petugas SPP STIP' }}</span>
                            </div>
                        </div>

                        <div class="p-3 rounded-xl bg-white border border-border-subtle text-xs text-text-muted flex items-start gap-2">
                            <span class="material-symbols-outlined text-secondary text-[18px] shrink-0">lock</span>
                            <span>Prinsip FCFS (First Come, First Served). Slot otomatis terkunci di database saat pengajuan disetujui.</span>
                        </div>
                    </div>

                    <!-- CTA Buttons -->
                    <div class="flex flex-col gap-2 pt-6">
                        <a href="/admin" target="_blank"
                           class="w-full text-center py-3 rounded-xl bg-primary hover:bg-navy-light text-white font-bold text-xs transition-colors shadow-md flex items-center justify-center gap-1.5">
                            <span class="material-symbols-outlined text-[18px]">badge</span>
                            <span>Booking via SSO Dosen</span>
                        </a>
                        <a href="/student" target="_blank"
                           class="w-full text-center py-3 rounded-xl bg-tertiary-fixed-dim hover:bg-gold-hover text-primary font-bold text-xs transition-colors shadow-sm flex items-center justify-center gap-1.5">
                            <span class="material-symbols-outlined text-[18px]">school</span>
                            <span>Booking via Portal Taruna</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Matriks Ketersediaan Slot Hari Ini -->
            <div class="w-full bg-surface-white rounded-2xl p-6 border border-border-subtle flex flex-col gap-4">
                <div class="flex items-center justify-between flex-wrap gap-2">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-secondary text-[22px]">view_timeline</span>
                        <h2 class="font-bold text-lg text-primary">Matriks Slot Ketersediaan Hari Ini (07.30 - 16.00 WIB)</h2>
                    </div>
                    <div class="flex items-center gap-3 text-xs">
                        <span class="inline-flex items-center gap-1.5">
                            <span class="w-2.5 h-2.5 rounded-full bg-status-tersedia"></span>
                            Tersedia
                        </span>
                        <span class="inline-flex items-center gap-1.5">
                            <span class="w-2.5 h-2.5 rounded-full bg-status-terisi"></span>
                            Terisi
                        </span>
                        <span class="inline-flex items-center gap-1.5">
                            <span class="w-2.5 h-2.5 rounded-full bg-status-inoperative"></span>
                            Pemeliharaan
                        </span>
                    </div>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-6 lg:grid-cols-8 gap-2 pt-2">
                    @foreach ($slots as $slot)
                        @php
                            $slotBg = match($slot['status']) {
                                'terisi' => 'bg-status-terisi text-white',
                                'maintenance' => 'bg-status-inoperative-bg text-status-inoperative border border-status-inoperative/30',
                                default => 'bg-status-tersedia-bg text-secondary border border-status-tersedia/20',
                            };
                        @endphp
                        <div class="p-2.5 rounded-xl text-center flex flex-col items-center justify-center gap-1 {{ $slotBg }}">
                            <span class="font-extrabold text-xs">{{ $slot['time_start'] }} - {{ $slot['time_end'] }}</span>
                            <span class="text-[10px] uppercase font-bold tracking-tight truncate w-full">{{ $slot['label'] }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Mata Kuliah Kurikulum & Standar IMO STCW -->
            <div class="w-full bg-surface-white rounded-2xl p-6 border border-border-subtle flex flex-col gap-4">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-secondary text-[22px]">menu_book</span>
                    <h2 class="font-bold text-lg text-primary">Mata Kuliah & Standar Kompetensi IMO STCW</h2>
                </div>
                <p class="text-xs text-text-muted">
                    Sesuai PRD §3.2 & §3.3, setiap peminjaman lab ini wajib dikaitkan dengan mata kuliah aktif dan minimal 1 kompetensi IMO Model Course.
                </p>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-2">
                    @forelse ($room->subjects as $subject)
                        <div class="p-4 rounded-xl bg-canvas-bg border border-border-subtle flex flex-col gap-2">
                            <div class="flex items-center justify-between">
                                <span class="font-bold text-sm text-primary">{{ $subject->name }}</span>
                                <span class="text-[11px] font-bold px-2 py-0.5 rounded bg-surface-white text-secondary border border-border-subtle">
                                    {{ $subject->code }}
                                </span>
                            </div>
                            <span class="text-xs text-text-muted">Semester: {{ $subject->semester }} • Bobot: {{ $subject->credits }} SKS</span>

                            <!-- Kompetensi IMO STCW -->
                            <div class="flex flex-wrap gap-1.5 pt-2 border-t border-border-subtle/60">
                                @forelse ($subject->competences as $comp)
                                    <span class="px-2 py-0.5 rounded bg-white text-[11px] text-primary border border-border-subtle">
                                        {{ $comp->imoModelCourse?->code ?? 'IMO' }} : {{ $comp->code }} ({{ Str::limit($comp->name, 25) }})
                                    </span>
                                @empty
                                    <span class="text-[11px] text-text-muted">Kompetensi IMO STCW Reg. I/12</span>
                                @endforelse
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full p-4 rounded-xl bg-canvas-bg text-center text-xs text-text-muted">
                            Mata kuliah kurikulum sedang dalam proses sinkronisasi dengan RPS semester berjalan.
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Tata Tertib & Standar Operasional Laboratorium -->
            <div class="w-full bg-surface-white rounded-2xl p-6 border border-border-subtle flex flex-col gap-4">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-secondary text-[22px]">health_and_safety</span>
                    <h2 class="font-bold text-lg text-primary">Tata Tertib & Keselamatan Praktikum di {{ $room->name }}</h2>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-xs text-text-muted leading-relaxed">
                    <div class="p-4 rounded-xl bg-canvas-bg flex flex-col gap-1.5 border border-border-subtle">
                        <span class="font-bold text-primary flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-[16px] text-secondary">check_circle</span>
                            Pakaian Dinas & APD
                        </span>
                        <p>Taruna wajib menggunakan Pakaian Dinas Harian (PDH) atau Wearpack praktikum lengkap dengan safety shoes sesuai standar keselamatan kemaritiman.</p>
                    </div>
                    <div class="p-4 rounded-xl bg-canvas-bg flex flex-col gap-1.5 border border-border-subtle">
                        <span class="font-bold text-primary flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-[16px] text-secondary">check_circle</span>
                            Check-in & Presensi
                        </span>
                        <p>Dosen atau penanggung jawab wajib melapor kepada Petugas SPP sebelum menyalakan konsol simulator dan mengisi lembar presensi taruna.</p>
                    </div>
                    <div class="p-4 rounded-xl bg-canvas-bg flex flex-col gap-1.5 border border-border-subtle">
                        <span class="font-bold text-primary flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-[16px] text-secondary">check_circle</span>
                            Pencatatan Realisasi
                        </span>
                        <p>Setelah sesi selesai, wajib dilakukan prosedur shutdown sesuai SOP, pencatatan bahan habis pakai, dan pemeriksaan kondisi alat sebelum check-out.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
