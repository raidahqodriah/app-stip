<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SIM-STIP Terpadu | Sekolah Tinggi Ilmu Pelayaran Jakarta</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #0f2b48;
            --primary-dark: #0a1b2e;
            --accent: #d97706;
            --accent-light: #fbbf24;
            --cyan: #0284c7;
            --cyan-light: #38bdf8;
            --bg: #07111e;
            --card-bg: rgba(15, 33, 56, 0.7);
            --border: rgba(255, 255, 255, 0.08);
            --text-main: #f1f5f9;
            --text-muted: #94a3b8;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        body {
            background-color: var(--bg);
            color: var(--text-main);
            min-height: 100vh;
            background-image: 
                radial-gradient(circle at 10% 20%, rgba(2, 132, 199, 0.15) 0%, transparent 40%),
                radial-gradient(circle at 90% 80%, rgba(217, 119, 6, 0.12) 0%, transparent 40%),
                linear-gradient(180deg, #07111e 0%, #040810 100%);
            background-attachment: fixed;
            line-height: 1.6;
            overflow-x: hidden;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1.5rem;
        }

        /* Navbar */
        nav {
            border-bottom: 1px solid var(--border);
            backdrop-filter: blur(12px);
            position: sticky;
            top: 0;
            z-index: 50;
            background: rgba(7, 17, 30, 0.85);
        }

        .nav-inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 4.5rem;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            text-decoration: none;
            color: #fff;
        }

        .brand-badge {
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: #fff;
            font-weight: 800;
            font-size: 1rem;
            padding: 0.4rem 0.75rem;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(217, 119, 6, 0.3);
            letter-spacing: 0.05em;
        }

        .brand-text h1 {
            font-size: 1.15rem;
            font-weight: 700;
            line-height: 1.2;
        }

        .brand-text p {
            font-size: 0.72rem;
            color: var(--cyan-light);
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.6rem 1.25rem;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.875rem;
            text-decoration: none;
            transition: all 0.2s ease;
            cursor: pointer;
            border: none;
        }

        .btn-outline {
            border: 1px solid var(--border);
            color: var(--text-main);
            background: rgba(255, 255, 255, 0.03);
        }

        .btn-outline:hover {
            background: rgba(255, 255, 255, 0.08);
            border-color: rgba(255, 255, 255, 0.2);
        }

        .btn-gold {
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: #fff;
            box-shadow: 0 4px 15px rgba(217, 119, 6, 0.3);
        }

        .btn-gold:hover {
            box-shadow: 0 6px 20px rgba(217, 119, 6, 0.5);
            transform: translateY(-1px);
        }

        .btn-cyan {
            background: linear-gradient(135deg, #0284c7, #0369a1);
            color: #fff;
            box-shadow: 0 4px 15px rgba(2, 132, 199, 0.3);
        }

        .btn-cyan:hover {
            box-shadow: 0 6px 20px rgba(2, 132, 199, 0.5);
            transform: translateY(-1px);
        }

        /* Hero */
        .hero {
            padding: 4.5rem 0 3rem;
            text-align: center;
        }

        .hero-pill {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.35rem 1rem;
            background: rgba(2, 132, 199, 0.12);
            border: 1px solid rgba(2, 132, 199, 0.3);
            border-radius: 9999px;
            color: var(--cyan-light);
            font-size: 0.8rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
        }

        .hero h2 {
            font-size: 2.75rem;
            font-weight: 800;
            line-height: 1.2;
            margin-bottom: 1.25rem;
            background: linear-gradient(135deg, #ffffff 40%, #cbd5e1 70%, #94a3b8 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .hero p {
            font-size: 1.1rem;
            color: var(--text-muted);
            max-width: 720px;
            margin: 0 auto 2.5rem;
        }

        /* Portal Cards */
        .portal-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 1.5rem;
            margin-bottom: 3.5rem;
        }

        .portal-card {
            background: var(--card-bg);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 2rem;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
            overflow: hidden;
            backdrop-filter: blur(10px);
            transition: all 0.25s ease;
        }

        .portal-card:hover {
            border-color: rgba(255, 255, 255, 0.2);
            transform: translateY(-4px);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.4);
        }

        .portal-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
        }

        .portal-card.admin::before {
            background: linear-gradient(90deg, #f59e0b, #d97706);
        }

        .portal-card.student::before {
            background: linear-gradient(90deg, #38bdf8, #0284c7);
        }

        .card-icon {
            width: 3.25rem;
            height: 3.25rem;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.6rem;
            margin-bottom: 1.25rem;
        }

        .portal-card.admin .card-icon {
            background: rgba(217, 119, 6, 0.15);
            color: var(--accent-light);
            border: 1px solid rgba(217, 119, 6, 0.3);
        }

        .portal-card.student .card-icon {
            background: rgba(2, 132, 199, 0.15);
            color: var(--cyan-light);
            border: 1px solid rgba(2, 132, 199, 0.3);
        }

        .card-title {
            font-size: 1.35rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .card-desc {
            color: var(--text-muted);
            font-size: 0.9rem;
            margin-bottom: 1.25rem;
            flex-grow: 1;
        }

        .feature-list {
            list-style: none;
            margin-bottom: 1.75rem;
            font-size: 0.85rem;
            color: #cbd5e1;
        }

        .feature-list li {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 0.4rem;
        }

        .feature-list li span.dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: var(--accent);
        }

        .portal-card.student .feature-list li span.dot {
            background: var(--cyan);
        }

        /* Stats Bar */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1rem;
            margin-bottom: 4rem;
            background: rgba(15, 33, 56, 0.4);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 1.5rem;
        }

        .stat-item {
            text-align: center;
        }

        .stat-number {
            font-size: 2rem;
            font-weight: 800;
            color: #fff;
        }

        .stat-label {
            font-size: 0.8rem;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        /* Section Title */
        .section-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.5rem;
        }

        .section-header h3 {
            font-size: 1.5rem;
            font-weight: 700;
        }

        /* Schedule Table */
        .schedule-card {
            background: var(--card-bg);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 1.5rem;
            margin-bottom: 4rem;
            backdrop-filter: blur(10px);
        }

        .table-responsive {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.875rem;
            text-align: left;
        }

        th {
            padding: 0.75rem 1rem;
            background: rgba(255, 255, 255, 0.03);
            color: var(--text-muted);
            font-weight: 600;
            border-bottom: 1px solid var(--border);
        }

        td {
            padding: 0.875rem 1rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.04);
            color: #e2e8f0;
        }

        tr:last-child td {
            border-bottom: none;
        }

        .badge-status {
            display: inline-block;
            padding: 0.2rem 0.6rem;
            border-radius: 9999px;
            font-size: 0.72rem;
            font-weight: 700;
            text-transform: uppercase;
        }

        .badge-approved {
            background: rgba(16, 185, 129, 0.2);
            color: #34d399;
            border: 1px solid rgba(16, 185, 129, 0.4);
        }

        .badge-submitted {
            background: rgba(245, 158, 11, 0.2);
            color: #fbbf24;
            border: 1px solid rgba(245, 158, 11, 0.4);
        }

        .badge-completed {
            background: rgba(59, 130, 246, 0.2);
            color: #60a5fa;
            border: 1px solid rgba(59, 130, 246, 0.4);
        }

        /* Demo credentials section */
        .demo-box {
            background: rgba(217, 119, 6, 0.08);
            border: 1px dashed rgba(217, 119, 6, 0.35);
            border-radius: 12px;
            padding: 1.25rem 1.5rem;
            margin-bottom: 3rem;
            font-size: 0.85rem;
        }

        .demo-box h4 {
            color: var(--accent-light);
            font-weight: 700;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .demo-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 1rem;
            margin-top: 0.75rem;
        }

        .demo-badge {
            background: rgba(0, 0, 0, 0.25);
            padding: 0.5rem 0.75rem;
            border-radius: 6px;
            border: 1px solid rgba(255, 255, 255, 0.05);
        }

        .demo-badge code {
            color: var(--cyan-light);
            font-family: monospace;
            font-size: 0.85rem;
        }

        /* Footer */
        footer {
            border-top: 1px solid var(--border);
            padding: 2.5rem 0;
            text-align: center;
            font-size: 0.82rem;
            color: var(--text-muted);
        }

        @media (max-width: 768px) {
            .hero h2 {
                font-size: 2rem;
            }
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
    </style>
</head>
<body>

    <!-- Header Navigation -->
    <nav>
        <div class="container nav-inner">
            <a href="/" class="brand">
                <span class="brand-badge">STIP</span>
                <div class="brand-text">
                    <h1>SIM-STIP TERPADU</h1>
                    <p>Kementerian Perhubungan RI</p>
                </div>
            </a>
            <div class="nav-links">
                <a href="#jadwal" class="btn btn-outline">Jadwal Lab</a>
                <a href="/student" class="btn btn-cyan">Portal Taruna</a>
                <a href="/admin" class="btn btn-gold">Portal Pegawai</a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="container">
        <!-- Hero Section -->
        <section class="hero">
            <div class="hero-pill">
                ⚓ Sistem Informasi Pelayanan Terpadu STIP Jakarta
            </div>
            <h2>Integrasi Pelayanan Simulator, Aset BMN,<br>& Perpustakaan Maritim</h2>
            <p>
                Platform operasional terpadu Sekolah Tinggi Ilmu Pelayaran Jakarta untuk manajemen reservasi laboratorium & simulator berstandar STCW, tata kelola BMN & SIP Rumah Dinas, serta layanan sirkulasi perpustakaan.
            </p>
        </section>

        <!-- Quick Access Portals -->
        <section class="portal-grid">
            <!-- Card 1: Admin & Pegawai -->
            <div class="portal-card admin">
                <div>
                    <div class="card-icon">🏛️</div>
                    <h3 class="card-title">Panel Pegawai & Manajemen</h3>
                    <p class="card-desc">
                        Akses operasional untuk Dosen, Instruktur Lab, Kasie Lab, Ka. PPK, Pengurus RT, Bagian BMN, Pustakawan, dan Ketua STIP.
                    </p>
                    <ul class="feature-list">
                        <li><span class="dot"></span> Verifikasi Berjenjang Jadwal Simulator (Kasie & Ka. PPK)</li>
                        <li><span class="dot"></span> Penetapan Status Penggunaan BMN & SK PSP</li>
                        <li><span class="dot"></span> Verifikasi SIP Rumah Dinas STIP Marunda</li>
                        <li><span class="dot"></span> Manajemen Sirkulasi Buku & Perhitungan Denda Otomatis</li>
                    </ul>
                </div>
                <div>
                    <a href="/admin" class="btn btn-gold" style="width: 100%; justify-content: center;">
                        Masuk Panel Pegawai (Admin) &rarr;
                    </a>
                </div>
            </div>

            <!-- Card 2: Student & Taruna -->
            <div class="portal-card student">
                <div>
                    <div class="card-icon">⚓</div>
                    <h3 class="card-title">Panel Taruna / Taruni</h3>
                    <p class="card-desc">
                        Layanan mandiri (Self-Service) bagi Taruna STIP untuk praktikum laboratorium, katalog pustaka, dan pemantauan riwayat peminjaman.
                    </p>
                    <ul class="feature-list">
                        <li><span class="dot"></span> Reservasi Praktik Mandiri Simulator & Bengkel</li>
                        <li><span class="dot"></span> Pemantauan Timeline Status Verifikasi Pengajuan</li>
                        <li><span class="dot"></span> Eksplorasi Katalog Buku & Modul Maritim STIP</li>
                        <li><span class="dot"></span> Informasi Tenggat Waktu & Rekap Status Peminjaman</li>
                    </ul>
                </div>
                <div>
                    <a href="/student" class="btn btn-cyan" style="width: 100%; justify-content: center;">
                        Masuk Panel Taruna (Student) &rarr;
                    </a>
                </div>
            </div>
        </section>

        <!-- Demonstration Account Badges -->
        <div class="demo-box">
            <h4>💡 Akun Uji Coba Terdaftar (Seeded Accounts)</h4>
            <div class="demo-grid">
                <div class="demo-badge">
                    <strong>Pegawai / Administrator:</strong><br>
                    Email: <code>admin@stipjakarta.ac.id</code><br>
                    Password: <code>password</code>
                </div>
                <div class="demo-badge">
                    <strong>Taruna (Nautika / Teknika / KALK):</strong><br>
                    Email: <code>taruna.teknika1@student.stipjakarta.ac.id</code><br>
                    Password: <code>password</code>
                </div>
            </div>
        </div>

        <!-- System Summary Statistics -->
        <section class="stats-grid">
            <div class="stat-item">
                <div class="stat-number">{{ $stats['rooms'] ?? 0 }}</div>
                <div class="stat-label">Lab & Simulator</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">{{ $stats['bookings'] ?? 0 }}</div>
                <div class="stat-label">Jadwal Praktik</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">{{ $stats['books'] ?? 0 }}</div>
                <div class="stat-label">Judul Buku Pustaka</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">{{ $stats['bmn'] ?? 0 }}</div>
                <div class="stat-label">Item Aset BMN</div>
            </div>
        </section>

        <!-- Public Lab Occupancy Schedule -->
        <section id="jadwal" class="schedule-card">
            <div class="section-header">
                <div>
                    <h3>📅 Jadwal & Okupansi Lab Terkini</h3>
                    <p style="color: var(--text-muted); font-size: 0.85rem;">
                        Transparansi penggunaan ruang simulator & laboratorium STIP Jakarta secara real-time
                    </p>
                </div>
                <span class="hero-pill" style="margin-bottom: 0;">Status Publik</span>
            </div>

            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal & Waktu</th>
                            <th>Laboratorium / Simulator</th>
                            <th>Mata Kuliah / Kegiatan</th>
                            <th>Pemohon / Instruktur</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentBookings as $index => $booking)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    <strong>{{ \Carbon\Carbon::parse($booking->start_at)->translatedFormat('d M Y') }}</strong><br>
                                    <small style="color: var(--text-muted);">
                                        {{ \Carbon\Carbon::parse($booking->start_at)->format('H:i') }} - {{ \Carbon\Carbon::parse($booking->end_at)->format('H:i') }} WIB
                                    </small>
                                </td>
                                <td>
                                    <strong>{{ $booking->room->name ?? '-' }}</strong><br>
                                    <small style="color: var(--cyan-light);">Kode: {{ $booking->room->code ?? '-' }}</small>
                                </td>
                                <td>
                                    {{ $booking->subject->name ?? ($booking->purpose ?? 'Praktikum Mandiri') }}
                                </td>
                                <td>
                                    {{ $booking->requester->name ?? 'Taruna / Dosen' }}
                                </td>
                                <td>
                                    @if($booking->status->value === 'approved')
                                        <span class="badge-status badge-approved">{{ $booking->status->label() }}</span>
                                    @elseif($booking->status->value === 'completed')
                                        <span class="badge-status badge-completed">{{ $booking->status->label() }}</span>
                                    @else
                                        <span class="badge-status badge-submitted">{{ $booking->status->label() }}</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" style="text-align: center; color: var(--text-muted); padding: 2rem;">
                                    Belum ada jadwal praktik aktif untuk hari ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer>
        <div class="container">
            <p>&copy; {{ date('Y') }} Sekolah Tinggi Ilmu Pelayaran (STIP) Jakarta. Badan Pengembangan SDM Perhubungan.</p>
            <p style="margin-top: 0.25rem; color: #64748b;">
                Didukung oleh Filament v5 & Laravel 12. Sistem Terpadu Layanan Simulator, BMN & Rumah Dinas, serta Perpustakaan.
            </p>
        </div>
    </footer>

</body>
</html>
