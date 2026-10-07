<div class="flex flex-col w-full">
    <!-- Top Ambient Glow Marine Background Strip -->
    <div class="w-full relative overflow-hidden bg-canvas-bg">
        <div class="absolute -top-32 right-1/4 w-96 h-96 rounded-full bg-surface-container-high/40 blur-3xl pointer-events-none"></div>
        <div class="absolute top-48 left-10 w-80 h-80 rounded-full bg-secondary-fixed/20 blur-3xl pointer-events-none"></div>

        <!-- 1. Hero Section -->
        <section class="max-w-7xl mx-auto px-4 md:px-8 pt-8 pb-16 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
                <!-- Hero Left Column -->
                <div class="lg:col-span-7 flex flex-col gap-6">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-surface-container-high/60 w-fit border border-border-subtle">
                        <span class="w-2 h-2 rounded-full bg-status-tersedia animate-pulse"></span>
                        <span class="text-xs font-bold text-primary tracking-wider uppercase">Sistem Terpadu STIP Jakarta v2.4</span>
                    </div>

                    <div class="flex flex-col gap-3">
                        <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-primary tracking-tight leading-tight">
                            Satu Aplikasi untuk Seluruh Layanan Kampus STIP Jakarta
                        </h1>
                        <p class="text-base sm:text-lg text-text-muted max-w-2xl leading-relaxed">
                            Booking lab dan simulator, pengelolaan BMN, surat izin rumah dinas, dan perpustakaan dalam satu akun, tanpa bentrok, dan terdokumentasi.
                        </p>
                    </div>

                    <!-- Action CTAs -->
                    <div class="flex flex-wrap items-center gap-4 pt-2">
                        <a href="{{ route('lab.schedule') }}" wire:navigate
                           class="inline-flex items-center justify-center gap-2 h-12 px-6 rounded-xl bg-tertiary-fixed-dim hover:bg-gold-hover text-primary font-bold text-sm shadow-md hover:shadow-lg transition-all duration-200">
                            <span class="material-symbols-outlined text-[20px]">calendar_month</span>
                            <span>Lihat Jadwal Lab</span>
                        </a>
                        <a href="{{ route('lab.catalog') }}" wire:navigate
                           class="inline-flex items-center justify-center gap-2 h-12 px-6 rounded-xl bg-surface-white hover:bg-surface-container text-primary font-semibold text-sm shadow-sm hover:shadow-md border border-border-subtle transition-all duration-200">
                            <span class="material-symbols-outlined text-[20px]">travel_explore</span>
                            <span>Jelajahi Lab</span>
                        </a>
                    </div>

                    <!-- Operational Live Tagline -->
                    <div class="flex items-center gap-2 text-text-muted text-xs sm:text-sm">
                        <span class="material-symbols-outlined text-status-tersedia text-[18px]">verified</span>
                        <span>Sinkronisasi otomatis dengan Kalender Akademik Semester Genap 2025/2026</span>
                    </div>
                </div>

                <!-- Hero Right Column: Simulator Schedule Card Preview -->
                <div class="lg:col-span-5 relative">
                    <div class="w-full bg-surface-white rounded-2xl shadow-xl p-6 flex flex-col gap-4 border border-border-subtle">
                        <div class="flex items-center justify-between pb-2 border-b border-border-subtle">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-primary text-white flex items-center justify-center shadow-xs">
                                    <span class="material-symbols-outlined text-[22px]">grid_view</span>
                                </div>
                                <div class="flex flex-col">
                                    <span class="font-bold text-base text-primary">Operasional Simulator</span>
                                    <span class="text-xs text-text-muted">Live Monitoring • 8 Lab Aktif</span>
                                </div>
                            </div>
                            <div class="px-2.5 py-1 rounded-full bg-status-tersedia-bg text-secondary text-xs font-semibold flex items-center gap-1.5">
                                <span class="w-1.5 h-1.5 rounded-full bg-secondary animate-pulse"></span>
                                <span>07.30 - 16.00 WIB</span>
                            </div>
                        </div>

                        <!-- Mini Calendar Matrix -->
                        <div class="bg-surface-container-low rounded-xl p-3 flex flex-col gap-2">
                            <div class="grid grid-cols-5 text-center text-xs font-bold text-text-muted py-1 border-b border-border-subtle/50">
                                <span>SEN</span>
                                <span>SEL</span>
                                <span>RAB</span>
                                <span>KAM</span>
                                <span>JUM</span>
                            </div>

                            <div class="grid grid-cols-5 gap-2 text-center text-xs">
                                <!-- Senin -->
                                <div class="flex flex-col gap-1.5">
                                    <div class="p-2 rounded-lg bg-status-terisi text-white shadow-2xs flex flex-col items-center">
                                        <span class="font-bold text-[11px]">ERCS</span>
                                        <span class="text-[9px] opacity-80">08.00-11.00</span>
                                        <span class="mt-1 px-1 py-0.2 rounded bg-white/20 text-[8px] uppercase">Terisi</span>
                                    </div>
                                    <div class="p-2 rounded-lg bg-status-tersedia-bg text-secondary shadow-2xs flex flex-col items-center">
                                        <span class="font-bold text-[11px]">CHL</span>
                                        <span class="text-[9px] opacity-80">13.00-15.30</span>
                                        <span class="mt-1 px-1 py-0.2 rounded bg-secondary/15 text-[8px] uppercase font-bold">Terisi</span>
                                    </div>
                                </div>

                                <!-- Selasa -->
                                <div class="flex flex-col gap-1.5">
                                    <div class="p-2 rounded-lg bg-status-terisi text-white shadow-2xs flex flex-col items-center">
                                        <span class="font-bold text-[11px]">MEL</span>
                                        <span class="text-[9px] opacity-80">08.30-11.30</span>
                                        <span class="mt-1 px-1 py-0.2 rounded bg-white/20 text-[8px] uppercase">Terisi</span>
                                    </div>
                                    <div class="p-2 rounded-lg bg-status-terisi text-white shadow-2xs flex flex-col items-center">
                                        <span class="font-bold text-[11px]">EEL</span>
                                        <span class="text-[9px] opacity-80">12.30-15.30</span>
                                        <span class="mt-1 px-1 py-0.2 rounded bg-white/20 text-[8px] uppercase">Terisi</span>
                                    </div>
                                </div>

                                <!-- Rabu -->
                                <div class="flex flex-col gap-1.5">
                                    <div class="p-2 rounded-lg bg-status-terisi text-white shadow-2xs flex flex-col items-center">
                                        <span class="font-bold text-[11px]">CBT</span>
                                        <span class="text-[9px] opacity-80">08.00-12.00</span>
                                        <span class="mt-1 px-1 py-0.2 rounded bg-white/20 text-[8px] uppercase">UKP</span>
                                    </div>
                                    <div class="p-2 rounded-lg bg-status-tersedia-bg text-secondary shadow-2xs flex flex-col items-center">
                                        <span class="font-bold text-[11px]">Slot Siang</span>
                                        <span class="text-[9px] opacity-80">13.00-16.00</span>
                                        <span class="mt-1 px-1 py-0.2 rounded bg-secondary/15 text-[8px] uppercase font-bold">Tersedia</span>
                                    </div>
                                </div>

                                <!-- Kamis -->
                                <div class="flex flex-col gap-1.5">
                                    <div class="p-2 rounded-lg bg-status-terisi text-white shadow-2xs flex flex-col items-center">
                                        <span class="font-bold text-[11px]">EWS</span>
                                        <span class="text-[9px] opacity-80">08.30-12.00</span>
                                        <span class="mt-1 px-1 py-0.2 rounded bg-white/20 text-[8px] uppercase">Terisi</span>
                                    </div>
                                    <div class="p-2 rounded-lg bg-status-terisi text-white shadow-2xs flex flex-col items-center">
                                        <span class="font-bold text-[11px]">ERCS</span>
                                        <span class="text-[9px] opacity-80">13.00-16.00</span>
                                        <span class="mt-1 px-1 py-0.2 rounded bg-white/20 text-[8px] uppercase">ATT-II</span>
                                    </div>
                                </div>

                                <!-- Jumat -->
                                <div class="flex flex-col gap-1.5">
                                    <div class="p-2 rounded-lg bg-status-terisi text-white shadow-2xs flex flex-col items-center">
                                        <span class="font-bold text-[11px]">LTL</span>
                                        <span class="text-[9px] opacity-80">08.00-11.00</span>
                                        <span class="mt-1 px-1 py-0.2 rounded bg-white/20 text-[8px] uppercase">SMCP</span>
                                    </div>
                                    <div class="p-2 rounded-lg bg-status-tersedia-bg text-secondary shadow-2xs flex flex-col items-center">
                                        <span class="font-bold text-[11px]">Slot Siang</span>
                                        <span class="text-[9px] opacity-80">13.30-16.00</span>
                                        <span class="mt-1 px-1 py-0.2 rounded bg-secondary/15 text-[8px] uppercase font-bold">Tersedia</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Footer mini preview -->
                        <div class="flex items-center justify-between text-xs text-text-muted pt-1">
                            <span class="flex items-center gap-1">
                                <span class="material-symbols-outlined text-[16px] text-secondary">info</span>
                                Tanpa nama peminjam (Kebijakan Privasi)
                            </span>
                            <a href="{{ route('lab.schedule') }}" wire:navigate class="text-secondary font-bold hover:underline">
                                Buka Kalender Lengkap →
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- 2. Ringkasan 4 Modul Layanan Kampus Terpadu -->
    <section class="max-w-7xl mx-auto px-4 md:px-8 py-16 w-full">
        <div class="flex flex-col items-center text-center gap-3 mb-12">
            <span class="text-xs font-bold text-secondary uppercase tracking-widest">Empat Layanan dalam Satu Basis Data</span>
            <h2 class="text-2xl sm:text-3xl font-extrabold text-primary">Cakupan Sistem Terpadu SILT-STIP</h2>
            <p class="text-sm sm:text-base text-text-muted max-w-2xl">
                Integrasi menyeluruh pengelolaan fasilitas praktikum, sarana prasarana negara, perumahan dinas, dan literasi maritim.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Modul A: Lab SPP -->
            <div class="bg-surface-white rounded-2xl p-6 shadow-sm hover:shadow-md border border-border-subtle flex flex-col justify-between transition-all duration-300">
                <div class="flex flex-col gap-4">
                    <div class="w-12 h-12 rounded-xl bg-primary/10 text-primary flex items-center justify-center">
                        <span class="material-symbols-outlined text-[26px]">precision_manufacturing</span>
                    </div>
                    <div class="flex flex-col gap-1">
                        <span class="text-xs font-bold text-secondary uppercase tracking-wider">Modul A</span>
                        <h3 class="font-bold text-lg text-primary">Lab & Simulator SPP</h3>
                        <p class="text-xs text-text-muted leading-relaxed">
                            Peminjaman 8 simulator canggih tanpa tumpang tindih. Verifikasi 2 tahap (Petugas SPP & Kepala Unit) terintegrasi IMO Model Course.
                        </p>
                    </div>
                </div>
                <div class="pt-6 mt-4 border-t border-border-subtle flex items-center justify-between">
                    <span class="text-xs font-bold text-primary">{{ $stats['labs'] }} Lab Aktif</span>
                    <a href="{{ route('lab.catalog') }}" wire:navigate class="text-xs font-bold text-secondary hover:underline">
                        Lihat Lab →
                    </a>
                </div>
            </div>

            <!-- Modul B: BMN -->
            <div class="bg-surface-white rounded-2xl p-6 shadow-sm hover:shadow-md border border-border-subtle flex flex-col justify-between transition-all duration-300">
                <div class="flex flex-col gap-4">
                    <div class="w-12 h-12 rounded-xl bg-navy-light/10 text-navy-light flex items-center justify-center">
                        <span class="material-symbols-outlined text-[26px]">inventory_2</span>
                    </div>
                    <div class="flex flex-col gap-1">
                        <span class="text-xs font-bold text-navy-light uppercase tracking-wider">Modul B</span>
                        <h3 class="font-bold text-lg text-primary">Pengelolaan BMN</h3>
                        <p class="text-xs text-text-muted leading-relaxed">
                            Inventaris barang milik negara per unit & ruangan. Pengajuan pengadaan baru, mutasi antar ruangan, dan pengembalian barang rusak.
                        </p>
                    </div>
                </div>
                <div class="pt-6 mt-4 border-t border-border-subtle flex items-center justify-between">
                    <span class="text-xs font-bold text-primary">{{ $stats['bmn'] }} Aset Terdata</span>
                    <a href="/admin" target="_blank" class="text-xs font-bold text-navy-light hover:underline">
                        Akses BMN →
                    </a>
                </div>
            </div>

            <!-- Modul C: Rumah Dinas -->
            <div class="bg-surface-white rounded-2xl p-6 shadow-sm hover:shadow-md border border-border-subtle flex flex-col justify-between transition-all duration-300">
                <div class="flex flex-col gap-4">
                    <div class="w-12 h-12 rounded-xl bg-tertiary-fixed-dim/20 text-on-tertiary-container flex items-center justify-center">
                        <span class="material-symbols-outlined text-[26px]">home_work</span>
                    </div>
                    <div class="flex flex-col gap-1">
                        <span class="text-xs font-bold text-gold-hover uppercase tracking-wider">Modul C</span>
                        <h3 class="font-bold text-lg text-primary">Rumah Dinas</h3>
                        <p class="text-xs text-text-muted leading-relaxed">
                            Pengajuan Surat Izin Penghunian (SIP) rumah dinas pegawai Marunda. Verifikasi administrasi hingga persetujuan akhir Ketua STIP.
                        </p>
                    </div>
                </div>
                <div class="pt-6 mt-4 border-t border-border-subtle flex items-center justify-between">
                    <span class="text-xs font-bold text-primary">SIP Terintegrasi</span>
                    <a href="/admin" target="_blank" class="text-xs font-bold text-gold-hover hover:underline">
                        Akses SIP →
                    </a>
                </div>
            </div>

            <!-- Modul D: Perpustakaan -->
            <div class="bg-surface-white rounded-2xl p-6 shadow-sm hover:shadow-md border border-border-subtle flex flex-col justify-between transition-all duration-300">
                <div class="flex flex-col gap-4">
                    <div class="w-12 h-12 rounded-xl bg-status-tersedia-bg text-status-tersedia flex items-center justify-center">
                        <span class="material-symbols-outlined text-[26px]">local_library</span>
                    </div>
                    <div class="flex flex-col gap-1">
                        <span class="text-xs font-bold text-status-tersedia uppercase tracking-wider">Modul D</span>
                        <h3 class="font-bold text-lg text-primary">Perpustakaan</h3>
                        <p class="text-xs text-text-muted leading-relaxed">
                            Sirkulasi peminjaman dan pengembalian literatur maritim. Pencatatan denda otomatis dan kuota peminjaman khusus dosen dan taruna.
                        </p>
                    </div>
                </div>
                <div class="pt-6 mt-4 border-t border-border-subtle flex items-center justify-between">
                    <span class="text-xs font-bold text-primary">{{ $stats['books'] }} Judul Buku</span>
                    <a href="/admin" target="_blank" class="text-xs font-bold text-status-tersedia hover:underline">
                        Akses Perpus →
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- 3. Live Campus Metrics Counter -->
    <section class="w-full bg-primary py-12 text-white">
        <div class="max-w-7xl mx-auto px-4 md:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
                <div class="flex flex-col items-center gap-1">
                    <span class="text-3xl sm:text-4xl font-extrabold text-secondary-fixed">{{ $stats['labs'] }}</span>
                    <span class="text-xs sm:text-sm font-semibold uppercase tracking-wider text-on-primary-container">Lab & Simulator Aktif</span>
                    <span class="text-[11px] text-on-primary-container/70">Standar IMO STCW 1978</span>
                </div>
                <div class="flex flex-col items-center gap-1">
                    <span class="text-3xl sm:text-4xl font-extrabold text-tertiary-fixed">{{ $stats['bookings'] }}</span>
                    <span class="text-xs sm:text-sm font-semibold uppercase tracking-wider text-on-primary-container">Sesi Praktikum Disetujui</span>
                    <span class="text-[11px] text-on-primary-container/70">Anti-bentrok database</span>
                </div>
                <div class="flex flex-col items-center gap-1">
                    <span class="text-3xl sm:text-4xl font-extrabold text-secondary-fixed">{{ $stats['books'] }}</span>
                    <span class="text-xs sm:text-sm font-semibold uppercase tracking-wider text-on-primary-container">Katalog Buku Maritim</span>
                    <span class="text-[11px] text-on-primary-container/70">Koleksi Terverifikasi</span>
                </div>
                <div class="flex flex-col items-center gap-1">
                    <span class="text-3xl sm:text-4xl font-extrabold text-tertiary-fixed">{{ $stats['bmn'] }}</span>
                    <span class="text-xs sm:text-sm font-semibold uppercase tracking-wider text-on-primary-container">Aset BMN Terdata</span>
                    <span class="text-[11px] text-on-primary-container/70">Inventaris Ruangan & Unit</span>
                </div>
            </div>
        </div>
    </section>

    <!-- 4. Alur Prosedur Booking 3 Langkah -->
    <section class="max-w-7xl mx-auto px-4 md:px-8 py-16 w-full">
        <div class="flex flex-col items-center text-center gap-3 mb-12">
            <span class="text-xs font-bold text-secondary uppercase tracking-widest">Tata Kelola Praktikum Terpadu</span>
            <h2 class="text-2xl sm:text-3xl font-extrabold text-primary">Alur Peminjaman Laboratorium & Simulator</h2>
            <p class="text-sm sm:text-base text-text-muted max-w-2xl">
                Setiap peminjaman diproses secara transparan dengan prinsip First Come First Served (FCFS) sesuai PRD §3.3.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-surface-white rounded-2xl p-6 shadow-sm border border-border-subtle flex flex-col gap-4">
                <div class="w-10 h-10 rounded-xl bg-status-terisi text-white flex items-center justify-center font-bold">1</div>
                <h3 class="font-bold text-lg text-primary">Pilih Lab & Slot Waktu Kosong</h3>
                <p class="text-xs sm:text-sm text-text-muted leading-relaxed">
                    Dosen pengampu atau taruna memilih jadwal pada kalender real-time. Sistem otomatis memvalidasi ketersediaan dan mengunci slot tanpa tumpang tindih.
                </p>
            </div>

            <div class="bg-surface-white rounded-2xl p-6 shadow-sm border border-border-subtle flex flex-col gap-4">
                <div class="w-10 h-10 rounded-xl bg-status-terisi text-white flex items-center justify-center font-bold">2</div>
                <h3 class="font-bold text-lg text-primary">Verifikasi Petugas & Kepala Unit</h3>
                <p class="text-xs sm:text-sm text-text-muted leading-relaxed">
                    Petugas SPP memeriksa kesiapan lab, kecukupan kit bahan praktikum, serta kesesuaian RPS/IMO. Kepala Unit menerbitkan persetujuan resmi.
                </p>
            </div>

            <div class="bg-surface-white rounded-2xl p-6 shadow-sm border border-border-subtle flex flex-col gap-4">
                <div class="w-10 h-10 rounded-xl bg-status-terisi text-white flex items-center justify-center font-bold">3</div>
                <h3 class="font-bold text-lg text-primary">Pelaksanaan & Pencatatan Realisasi</h3>
                <p class="text-xs sm:text-sm text-text-muted leading-relaxed">
                    Surat konfirmasi booking diterbitkan. Pada hari pelaksanaan dilakukan check-in, pencatatan pemakaian bahan, dan kondisi alat setelah praktikum.
                </p>
            </div>
        </div>
    </section>

    <!-- 5. Dual Portal Access Band -->
    <section class="max-w-7xl mx-auto px-4 md:px-8 pb-16 w-full">
        <div class="bg-primary rounded-3xl p-8 sm:p-12 text-white shadow-xl relative overflow-hidden">
            <div class="absolute -right-16 -bottom-16 w-80 h-80 rounded-full bg-secondary/15 blur-2xl pointer-events-none"></div>

            <div class="max-w-3xl mb-8">
                <span class="text-xs font-bold text-secondary-fixed uppercase tracking-widest">Akses Portal Terpadu</span>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-white mt-2">Pintu Masuk SILT-STIP Jakarta</h2>
                <p class="text-sm text-on-primary-container mt-2">
                    Akun pegawai dan taruna dipisahkan sesuai guard Spatie Permission dan tata kelola Pusbangkar STIP.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Pegawai Card -->
                <div class="bg-white/10 backdrop-blur-md rounded-2xl p-6 flex flex-col justify-between border border-white/10">
                    <div class="flex flex-col gap-3 mb-6">
                        <div class="w-12 h-12 rounded-xl bg-white/20 text-white flex items-center justify-center">
                            <span class="material-symbols-outlined text-[26px]">badge</span>
                        </div>
                        <h3 class="text-xl font-bold text-white">Portal Pegawai & Dosen</h3>
                        <p class="text-xs text-on-primary-container leading-relaxed">
                            Masuk menggunakan NIP / Akun Dinas @stipjakarta.ac.id untuk dosen pengampu praktikum, verifikator SPP, BMN, dan Kepala Unit.
                        </p>
                    </div>
                    <a href="/admin" target="_blank"
                       class="inline-flex items-center justify-center gap-2 h-12 rounded-xl bg-white hover:bg-surface-container text-primary font-bold text-sm transition-colors shadow-md">
                        <span class="material-symbols-outlined text-[20px]">login</span>
                        <span>Masuk via SSO Pegawai</span>
                    </a>
                </div>

                <!-- Taruna Card -->
                <div class="bg-white/10 backdrop-blur-md rounded-2xl p-6 flex flex-col justify-between border border-white/10">
                    <div class="flex flex-col gap-3 mb-6">
                        <div class="w-12 h-12 rounded-xl bg-tertiary-fixed-dim text-primary flex items-center justify-center">
                            <span class="material-symbols-outlined text-[26px]">school</span>
                        </div>
                        <h3 class="text-xl font-bold text-white">Portal Taruna</h3>
                        <p class="text-xs text-on-primary-container leading-relaxed">
                            Akses taruna madya dan utama menggunakan Nomor Induk Taruna (NIT) untuk pengajuan praktikum mandiri dan peminjaman buku perpustakaan.
                        </p>
                    </div>
                    <a href="/student" target="_blank"
                       class="inline-flex items-center justify-center gap-2 h-12 rounded-xl bg-tertiary-fixed-dim hover:bg-gold-hover text-primary font-bold text-sm transition-colors shadow-md">
                        <span class="material-symbols-outlined text-[20px]">school</span>
                        <span>Masuk via Portal Taruna</span>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- 6. FAQ Section (Accordion) -->
    <section id="faq" x-data="{ activeFaq: null }" class="max-w-4xl mx-auto px-4 md:px-8 pb-16 w-full">
        <div class="flex flex-col items-center text-center gap-3 mb-8">
            <span class="text-xs font-bold text-secondary uppercase tracking-widest">Pusat Bantuan</span>
            <h2 class="text-2xl sm:text-3xl font-extrabold text-primary">Pertanyaan yang Sering Diajukan (FAQ)</h2>
        </div>

        <div class="flex flex-col gap-3">
            <!-- FAQ 1 -->
            <div class="bg-surface-white rounded-xl border border-border-subtle overflow-hidden">
                <button @click="activeFaq = (activeFaq === 1 ? null : 1)" type="button"
                        class="w-full px-6 py-4 flex items-center justify-between text-left font-bold text-sm sm:text-base text-primary hover:bg-canvas-bg transition-colors">
                    <span>Apakah masyarakat umum atau taruna dapat melihat nama peminjam pada jadwal publik?</span>
                    <span class="material-symbols-outlined transition-transform duration-200" :class="activeFaq === 1 ? 'rotate-180' : ''">expand_more</span>
                </button>
                <div x-show="activeFaq === 1" x-collapse class="px-6 pb-4 text-xs sm:text-sm text-text-muted leading-relaxed">
                    Sesuai PRD Bab 2.3 dan Bab 8.1 mengenai kepatuhan data pribadi maritim, halaman publik kalender jadwal laboratorium dirancang 100% anonim. Kalender hanya menampilkan kode laboratorium, nama mata kuliah, grup kelas, dan rentang jam, tanpa mempublikasikan identitas pemohon.
                </div>
            </div>

            <!-- FAQ 2 -->
            <div class="bg-surface-white rounded-xl border border-border-subtle overflow-hidden">
                <button @click="activeFaq = (activeFaq === 2 ? null : 2)" type="button"
                        class="w-full px-6 py-4 flex items-center justify-between text-left font-bold text-sm sm:text-base text-primary hover:bg-canvas-bg transition-colors">
                    <span>Bagaimana mekanisme pencegahan bentrok waktu pemakaian lab?</span>
                    <span class="material-symbols-outlined transition-transform duration-200" :class="activeFaq === 2 ? 'rotate-180' : ''">expand_more</span>
                </button>
                <div x-show="activeFaq === 2" x-collapse class="px-6 pb-4 text-xs sm:text-sm text-text-muted leading-relaxed">
                    Sistem SILT-STIP menerapkan penguncian unik di level database pada tabel <code>booking_slots</code> dengan rentang waktu 30 menitan dan prinsip First Come First Served (FCFS). Sistem menjamin tidak ada dua booking berstatus penahan slot yang dapat menempati laboratorium dan waktu yang sama.
                </div>
            </div>

            <!-- FAQ 3 -->
            <div class="bg-surface-white rounded-xl border border-border-subtle overflow-hidden">
                <button @click="activeFaq = (activeFaq === 3 ? null : 3)" type="button"
                        class="w-full px-6 py-4 flex items-center justify-between text-left font-bold text-sm sm:text-base text-primary hover:bg-canvas-bg transition-colors">
                    <span>Apakah pengajuan praktikum harus menyertakan standar IMO Model Course?</span>
                    <span class="material-symbols-outlined transition-transform duration-200" :class="activeFaq === 3 ? 'rotate-180' : ''">expand_more</span>
                </button>
                <div x-show="activeFaq === 3" x-collapse class="px-6 pb-4 text-xs sm:text-sm text-text-muted leading-relaxed">
                    Ya, seluruh peminjaman lab diwajibkan terikat dengan mata kuliah kurikulum dan minimal 1 kompetensi standar IMO STCW terkait (misal IMO Model Course 7.04 untuk Teknika atau 7.01 untuk Nautika) guna memastikan keterpenuhan jam praktik pra-prala.
                </div>
            </div>
        </div>
    </section>
</div>
