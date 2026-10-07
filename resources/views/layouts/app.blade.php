<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'SILT-STIP') | Layanan Terpadu STIP Jakarta</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500;700&display=swap" rel="stylesheet">

    <!-- Stylesheet -->
    <style>
        :root {
            --bg-deep: #050b14;
            --bg-card: rgba(13, 25, 43, 0.82);
            --bg-card-hover: rgba(19, 36, 61, 0.95);
            --border-subtle: rgba(255, 255, 255, 0.08);
            --border-focus: rgba(56, 189, 248, 0.4);
            --primary: #0284c7;
            --primary-light: #38bdf8;
            --primary-glow: rgba(2, 132, 199, 0.25);
            --gold: #f59e0b;
            --gold-light: #fbbf24;
            --gold-glow: rgba(245, 158, 11, 0.25);
            --emerald: #10b981;
            --emerald-bg: rgba(16, 185, 129, 0.15);
            --rose: #f43f5e;
            --rose-bg: rgba(244, 63, 94, 0.15);
            --amber: #f59e0b;
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
            --text-dim: #64748b;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
        }

        body {
            background-color: var(--bg-deep);
            color: var(--text-main);
            min-height: 100vh;
            background-image: 
                radial-gradient(circle at 15% 15%, rgba(2, 132, 199, 0.15) 0%, transparent 45%),
                radial-gradient(circle at 85% 75%, rgba(245, 158, 11, 0.10) 0%, transparent 45%),
                linear-gradient(180deg, #050b14 0%, #03070d 100%);
            background-attachment: fixed;
            line-height: 1.6;
            overflow-x: hidden;
            display: flex;
            flex-direction: column;
        }

        .container {
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 1.5rem;
            width: 100%;
        }

        /* Top Bar */
        .top-bar {
            background: rgba(5, 11, 20, 0.95);
            border-bottom: 1px solid var(--border-subtle);
            font-size: 0.78rem;
            color: var(--text-dim);
            padding: 0.35rem 0;
        }

        .top-bar-inner {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .top-bar a {
            color: var(--text-muted);
            text-decoration: none;
            transition: color 0.2s;
        }

        .top-bar a:hover {
            color: var(--primary-light);
        }

        /* Navbar */
        nav.main-nav {
            background: rgba(7, 16, 28, 0.88);
            backdrop-filter: blur(16px);
            border-bottom: 1px solid var(--border-subtle);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .nav-inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 4.5rem;
        }

        .brand-container {
            display: flex;
            align-items: center;
            gap: 0.85rem;
            text-decoration: none;
            color: #fff;
        }

        .brand-logo {
            width: 2.75rem;
            height: 2.75rem;
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 14px var(--gold-glow);
            font-size: 1.4rem;
        }

        .brand-title h1 {
            font-size: 1.15rem;
            font-weight: 800;
            letter-spacing: -0.01em;
            background: linear-gradient(135deg, #ffffff, #e2e8f0);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            line-height: 1.2;
        }

        .brand-title span {
            font-size: 0.68rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: var(--primary-light);
            font-weight: 600;
            display: block;
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 0.25rem;
            list-style: none;
        }

        .nav-item {
            position: relative;
        }

        .nav-link {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.55rem 0.85rem;
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--text-muted);
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.2s ease;
        }

        .nav-link:hover, .nav-link.active {
            color: #fff;
            background: rgba(255, 255, 255, 0.05);
        }

        .nav-link.active {
            color: var(--primary-light);
            background: rgba(2, 132, 199, 0.12);
        }

        .dropdown-menu {
            position: absolute;
            top: 100%;
            left: 0;
            background: #091524;
            border: 1px solid var(--border-subtle);
            border-radius: 12px;
            min-width: 230px;
            padding: 0.5rem;
            box-shadow: 0 16px 36px rgba(0, 0, 0, 0.5);
            display: none;
            z-index: 110;
        }

        .nav-item:hover .dropdown-menu {
            display: block;
        }

        .dropdown-item {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            padding: 0.6rem 0.85rem;
            font-size: 0.82rem;
            color: var(--text-muted);
            text-decoration: none;
            border-radius: 6px;
            transition: all 0.15s;
        }

        .dropdown-item:hover {
            color: #fff;
            background: rgba(255, 255, 255, 0.06);
        }

        .nav-actions {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        /* Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.55rem 1.15rem;
            border-radius: 8px;
            font-size: 0.85rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s ease;
            cursor: pointer;
            border: 1px solid transparent;
        }

        .btn-sm {
            padding: 0.35rem 0.75rem;
            font-size: 0.75rem;
            border-radius: 6px;
        }

        .btn-outline {
            background: rgba(255, 255, 255, 0.03);
            border-color: var(--border-subtle);
            color: var(--text-main);
        }

        .btn-outline:hover {
            background: rgba(255, 255, 255, 0.08);
            border-color: rgba(255, 255, 255, 0.2);
            color: #fff;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary) 0%, #0369a1 100%);
            color: #fff;
            box-shadow: 0 4px 14px var(--primary-glow);
        }

        .btn-primary:hover {
            box-shadow: 0 6px 20px rgba(2, 132, 199, 0.45);
            transform: translateY(-1px);
        }

        .btn-gold {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: #fff;
            box-shadow: 0 4px 14px var(--gold-glow);
        }

        .btn-gold:hover {
            box-shadow: 0 6px 20px rgba(245, 158, 11, 0.45);
            transform: translateY(-1px);
        }

        .btn-emerald {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: #fff;
        }

        .btn-rose {
            background: linear-gradient(135deg, #f43f5e 0%, #e11d48 100%);
            color: #fff;
        }

        /* Flash Messages */
        .flash-alerts {
            margin: 1.25rem 0 0.5rem;
        }

        .alert {
            padding: 0.85rem 1.25rem;
            border-radius: 10px;
            font-size: 0.875rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1rem;
            border: 1px solid transparent;
        }

        .alert-success {
            background: rgba(16, 185, 129, 0.12);
            border-color: rgba(16, 185, 129, 0.3);
            color: #6ee7b7;
        }

        .alert-error {
            background: rgba(244, 63, 94, 0.12);
            border-color: rgba(244, 63, 94, 0.3);
            color: #fda4af;
        }

        /* Page Layout */
        main.main-content {
            flex: 1;
            padding: 2rem 0 4rem;
        }

        /* Cards & Surfaces */
        .glass-card {
            background: var(--bg-card);
            border: 1px solid var(--border-subtle);
            border-radius: 16px;
            padding: 1.75rem;
            backdrop-filter: blur(12px);
            transition: all 0.2s ease;
        }

        .glass-card:hover {
            border-color: rgba(255, 255, 255, 0.14);
        }

        /* Badges */
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            padding: 0.25rem 0.65rem;
            font-size: 0.72rem;
            font-weight: 700;
            border-radius: 9999px;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        .badge-cyan {
            background: rgba(2, 132, 199, 0.15);
            color: var(--primary-light);
            border: 1px solid rgba(2, 132, 199, 0.3);
        }

        .badge-gold {
            background: rgba(245, 158, 11, 0.15);
            color: var(--gold-light);
            border: 1px solid rgba(245, 158, 11, 0.3);
        }

        .badge-emerald {
            background: var(--emerald-bg);
            color: #34d399;
            border: 1px solid rgba(16, 185, 129, 0.3);
        }

        .badge-rose {
            background: var(--rose-bg);
            color: #fb7185;
            border: 1px solid rgba(244, 63, 94, 0.3);
        }

        .badge-slate {
            background: rgba(148, 163, 184, 0.12);
            color: #cbd5e1;
            border: 1px solid rgba(148, 163, 184, 0.25);
        }

        /* Form Controls */
        .form-group {
            margin-bottom: 1.25rem;
        }

        .form-label {
            display: block;
            font-size: 0.82rem;
            font-weight: 600;
            color: #e2e8f0;
            margin-bottom: 0.4rem;
        }

        .form-control, .form-select {
            width: 100%;
            padding: 0.65rem 0.95rem;
            background: rgba(6, 13, 24, 0.8);
            border: 1px solid var(--border-subtle);
            border-radius: 8px;
            color: #fff;
            font-size: 0.875rem;
            transition: all 0.2s;
            outline: none;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary-light);
            box-shadow: 0 0 0 3px rgba(2, 132, 199, 0.2);
            background: rgba(8, 18, 33, 0.95);
        }

        .form-hint {
            font-size: 0.75rem;
            color: var(--text-dim);
            margin-top: 0.3rem;
        }

        /* Tables */
        .table-responsive {
            overflow-x: auto;
            border-radius: 12px;
            border: 1px solid var(--border-subtle);
        }

        table.custom-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.85rem;
            text-align: left;
        }

        table.custom-table th {
            background: rgba(10, 22, 38, 0.9);
            color: var(--text-muted);
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.72rem;
            letter-spacing: 0.05em;
            padding: 0.9rem 1.15rem;
            border-bottom: 1px solid var(--border-subtle);
        }

        table.custom-table td {
            padding: 0.95rem 1.15rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.04);
            color: #e2e8f0;
            vertical-align: middle;
        }

        table.custom-table tr:hover td {
            background: rgba(255, 255, 255, 0.02);
        }

        /* Footer */
        footer.main-footer {
            background: #040810;
            border-top: 1px solid var(--border-subtle);
            padding: 3rem 0 2rem;
            font-size: 0.82rem;
            color: var(--text-dim);
            margin-top: auto;
        }

        .footer-grid {
            display: grid;
            grid-template-columns: 2fr repeat(3, 1fr);
            gap: 2.5rem;
            margin-bottom: 2.5rem;
        }

        .footer-col h4 {
            color: #f1f5f9;
            font-size: 0.9rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .footer-col ul {
            list-style: none;
        }

        .footer-col ul li {
            margin-bottom: 0.5rem;
        }

        .footer-col a {
            color: var(--text-dim);
            text-decoration: none;
            transition: color 0.15s;
        }

        .footer-col a:hover {
            color: var(--primary-light);
        }

        .footer-bottom {
            padding-top: 1.5rem;
            border-top: 1px solid rgba(255, 255, 255, 0.04);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        @media (max-width: 900px) {
            .footer-grid {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }
            .nav-links {
                display: none;
            }
        }
    </style>
    @yield('styles')
</head>
<body>

    <!-- Top Bar -->
    <div class="top-bar">
        <div class="container top-bar-inner">
            <div>
                ⚓ <strong>Kementerian Perhubungan RI</strong> &bull; Badan Pengembangan SDM Perhubungan &bull; STIP Jakarta
            </div>
            <div style="display: flex; gap: 1rem;">
                <span>Zona Waktu: <strong>WIB (UTC+7)</strong></span>
                <span>Tahun Akademik: <strong>2026/2027</strong></span>
            </div>
        </div>
    </div>

    <!-- Main Navbar -->
    <nav class="main-nav">
        <div class="container nav-inner">
            <!-- Brand -->
            <a href="{{ route('home') }}" class="brand-container">
                <div class="brand-logo">⚓</div>
                <div class="brand-title">
                    <h1>SILT-STIP</h1>
                    <span>Sistem Informasi Layanan Terpadu</span>
                </div>
            </a>

            <!-- Navigation Links -->
            <ul class="nav-links">
                <li class="nav-item">
                    <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">
                        Beranda
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('public.schedule') }}" class="nav-link {{ request()->routeIs('public.schedule') ? 'active' : '' }}">
                        Jadwal Lab
                    </a>
                </li>

                <!-- Modul A: Lab -->
                <li class="nav-item">
                    <a href="{{ route('lab.monitoring') }}" class="nav-link {{ request()->is('layanan/lab*') ? 'active' : '' }}">
                        Lab & Simulator ▾
                    </a>
                    <div class="dropdown-menu">
                        <a href="{{ route('lab.booking') }}" class="dropdown-item">
                            📝 Ajukan Booking Baru
                        </a>
                        <a href="{{ route('lab.monitoring') }}" class="dropdown-item">
                            📊 Monitoring & Verifikasi 2-Tahap
                        </a>
                        <a href="{{ route('public.schedule') }}" class="dropdown-item">
                            📅 Kalender Ketersediaan FCFS
                        </a>
                    </div>
                </li>

                <!-- Modul B: BMN -->
                <li class="nav-item">
                    <a href="{{ route('bmn.inventory') }}" class="nav-link {{ request()->is('layanan/bmn*') ? 'active' : '' }}">
                        BMN ▾
                    </a>
                    <div class="dropdown-menu">
                        <a href="{{ route('bmn.inventory') }}" class="dropdown-item">
                            📦 Rekap Inventaris Unit
                        </a>
                        <a href="{{ route('bmn.submission') }}" class="dropdown-item">
                            ➕ Pengajuan BMN Baru
                        </a>
                        <a href="{{ route('bmn.return') }}" class="dropdown-item">
                            ↩️ Pengembalian BMN / Rusak
                        </a>
                    </div>
                </li>

                <!-- Modul C: Rumah Dinas -->
                <li class="nav-item">
                    <a href="{{ route('residence.approval') }}" class="nav-link {{ request()->is('layanan/rumah-dinas*') ? 'active' : '' }}">
                        Rumah Dinas ▾
                    </a>
                    <div class="dropdown-menu">
                        <a href="{{ route('residence.submission') }}" class="dropdown-item">
                            🏠 Permohonan Izin (SIP)
                        </a>
                        <a href="{{ route('residence.approval') }}" class="dropdown-item">
                            ✍️ Alur Persetujuan Ketua STIP
                        </a>
                    </div>
                </li>

                <!-- Modul D: Perpustakaan -->
                <li class="nav-item">
                    <a href="{{ route('library.catalog') }}" class="nav-link {{ request()->is('layanan/perpustakaan*') ? 'active' : '' }}">
                        Perpustakaan ▾
                    </a>
                    <div class="dropdown-menu">
                        <a href="{{ route('library.catalog') }}" class="dropdown-item">
                            📚 Katalog Koleksi Digital
                        </a>
                        <a href="{{ route('library.circulation') }}" class="dropdown-item">
                            🔄 Loket Sirkulasi & Denda
                        </a>
                    </div>
                </li>

                <li class="nav-item">
                    <a href="{{ route('reports.dashboard') }}" class="nav-link {{ request()->routeIs('reports.dashboard') ? 'active' : '' }}">
                        Laporan KPI
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('documents.index') }}" class="nav-link {{ request()->routeIs('documents.*') ? 'active' : '' }}">
                        Dokumen Cetak
                    </a>
                </li>
            </ul>

            <!-- Portals CTA -->
            <div class="nav-actions">
                <a href="/student" class="btn btn-sm btn-outline" title="Masuk Portal Taruna">
                    🎓 Taruna
                </a>
                <a href="/admin" class="btn btn-sm btn-gold" title="Masuk Panel Pegawai & Admin">
                    🛡️ Admin / Pegawai
                </a>
            </div>
        </div>
    </nav>

    <!-- Main Content Container -->
    <main class="main-content">
        <div class="container">
            <!-- Flash Message Alerts -->
            @if(session('success'))
                <div class="alert alert-success">
                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                        <span>✓</span>
                        <span>{{ session('success') }}</span>
                    </div>
                    <button type="button" onclick="this.parentElement.remove()" style="background: none; border: none; color: inherit; cursor: pointer; font-size: 1.1rem;">&times;</button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-error">
                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                        <span>⚠️</span>
                        <span>{{ session('error') }}</span>
                    </div>
                    <button type="button" onclick="this.parentElement.remove()" style="background: none; border: none; color: inherit; cursor: pointer; font-size: 1.1rem;">&times;</button>
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <!-- Main Footer -->
    <footer class="main-footer">
        <div class="container">
            <div class="footer-grid">
                <div>
                    <div style="display: flex; align-items: center; gap: 0.6rem; margin-bottom: 0.8rem;">
                        <span style="font-size: 1.5rem;">⚓</span>
                        <strong style="color: #fff; font-size: 1.05rem;">SILT-STIP Jakarta</strong>
                    </div>
                    <p style="line-height: 1.6; margin-bottom: 1rem;">
                        Sistem Informasi Layanan Terpadu Sekolah Tinggi Ilmu Pelayaran Jakarta mengintegrasikan 4 pilar operasional kampus: Laboratorium/Simulator SPP, Pengelolaan Barang Milik Negara (BMN), Izin Rumah Dinas, dan Perpustakaan Digital.
                    </p>
                    <p style="font-size: 0.75rem;">
                        Jl. Marunda Makmur No. 1, Cilincing, Jakarta Utara 14150 &bull; Telp. (021) 8899-xxxx
                    </p>
                </div>
                <div class="footer-col">
                    <h4>Layanan Terpadu</h4>
                    <ul>
                        <li><a href="{{ route('lab.booking') }}">Booking Simulator SPP</a></li>
                        <li><a href="{{ route('public.schedule') }}">Jadwal & Slot FCFS</a></li>
                        <li><a href="{{ route('bmn.inventory') }}">Inventaris BMN Kampus</a></li>
                        <li><a href="{{ route('residence.submission') }}">Surat Izin Rumah Dinas</a></li>
                        <li><a href="{{ route('library.catalog') }}">Perpustakaan Digital</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h4>Dokumen Resmi</h4>
                    <ul>
                        <li><a href="{{ route('documents.index') }}">Pusat Dokumen Cetak</a></li>
                        <li><a href="{{ route('documents.index', ['type' => 'booking_confirmation']) }}">Bukti Konfirmasi Booking</a></li>
                        <li><a href="{{ route('documents.index', ['type' => 'bmn_decree']) }}">Surat Penetapan BMN</a></li>
                        <li><a href="{{ route('documents.index', ['type' => 'bmn_return_receipt']) }}">Tanda Terima Kembali BMN</a></li>
                        <li><a href="{{ route('documents.index', ['type' => 'residence_permit']) }}">Surat Izin Penghuni (SIP)</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h4>Portal Akses</h4>
                    <ul>
                        <li><a href="/admin">Panel Pegawai & Dosen (/admin)</a></li>
                        <li><a href="/student">Portal Mandiri Taruna (/student)</a></li>
                        <li><a href="{{ route('reports.dashboard') }}">Executive Dashboard & KPI</a></li>
                        <li><a href="/#tentang">Standar IMO Model Course</a></li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2026 Sekolah Tinggi Ilmu Pelayaran (STIP) Jakarta. Seluruh hak cipta dilindungi.</p>
                <p>Dikembangkan untuk mematuhi regulasi STCW 1978/2010 dan Tata Kelola BMN Kemenhub.</p>
            </div>
        </div>
    </footer>

    @yield('scripts')
</body>
</html>
