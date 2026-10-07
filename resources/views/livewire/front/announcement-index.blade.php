<div class="flex flex-col w-full">
    <!-- Visual Oceanic Header Canvas Area -->
    <section class="relative w-full bg-primary overflow-hidden text-white border-b border-border-subtle">
        <div class="absolute inset-0 opacity-10 pointer-events-none">
            <svg class="w-full h-full object-cover" height="100%" width="100%" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <pattern height="80" id="maritime-wave-grid" patternunits="userSpaceOnUse" width="80">
                        <path d="M0 40 Q 20 20, 40 40 T 80 40" fill="none" stroke="#8ff3f2" stroke-width="1.2"></path>
                        <path d="M0 60 Q 20 40, 40 60 T 80 60" fill="none" stroke="#ffdea8" stroke-width="0.8"></path>
                        <circle cx="40" cy="40" fill="#8ff3f2" r="1.5"></circle>
                    </pattern>
                </defs>
                <rect fill="url(#maritime-wave-grid)" height="100%" width="100%"></rect>
            </svg>
        </div>

        <div class="max-w-7xl mx-auto px-4 md:px-8 py-10 relative z-10">
            <!-- Breadcrumb -->
            <nav aria-label="Breadcrumb" class="flex items-center gap-1.5 text-xs text-on-primary-container mb-4">
                <a href="{{ route('home') }}" wire:navigate class="hover:text-secondary-fixed transition-colors flex items-center gap-1">
                    <span class="material-symbols-outlined text-[16px]">home</span>
                    <span>Beranda</span>
                </a>
                <span class="material-symbols-outlined text-[14px]">chevron_right</span>
                <span class="text-secondary-fixed font-bold">Pengumuman</span>
            </nav>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-end">
                <div class="lg:col-span-8 flex flex-col gap-2">
                    <div class="inline-flex items-center gap-2 self-start px-3 py-1 rounded-full bg-navy-light text-secondary-fixed text-xs font-bold uppercase tracking-wider">
                        <span class="w-2 h-2 rounded-full bg-secondary-fixed animate-ping"></span>
                        SIARAN RESMI OPERASIONAL KAMPUS MARUNDA
                    </div>
                    <h1 class="text-2xl sm:text-3xl md:text-4xl font-extrabold text-white tracking-tight">
                        Pusat Pengumuman & Informasi Operasional
                    </h1>
                    <p class="text-sm md:text-base text-on-primary-container max-w-3xl leading-relaxed">
                        Jadwal pemeliharaan simulator, kalender libur akademik, pelaksanaan UKP, dan regulasi peminjaman fasilitas STIP Jakarta.
                    </p>
                </div>

                <!-- Live Status Metric Card -->
                <div class="lg:col-span-4 bg-white/10 backdrop-blur-md rounded-2xl p-4 flex items-center justify-between border border-white/15 shadow-sm">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-xl bg-tertiary-fixed text-primary flex items-center justify-center">
                            <span class="material-symbols-outlined text-[26px]">notifications_active</span>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-[10px] text-secondary-fixed uppercase font-bold tracking-wider">Status Pemeliharaan</span>
                            <span class="font-extrabold text-base text-white">Kalibrasi Aktif</span>
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="text-[11px] font-bold text-white bg-status-inoperative px-2.5 py-1 rounded-lg">
                            ACSL Perawatan
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content Layout Section -->
    <section class="max-w-7xl mx-auto px-4 md:px-8 -mt-6 relative z-20 pb-16 w-full">
        <!-- Pinned Urgency Card (Pengumuman Disematkan) -->
        @if ($pinned)
            <div class="w-full bg-surface-white rounded-2xl shadow-md p-6 mb-8 border border-border-subtle relative overflow-hidden">
                <div class="absolute top-0 bottom-0 left-0 w-2.5 bg-tertiary-fixed-dim"></div>
                <div class="pl-2 flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div class="flex flex-col gap-2">
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="inline-flex items-center gap-1 bg-gold-subtle text-on-tertiary-container px-2.5 py-0.5 rounded-full text-xs font-bold uppercase tracking-wider">
                                <span class="material-symbols-outlined text-[15px]">push_pin</span>
                                Disematkan
                            </span>
                            <span class="text-xs font-bold bg-status-inoperative-bg text-status-inoperative px-2.5 py-0.5 rounded-full">
                                {{ $pinned['category_label'] }}
                            </span>
                            <span class="text-xs text-text-muted flex items-center gap-1">
                                <span class="material-symbols-outlined text-[14px]">calendar_today</span>
                                {{ $pinned['date'] }}
                            </span>
                            <span class="text-xs font-bold bg-primary text-white px-2 py-0.5 rounded-lg">
                                {{ $pinned['room_tag'] }}
                            </span>
                        </div>

                        <h2 wire:click="selectAnnouncement('{{ $pinned['id'] }}')"
                            class="text-lg md:text-xl font-bold text-primary hover:text-secondary transition-colors cursor-pointer leading-snug">
                            {{ $pinned['title'] }}
                        </h2>
                        <p class="text-xs md:text-sm text-text-muted max-w-4xl leading-relaxed">
                            {{ $pinned['summary'] }}
                        </p>
                    </div>

                    <div class="flex items-center shrink-0">
                        <button type="button" wire:click="selectAnnouncement('{{ $pinned['id'] }}')"
                                class="h-11 px-5 rounded-xl bg-tertiary-fixed-dim hover:bg-gold-hover text-primary font-bold text-xs inline-flex items-center gap-1.5 shadow-sm transition-all">
                            <span>Rincian Dampak</span>
                            <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                        </button>
                    </div>
                </div>
            </div>
        @endif

        <!-- Filter Deck: Search & Categories -->
        <div class="bg-surface-white rounded-2xl shadow-sm p-4 border border-border-subtle flex flex-col md:flex-row items-center justify-between gap-4 mb-6">
            <!-- Category Pills -->
            <div class="flex items-center gap-1.5 flex-wrap w-full md:w-auto">
                <button type="button" wire:click="$set('category', 'all')"
                        class="px-3.5 py-2 rounded-xl text-xs font-bold transition-all {{ $category === 'all' ? 'bg-primary text-white shadow-xs' : 'bg-canvas-bg text-on-surface-variant hover:bg-surface-container-high' }}">
                    Semua ({{ $counts['all'] }})
                </button>
                <button type="button" wire:click="$set('category', 'pemeliharaan')"
                        class="px-3.5 py-2 rounded-xl text-xs font-bold transition-all {{ $category === 'pemeliharaan' ? 'bg-primary text-white shadow-xs' : 'bg-canvas-bg text-on-surface-variant hover:bg-surface-container-high' }}">
                    Pemeliharaan Lab ({{ $counts['pemeliharaan'] }})
                </button>
                <button type="button" wire:click="$set('category', 'ukp')"
                        class="px-3.5 py-2 rounded-xl text-xs font-bold transition-all {{ $category === 'ukp' ? 'bg-primary text-white shadow-xs' : 'bg-canvas-bg text-on-surface-variant hover:bg-surface-container-high' }}">
                    Jadwal UKP ({{ $counts['ukp'] }})
                </button>
                <button type="button" wire:click="$set('category', 'kalender')"
                        class="px-3.5 py-2 rounded-xl text-xs font-bold transition-all {{ $category === 'kalender' ? 'bg-primary text-white shadow-xs' : 'bg-canvas-bg text-on-surface-variant hover:bg-surface-container-high' }}">
                    Kalender Akademik ({{ $counts['kalender'] }})
                </button>
                <button type="button" wire:click="$set('category', 'regulasi')"
                        class="px-3.5 py-2 rounded-xl text-xs font-bold transition-all {{ $category === 'regulasi' ? 'bg-primary text-white shadow-xs' : 'bg-canvas-bg text-on-surface-variant hover:bg-surface-container-high' }}">
                    Regulasi ({{ $counts['regulasi'] }})
                </button>
            </div>

            <!-- Search Bar -->
            <div class="relative w-full md:w-72">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline text-[18px]">search</span>
                <input type="text"
                       wire:model.live.debounce.300ms="search"
                       placeholder="Cari pengumuman..."
                       class="w-full h-10 pl-9 pr-3 rounded-xl bg-canvas-bg text-primary text-xs border border-border-subtle focus:outline-none focus:border-secondary focus:bg-white transition-all"/>
            </div>
        </div>

        <!-- Announcements Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse ($announcements as $ann)
                <article wire:key="ann-{{ $ann['id'] }}"
                         class="bg-surface-white rounded-2xl p-6 shadow-xs hover:shadow-md border border-border-subtle flex flex-col justify-between transition-all duration-300">
                    <div class="flex flex-col gap-3">
                        <div class="flex items-center justify-between">
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-{{ $ann['category_bg'] }} text-{{ $ann['category_color'] }}">
                                {{ $ann['category_label'] }}
                            </span>
                            <span class="text-xs text-text-muted">{{ $ann['date'] }}</span>
                        </div>

                        <div class="flex flex-col gap-1">
                            <h3 wire:click="selectAnnouncement('{{ $ann['id'] }}')"
                                class="font-bold text-base text-primary hover:text-secondary transition-colors cursor-pointer leading-snug">
                                {{ $ann['title'] }}
                            </h3>
                            <p class="text-xs text-text-muted leading-relaxed line-clamp-3 mt-1">
                                {{ $ann['summary'] }}
                            </p>
                        </div>
                    </div>

                    <div class="pt-4 mt-4 border-t border-border-subtle flex items-center justify-between text-xs">
                        <span class="font-bold text-primary bg-surface-container px-2 py-0.5 rounded">
                            {{ $ann['room_tag'] }}
                        </span>
                        <button type="button" wire:click="selectAnnouncement('{{ $ann['id'] }}')"
                                class="text-secondary font-bold hover:underline flex items-center gap-1">
                            <span>Rincian</span>
                            <span class="material-symbols-outlined text-[16px]">chevron_right</span>
                        </button>
                    </div>
                </article>
            @empty
                <div class="col-span-full py-16 text-center bg-surface-white rounded-2xl border border-border-subtle flex flex-col items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-[48px] text-text-muted">announcement</span>
                    <h3 class="font-bold text-base text-primary">Tidak Ada Pengumuman</h3>
                    <p class="text-xs text-text-muted">Tidak ada pengumuman yang sesuai dengan filter atau kata kunci pencarian Anda.</p>
                </div>
            @endforelse
        </div>
    </section>

    <!-- Modal Rincian Dampak Pengumuman -->
    @if ($showModal && $selectedAnnouncement)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-xs p-4"
             x-transition.opacity>
            <div class="bg-surface-white rounded-3xl max-w-lg w-full p-6 shadow-2xl border border-border-subtle flex flex-col gap-4 relative animate-in fade-in zoom-in-95 duration-200">
                <button type="button" wire:click="closeModal"
                        class="absolute top-4 right-4 w-8 h-8 rounded-full bg-canvas-bg hover:bg-surface-container flex items-center justify-center text-primary transition-colors">
                    <span class="material-symbols-outlined text-[18px]">close</span>
                </button>

                <div class="flex items-center gap-2">
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-{{ $selectedAnnouncement['category_bg'] }} text-{{ $selectedAnnouncement['category_color'] }}">
                        {{ $selectedAnnouncement['category_label'] }}
                    </span>
                    <span class="text-xs text-text-muted">{{ $selectedAnnouncement['date'] }}</span>
                </div>

                <div class="flex flex-col gap-1">
                    <h3 class="text-lg font-bold text-primary leading-snug">{{ $selectedAnnouncement['title'] }}</h3>
                    <span class="text-xs font-bold text-secondary">Fasilitas Terdampak: {{ $selectedAnnouncement['room_tag'] }}</span>
                </div>

                <p class="text-xs text-text-muted leading-relaxed">
                    {{ $selectedAnnouncement['summary'] }}
                </p>

                <div class="p-4 rounded-2xl bg-canvas-bg border border-border-subtle flex flex-col gap-2">
                    <span class="text-xs font-bold text-primary flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-secondary text-[18px]">info</span>
                        Dampak Terhadap Penjadwalan Lab:
                    </span>
                    <p class="text-xs text-text-muted leading-relaxed">
                        {{ $selectedAnnouncement['impact'] }}
                    </p>
                </div>

                <div class="flex items-center justify-end gap-2 pt-2 border-t border-border-subtle">
                    <button type="button" wire:click="closeModal"
                            class="px-4 py-2.5 rounded-xl bg-canvas-bg hover:bg-surface-container text-xs font-bold text-primary transition-colors">
                        Tutup
                    </button>
                    <a href="{{ route('lab.schedule') }}" wire:navigate
                       class="px-4 py-2.5 rounded-xl bg-primary hover:bg-navy-light text-white text-xs font-bold transition-colors">
                        Cek Kalender Jadwal →
                    </a>
                </div>
            </div>
        </div>
    @endif
</div>
