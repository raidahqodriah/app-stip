<header x-data="{ mobileOpen: false }" class="fixed top-0 left-0 right-0 z-50 bg-surface-white/95 backdrop-blur-md shadow-[0_1px_8px_rgba(11,42,74,0.06)] border-b border-border-subtle">
    <div class="h-20 max-w-7xl mx-auto px-4 md:px-8 flex items-center justify-between gap-6">
        <!-- Logo & Branding -->
        <a href="{{ route('home') }}" wire:navigate class="flex items-center gap-3 group">
            <img alt="Logo STIP Jakarta" class="h-10 w-auto object-contain transition-transform group-hover:scale-105" src="https://lh3.googleusercontent.com/aida/AEtjO1WKJ7fBs2JmMjPqLdlD2nX41TZH0Wp8N4fBcbYI7nWYyUkgDCLeTyIkdJrzL9dejgU54xfmtZZ6tsRZa__Qw5Mjn_3PVDtSybqLJfP0mLpqaxk3x-kfGJtnjKVRbjFG4KjHLIjxvLOPmKi7fvzN5thjnw4qA07oWCdrH4pB2A438-Njg2S7aqHx4Z0S6xIQ7jzK2AFUjSMDKN-52-00EFC51p1kXXA1si5WyLEytqINcw" onerror="this.style.display='none'"/>
            <div class="flex flex-col">
                <span class="font-bold text-lg md:text-xl text-primary tracking-tight">SILT-STIP</span>
                <span class="text-xs text-text-muted hidden sm:inline-block">Sistem Informasi Layanan Terpadu - STIP Jakarta</span>
            </div>
        </a>

        <!-- Desktop Navigation -->
        <nav class="hidden lg:flex items-center gap-1 p-1 bg-canvas-bg rounded-xl border border-border-subtle">
            <a href="{{ route('home') }}" wire:navigate
               class="px-4 py-2 rounded-lg text-sm font-semibold transition-all duration-200 {{ request()->routeIs('home') ? 'bg-surface-container text-primary shadow-xs' : 'text-on-surface-variant hover:text-on-surface hover:bg-surface-container-high' }}">
                Beranda
            </a>
            <a href="{{ route('lab.catalog') }}" wire:navigate
               class="px-4 py-2 rounded-lg text-sm font-semibold transition-all duration-200 {{ request()->routeIs('lab.catalog*') || request()->routeIs('lab.detail*') ? 'bg-surface-container text-primary shadow-xs' : 'text-on-surface-variant hover:text-on-surface hover:bg-surface-container-high' }}">
                Lab
            </a>
            <a href="{{ route('lab.schedule') }}" wire:navigate
               class="px-4 py-2 rounded-lg text-sm font-semibold transition-all duration-200 {{ request()->routeIs('lab.schedule') ? 'bg-surface-container text-primary shadow-xs' : 'text-on-surface-variant hover:text-on-surface hover:bg-surface-container-high' }}">
                Jadwal
            </a>
            <a href="{{ route('announcements') }}" wire:navigate
               class="px-4 py-2 rounded-lg text-sm font-semibold transition-all duration-200 {{ request()->routeIs('announcements') ? 'bg-surface-container text-primary shadow-xs' : 'text-on-surface-variant hover:text-on-surface hover:bg-surface-container-high' }}">
                Pengumuman
            </a>
        </nav>

        <!-- Right Side: Auth Buttons & Mobile Menu Trigger -->
        <div class="flex items-center gap-2 sm:gap-3">
            <a href="/admin" target="_blank"
               class="hidden sm:inline-flex items-center justify-center h-10 px-4 rounded-xl font-semibold text-xs text-status-terisi border border-status-terisi/20 hover:bg-status-libur-bg transition-colors shadow-2xs">
                Masuk Pegawai
            </a>
            <a href="/student" target="_blank"
               class="inline-flex items-center justify-center h-10 px-4 rounded-xl bg-status-terisi text-white font-semibold text-xs hover:bg-navy-light transition-all shadow-xs">
                Masuk Taruna
            </a>
            <div class="w-9 h-9 rounded-full bg-primary/10 text-primary flex items-center justify-center shrink-0">
                <span class="material-symbols-outlined text-[20px]">person</span>
            </div>

            <!-- Mobile Hamburger Toggle -->
            <button @click="mobileOpen = !mobileOpen" type="button"
                    class="lg:hidden p-2 rounded-lg text-primary hover:bg-canvas-bg focus:outline-none ml-1"
                    aria-label="Buka Menu">
                <span class="material-symbols-outlined" x-text="mobileOpen ? 'close' : 'menu'">menu</span>
            </button>
        </div>
    </div>

    <!-- Mobile Navigation Drawer -->
    <div x-show="mobileOpen" x-transition.opacity.duration.200ms
         @click.away="mobileOpen = false"
         class="lg:hidden border-t border-border-subtle bg-surface-white px-4 py-4 space-y-2 shadow-lg">
        <a href="{{ route('home') }}" wire:navigate @click="mobileOpen = false"
           class="flex items-center gap-2 px-3 py-2.5 rounded-lg text-sm font-semibold {{ request()->routeIs('home') ? 'bg-surface-container text-primary' : 'text-text-muted hover:bg-canvas-bg' }}">
            <span class="material-symbols-outlined text-[20px]">home</span>
            Beranda
        </a>
        <a href="{{ route('lab.catalog') }}" wire:navigate @click="mobileOpen = false"
           class="flex items-center gap-2 px-3 py-2.5 rounded-lg text-sm font-semibold {{ request()->routeIs('lab.catalog*') ? 'bg-surface-container text-primary' : 'text-text-muted hover:bg-canvas-bg' }}">
            <span class="material-symbols-outlined text-[20px]">precision_manufacturing</span>
            Lab
        </a>
        <a href="{{ route('lab.schedule') }}" wire:navigate @click="mobileOpen = false"
           class="flex items-center gap-2 px-3 py-2.5 rounded-lg text-sm font-semibold {{ request()->routeIs('lab.schedule') ? 'bg-surface-container text-primary' : 'text-text-muted hover:bg-canvas-bg' }}">
            <span class="material-symbols-outlined text-[20px]">calendar_month</span>
            Jadwal
        </a>
        <a href="{{ route('announcements') }}" wire:navigate @click="mobileOpen = false"
           class="flex items-center gap-2 px-3 py-2.5 rounded-lg text-sm font-semibold {{ request()->routeIs('announcements') ? 'bg-surface-container text-primary' : 'text-text-muted hover:bg-canvas-bg' }}">
            <span class="material-symbols-outlined text-[20px]">campaign</span>
            Pengumuman
        </a>
        <div class="pt-2 border-t border-border-subtle flex flex-col gap-2">
            <a href="/admin" target="_blank" class="w-full text-center py-2.5 rounded-lg text-xs font-semibold text-status-terisi bg-canvas-bg border border-border-subtle">
                Masuk Pegawai / Dosen
            </a>
            <a href="/student" target="_blank" class="w-full text-center py-2.5 rounded-lg text-xs font-semibold text-white bg-status-terisi">
                Masuk Taruna (NIT)
            </a>
        </div>
    </div>
</header>
