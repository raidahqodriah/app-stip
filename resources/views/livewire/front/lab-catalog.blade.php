<div class="flex flex-col w-full">
    <!-- Breadcrumb & Top Masthead -->
    <section class="relative w-full bg-surface-white shadow-xs border-b border-border-subtle">
        <div class="max-w-7xl mx-auto px-4 md:px-8 py-8 md:py-12">
            <nav aria-label="Breadcrumb" class="flex items-center gap-1.5 text-xs text-text-muted mb-4">
                <a href="{{ route('home') }}" wire:navigate class="hover:text-primary transition-colors flex items-center gap-1">
                    <span class="material-symbols-outlined text-[16px]">home</span>
                    Beranda
                </a>
                <span class="material-symbols-outlined text-[14px]">chevron_right</span>
                <span class="font-bold text-primary bg-surface-container px-2 py-0.5 rounded">Katalog Lab</span>
            </nav>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-end">
                <div class="lg:col-span-8 flex flex-col gap-2">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-status-tersedia-bg w-fit">
                        <span class="w-2 h-2 rounded-full bg-status-tersedia animate-pulse"></span>
                        <span class="text-xs font-bold text-secondary uppercase tracking-wider">Standard IMO STCW 1978 / Amandemen Manila 2010</span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl md:text-4xl font-extrabold text-primary tracking-tight">
                        Katalog Laboratorium & Simulator SPP
                    </h1>
                    <p class="text-sm md:text-base text-text-muted max-w-3xl leading-relaxed">
                        Eksplorasi fasilitas simulator canggih dan laboratorium praktikum berstandar IMO STCW di STIP Jakarta. Pemantauan kesiapan fasilitas dan jadwal operasional real-time transparan bagi seluruh civitas akademika.
                    </p>
                </div>

                <div class="lg:col-span-4 flex flex-row lg:flex-col justify-between lg:justify-end gap-3 bg-canvas-bg p-4 rounded-2xl border border-border-subtle">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-xl bg-primary text-white flex items-center justify-center shadow-md">
                            <span class="material-symbols-outlined text-[26px]">precision_manufacturing</span>
                        </div>
                        <div class="flex flex-col">
                            <span class="font-extrabold text-lg text-primary">{{ $counts['all'] }} Lab Aktif</span>
                            <span class="text-xs text-text-muted">Total Fasilitas Terdaftar</span>
                        </div>
                    </div>
                    <div class="flex items-center gap-1 text-status-tersedia text-xs font-bold">
                        <span class="material-symbols-outlined text-[18px]">verified</span>
                        <span>Tersertifikasi BPSDM Perhubungan</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Search & Filter Deck -->
    <section class="max-w-7xl mx-auto px-4 md:px-8 -mt-6 z-10 w-full">
        <div class="bg-surface-white rounded-2xl shadow-lg p-4 md:p-6 flex flex-col gap-4 border border-border-subtle">
            <!-- Filter Bar Row 1: Inputs & Selects -->
            <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                <!-- Search -->
                <div class="md:col-span-6 relative">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline text-[20px]">search</span>
                    <input type="text"
                           wire:model.live.debounce.300ms="search"
                           placeholder="Cari nama lab, kode (ERCS, CHL, dsb), atau lokasi..."
                           class="w-full h-11 pl-10 pr-4 rounded-xl bg-canvas-bg text-text-primary placeholder:text-text-muted text-sm border border-border-subtle focus:outline-none focus:border-secondary focus:bg-white transition-all"/>
                </div>

                <!-- Status Filter -->
                <div class="md:col-span-3 relative">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline text-[18px] pointer-events-none">filter_alt</span>
                    <select wire:model.live="statusFilter"
                            class="w-full h-11 pl-9 pr-8 rounded-xl bg-canvas-bg text-text-primary text-sm border border-border-subtle focus:outline-none focus:border-secondary focus:bg-white cursor-pointer appearance-none">
                        <option value="all">Semua Status Operasional</option>
                        <option value="tersedia">Hanya Tersedia Hari Ini</option>
                        <option value="terisi">Terisi / Terjadwal</option>
                        <option value="maintenance">Tidak Operasional (Perawatan)</option>
                    </select>
                    <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-outline text-[18px] pointer-events-none">expand_more</span>
                </div>

                <!-- Sort Filter -->
                <div class="md:col-span-3 relative">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline text-[18px] pointer-events-none">swap_vert</span>
                    <select wire:model.live="sortOrder"
                            class="w-full h-11 pl-9 pr-8 rounded-xl bg-canvas-bg text-text-primary text-sm border border-border-subtle focus:outline-none focus:border-secondary focus:bg-white cursor-pointer appearance-none">
                        <option value="code-asc">Urutkan: Kode (A-Z)</option>
                        <option value="code-desc">Urutkan: Kode (Z-A)</option>
                        <option value="cap-desc">Urutkan: Kapasitas Tertinggi</option>
                        <option value="cap-asc">Urutkan: Kapasitas Terendah</option>
                    </select>
                    <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-outline text-[18px] pointer-events-none">expand_more</span>
                </div>
            </div>

            <!-- Filter Bar Row 2: Department Tabs & Info -->
            <div class="flex items-center justify-between flex-wrap gap-3 pt-1 border-t border-border-subtle/60">
                <div class="flex items-center gap-1.5 flex-wrap">
                    <button type="button" wire:click="$set('department', 'all')"
                            class="px-3.5 py-1.5 rounded-lg text-xs font-bold transition-all {{ $department === 'all' ? 'bg-primary text-white shadow-xs' : 'bg-canvas-bg text-on-surface-variant hover:bg-surface-container-high' }}">
                        Semua ({{ $counts['all'] }})
                    </button>
                    <button type="button" wire:click="$set('department', 'teknika')"
                            class="px-3.5 py-1.5 rounded-lg text-xs font-bold transition-all {{ $department === 'teknika' ? 'bg-primary text-white shadow-xs' : 'bg-canvas-bg text-on-surface-variant hover:bg-surface-container-high' }}">
                        Teknika ({{ $counts['teknika'] }})
                    </button>
                    <button type="button" wire:click="$set('department', 'nautika')"
                            class="px-3.5 py-1.5 rounded-lg text-xs font-bold transition-all {{ $department === 'nautika' ? 'bg-primary text-white shadow-xs' : 'bg-canvas-bg text-on-surface-variant hover:bg-surface-container-high' }}">
                        Nautika ({{ $counts['nautika'] }})
                    </button>
                    <button type="button" wire:click="$set('department', 'kalk')"
                            class="px-3.5 py-1.5 rounded-lg text-xs font-bold transition-all {{ $department === 'kalk' ? 'bg-primary text-white shadow-xs' : 'bg-canvas-bg text-on-surface-variant hover:bg-surface-container-high' }}">
                        KALK ({{ $counts['kalk'] }})
                    </button>
                    <button type="button" wire:click="$set('department', 'umum')"
                            class="px-3.5 py-1.5 rounded-lg text-xs font-bold transition-all {{ $department === 'umum' ? 'bg-primary text-white shadow-xs' : 'bg-canvas-bg text-on-surface-variant hover:bg-surface-container-high' }}">
                        Umum ({{ $counts['umum'] }})
                    </button>
                </div>

                <div class="flex items-center gap-1.5 text-xs text-text-muted">
                    <span class="material-symbols-outlined text-[16px] text-secondary">info</span>
                    <span>Status kalender anonim tanpa menampilkan identitas peminjam</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Lab Cards Grid -->
    <main class="max-w-7xl mx-auto px-4 md:px-8 py-10 w-full">
        <!-- Loading Indicator -->
        <div wire:loading class="w-full text-center py-6">
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-surface-white shadow-md text-sm text-secondary font-semibold">
                <span class="material-symbols-outlined animate-spin text-[20px]">progress_activity</span>
                <span>Memuat data laboratorium...</span>
            </div>
        </div>

        <div wire:loading.remove class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse ($rooms as $room)
                @php
                    // Placeholder fallback maritime simulator imagery by room code
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

                <article wire:key="lab-card-{{ $room->id }}"
                         class="group bg-surface-white rounded-2xl shadow-sm hover:shadow-xl border border-border-subtle transition-all duration-300 flex flex-col justify-between overflow-hidden">
                    <div>
                        <!-- Image & Badges Banner -->
                        <div class="relative w-full aspect-video overflow-hidden bg-primary-container">
                            <img src="{{ $imgUrl }}"
                                 alt="Simulator {{ $room->name }}"
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                 onerror="this.src='{{ $sampleImages['ERCS'] }}'"/>
                            <div class="absolute inset-0 bg-gradient-to-t from-primary/85 via-primary/20 to-transparent"></div>

                            <div class="absolute top-3 left-3 flex items-center gap-2">
                                <span class="px-2.5 py-1 rounded-lg bg-primary text-white font-bold text-xs tracking-wider shadow-sm">
                                    {{ $room->code }}
                                </span>
                                <span class="px-2.5 py-1 rounded-full bg-surface-white/95 backdrop-blur-md text-[11px] font-semibold text-primary">
                                    {{ $room->location ?? 'Gedung SPP' }}
                                </span>
                            </div>

                            <div class="absolute bottom-3 left-3 right-3 flex justify-between items-center text-white">
                                <div class="flex items-center gap-1.5 text-xs">
                                    <span class="material-symbols-outlined text-[16px] text-secondary-fixed">group</span>
                                    <span>Kapasitas {{ $room->capacity }} Taruna</span>
                                </div>
                                <span class="text-[10px] uppercase font-bold bg-navy-light/80 px-2 py-0.5 rounded">
                                    {{ strtoupper($room->lab_category) }}
                                </span>
                            </div>
                        </div>

                        <!-- Card Content -->
                        <div class="p-5 flex flex-col gap-3">
                            <!-- Status Indicator -->
                            <div class="flex items-center justify-between">
                                <span class="px-2.5 py-1 rounded-full text-xs font-bold inline-flex items-center gap-1.5 bg-{{ $room->status_bg }} text-{{ $room->status_color }}">
                                    <span class="w-2 h-2 rounded-full bg-{{ $room->status_color }} {{ $room->computed_status === 'tersedia' ? 'animate-pulse' : '' }}"></span>
                                    {{ $room->status_label }}
                                </span>
                                <span class="text-xs text-text-muted">
                                    07.30 - 16.00 WIB
                                </span>
                            </div>

                            <a href="{{ route('lab.detail', $room->code) }}" wire:navigate class="group-hover:text-secondary transition-colors">
                                <h3 class="font-bold text-base text-primary leading-snug">
                                    {{ $room->name }}
                                </h3>
                            </a>

                            <p class="text-xs text-text-muted leading-relaxed line-clamp-2">
                                {{ $room->description ?? 'Fasilitas praktikum navigasi dan kamar mesin simulator berstandar IMO STCW untuk taruna STIP Jakarta.' }}
                            </p>
                        </div>
                    </div>

                    <!-- Card Actions -->
                    <div class="p-5 pt-0 flex items-center justify-between gap-2 border-t border-border-subtle/50 mt-2">
                        <a href="{{ route('lab.schedule', ['room' => $room->code]) }}" wire:navigate
                           class="flex-1 text-center py-2.5 px-3 rounded-xl bg-canvas-bg hover:bg-surface-container text-primary font-bold text-xs transition-colors border border-border-subtle">
                            Jadwal Lab
                        </a>
                        <a href="{{ route('lab.detail', $room->code) }}" wire:navigate
                           class="flex-1 text-center py-2.5 px-3 rounded-xl bg-primary hover:bg-navy-light text-white font-bold text-xs transition-colors shadow-xs">
                            Detail & Rincian →
                        </a>
                    </div>
                </article>
            @empty
                <div class="col-span-full py-16 text-center bg-surface-white rounded-2xl border border-border-subtle flex flex-col items-center justify-center gap-3">
                    <span class="material-symbols-outlined text-[48px] text-text-muted">search_off</span>
                    <h3 class="font-bold text-lg text-primary">Tidak Ada Laboratorium Ditemukan</h3>
                    <p class="text-xs text-text-muted max-w-md">
                        Tidak ada laboratorium yang sesuai dengan kriteria pencarian "{{ $search }}". Silakan reset filter untuk melihat seluruh fasilitas.
                    </p>
                    <button type="button" wire:click="resetFilters"
                            class="mt-2 px-4 py-2 rounded-xl bg-primary text-white text-xs font-bold hover:bg-navy-light transition-colors">
                        Reset Filter
                    </button>
                </div>
            @endforelse
        </div>
    </main>
</div>
