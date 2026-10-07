<footer class="w-full bg-surface-white mt-16 border-t border-border-subtle">
    <div class="max-w-7xl mx-auto px-4 md:px-8 py-12">
        <div class="grid grid-cols-1 md:grid-cols-12 gap-8 pb-10 border-b border-border-subtle">
            <!-- Col 1: About & Campus info -->
            <div class="md:col-span-5 flex flex-col gap-4">
                <div class="flex items-center gap-2">
                    <div class="w-3 h-3 rounded-full bg-secondary"></div>
                    <span class="font-bold text-lg text-primary tracking-tight">SILT-STIP JAKARTA</span>
                </div>
                <p class="text-sm text-text-muted leading-relaxed">
                    Sistem Informasi Layanan Terpadu Sekolah Tinggi Ilmu Pelayaran (STIP) Jakarta. Pintu gerbang utama manajemen operasional laboratorium, simulator pelayaran berstandar IMO STCW, pengelolaan BMN, dan fasilitas akademis mariner terpadu.
                </p>
                <div class="flex flex-col gap-2 text-xs text-text-muted">
                    <div class="flex items-start gap-2">
                        <span class="material-symbols-outlined text-secondary text-[18px] shrink-0 mt-0.5">location_on</span>
                        <span>Jl. Marunda Makmur, Cilincing, Jakarta Utara, DKI Jakarta 14150</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-secondary text-[18px] shrink-0">mail</span>
                        <span>silt@stipjakarta.ac.id / info@stipjakarta.ac.id</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-secondary text-[18px] shrink-0">call</span>
                        <span>(021) 8899-0000 / ext. 142 (Unit TI STIP)</span>
                    </div>
                </div>
            </div>

            <!-- Col 2: Fast Links -->
            <div class="md:col-span-3 md:col-start-7 flex flex-col gap-3">
                <span class="font-bold text-sm text-primary uppercase tracking-wider">Layanan Terpadu</span>
                <div class="flex flex-col gap-2 text-sm text-text-muted">
                    <a href="{{ route('lab.catalog') }}" wire:navigate class="hover:text-secondary transition-colors">Katalog Lab & Simulator</a>
                    <a href="{{ route('lab.schedule') }}" wire:navigate class="hover:text-secondary transition-colors">Jadwal Real-Time Lab</a>
                    <a href="{{ route('announcements') }}" wire:navigate class="hover:text-secondary transition-colors">Pusat Pengumuman & Blackout</a>
                    <a href="{{ route('home') }}#faq" class="hover:text-secondary transition-colors">Panduan & FAQ Layanan</a>
                </div>
            </div>

            <!-- Col 3: Portal Access -->
            <div class="md:col-span-3 flex flex-col gap-3">
                <span class="font-bold text-sm text-primary uppercase tracking-wider">Akses Kedinasan</span>
                <div class="flex flex-col gap-2 text-sm text-text-muted">
                    <a href="/admin" target="_blank" class="hover:text-secondary transition-colors flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-[16px]">badge</span>
                        Portal Pegawai & Dosen (SSO)
                    </a>
                    <a href="/student" target="_blank" class="hover:text-secondary transition-colors flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-[16px]">school</span>
                        Portal Taruna (NIT / NRP)
                    </a>
                    <span class="text-xs text-text-muted/80 mt-2">
                        Operasional Layanan: Senin – Jumat 07.30 – 16.00 WIB
                    </span>
                </div>
            </div>
        </div>

        <div class="pt-6 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-text-muted">
            <p class="text-center sm:text-left">
                © {{ date('Y') }} Sekolah Tinggi Ilmu Pelayaran Jakarta. Hak Cipta Dilindungi.
            </p>
            <div class="flex items-center gap-2">
                <span class="w-1.5 h-1.5 rounded-full bg-status-tersedia"></span>
                <span class="uppercase tracking-wider font-semibold text-[10px]">Kementerian Perhubungan Republik Indonesia</span>
            </div>
        </div>
    </div>
</footer>
