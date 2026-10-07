@extends('layouts.app')

@section('title', 'Dashboard Eksekutif & Laporan Terpadu')

@section('content')
<div style="margin-bottom: 2rem;">
    <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 1rem;">
        <div>
            <div style="display: inline-flex; align-items: center; gap: 0.5rem; color: var(--gold-light); font-size: 0.8rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.5rem;">
                <span>📊 PUSAT LAPORAN & MONITORING EKSEKUTIF</span> &bull; <span>SILT-STIP JAKARTA</span>
            </div>
            <h1 style="font-size: 1.85rem; font-weight: 800; color: #fff;">
                Dashboard Indikator Kinerja Utama (KPI)
            </h1>
            <p style="color: var(--text-muted); font-size: 0.92rem; max-width: 650px;">
                Konsolidasi metrik operasional terpadu: Utilisasi Lab (FR, JLH, DRS), Pengelolaan Aset BMN, Rumah Dinas, dan Sirkulasi Perpustakaan (§8).
            </p>
        </div>

        <div style="display: flex; gap: 0.75rem;">
            <button onclick="window.print()" class="btn btn-outline">
                🖨️ Cetak Ringkasan
            </button>
            <a href="#" class="btn btn-gold" onclick="alert('Laporan siap diekspor dalam format REKAP UTILISASI v3 (.xlsx)'); return false;">
                📥 Ekspor Rekapitulasi (.xlsx)
            </a>
        </div>
    </div>
</div>

<!-- Period Filter -->
<div class="glass-card" style="margin-bottom: 2rem; padding: 1rem 1.25rem;">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
        <div style="font-size: 0.85rem; font-weight: 600; color: #fff;">
            Periode Analisis: <strong style="color: var(--gold-light);">Bulan Berjalan (Oktober 2026)</strong>
        </div>
        <div style="display: flex; gap: 0.5rem;">
            <a href="{{ route('reports.dashboard', ['period' => 'day']) }}" class="btn btn-sm {{ $period === 'day' ? 'btn-primary' : 'btn-outline' }}">Harian</a>
            <a href="{{ route('reports.dashboard', ['period' => 'month']) }}" class="btn btn-sm {{ $period === 'month' ? 'btn-primary' : 'btn-outline' }}">Bulanan</a>
            <a href="{{ route('reports.dashboard', ['period' => 'year']) }}" class="btn btn-sm {{ $period === 'year' ? 'btn-primary' : 'btn-outline' }}">Tahunan</a>
        </div>
    </div>
</div>

<!-- Section 1: Lab Metrics (Kosakata REKAP UTILISASI: FR, JLH, DRS, JF/JP) -->
<div style="margin-bottom: 2rem;">
    <div style="display: flex; align-items: center; gap: 0.6rem; margin-bottom: 1rem;">
        <span style="font-size: 1.3rem;">⚓</span>
        <h2 style="font-size: 1.25rem; font-weight: 700; color: #fff;">Pilar A: Metrik Utilisasi Laboratorium & Simulator SPP</h2>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(210px, 1fr)); gap: 1rem;">
        <div class="glass-card" style="border-left: 4px solid var(--primary-light);">
            <div style="font-size: 0.72rem; color: var(--text-dim); text-transform: uppercase;">FR (Frekuensi Sesi)</div>
            <div style="font-size: 1.85rem; font-weight: 800; color: #fff; margin-top: 0.2rem;">
                {{ $frCount }} <span style="font-size: 0.85rem; font-weight: 500; color: var(--text-muted);">Sesi Terlaksana</span>
            </div>
            <div style="font-size: 0.75rem; color: var(--text-dim); margin-top: 0.35rem;">Status completed (realisasi)</div>
        </div>

        <div class="glass-card" style="border-left: 4px solid var(--gold-light);">
            <div style="font-size: 0.72rem; color: var(--text-dim); text-transform: uppercase;">JLH (Total Peserta Aktual)</div>
            <div style="font-size: 1.85rem; font-weight: 800; color: var(--gold-light); margin-top: 0.2rem;">
                {{ $jlhTotal }} <span style="font-size: 0.85rem; font-weight: 500; color: var(--text-muted);">Taruna</span>
            </div>
            <div style="font-size: 0.75rem; color: var(--text-dim); margin-top: 0.35rem;">Berdasarkan absensi praktikum</div>
        </div>

        <div class="glass-card" style="border-left: 4px solid #34d399;">
            <div style="font-size: 0.72rem; color: var(--text-dim); text-transform: uppercase;">DRS (Total Durasi Jam)</div>
            <div style="font-size: 1.85rem; font-weight: 800; color: #34d399; margin-top: 0.2rem;">
                {{ $drsHours }} <span style="font-size: 0.85rem; font-weight: 500; color: var(--text-muted);">Jam Efektif</span>
            </div>
            <div style="font-size: 0.75rem; color: var(--text-dim); margin-top: 0.35rem;">Waktu operasional aktual</div>
        </div>

        <div class="glass-card" style="border-left: 4px solid #a78bfa;">
            <div style="font-size: 0.72rem; color: var(--text-dim); text-transform: uppercase;">Tingkat Utilisasi Lab (%)</div>
            <div style="font-size: 1.85rem; font-weight: 800; color: #a78bfa; margin-top: 0.2rem;">
                {{ $utilizationRate }}%
            </div>
            <div style="font-size: 0.75rem; color: var(--text-dim); margin-top: 0.35rem;">Rasio jam terpakai vs kapasitas</div>
        </div>

        <div class="glass-card" style="border-left: 4px solid #38bdf8;">
            <div style="font-size: 0.72rem; color: var(--text-dim); text-transform: uppercase;">Cakupan IMO Course</div>
            <div style="font-size: 1.85rem; font-weight: 800; color: #38bdf8; margin-top: 0.2rem;">
                {{ $imoCoverageRate }}%
            </div>
            <div style="font-size: 0.75rem; color: var(--text-dim); margin-top: 0.35rem;">Standar STCW terpenuhi</div>
        </div>
    </div>
</div>

<!-- Section 2: BMN & Rumah Dinas -->
<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-bottom: 2rem;">
    <!-- BMN -->
    <div>
        <div style="display: flex; align-items: center; gap: 0.6rem; margin-bottom: 1rem;">
            <span style="font-size: 1.3rem;">📦</span>
            <h2 style="font-size: 1.25rem; font-weight: 700; color: #fff;">Pilar B: Inventarisasi BMN</h2>
        </div>

        <div class="glass-card" style="height: calc(100% - 2.5rem);">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.5rem;">
                <div style="background: rgba(255, 255, 255, 0.03); padding: 1rem; border-radius: 8px;">
                    <div style="font-size: 0.72rem; color: var(--text-dim);">Total Aset Terdata</div>
                    <div style="font-size: 1.4rem; font-weight: 800; color: #fff;">{{ $totalBmn }} Unit</div>
                </div>
                <div style="background: rgba(255, 255, 255, 0.03); padding: 1rem; border-radius: 8px;">
                    <div style="font-size: 0.72rem; color: var(--text-dim);">Aset Aktif / Normal</div>
                    <div style="font-size: 1.4rem; font-weight: 800; color: #34d399;">{{ $activeBmn }} Unit</div>
                </div>
                <div style="background: rgba(255, 255, 255, 0.03); padding: 1rem; border-radius: 8px;">
                    <div style="font-size: 0.72rem; color: var(--text-dim);">Barang Kondisi Rusak</div>
                    <div style="font-size: 1.4rem; font-weight: 800; color: #fb7185;">{{ $damagedBmn }} Unit</div>
                </div>
                <div style="background: rgba(255, 255, 255, 0.03); padding: 1rem; border-radius: 8px;">
                    <div style="font-size: 0.72rem; color: var(--text-dim);">Menunggu Verifikasi</div>
                    <div style="font-size: 1.4rem; font-weight: 800; color: var(--gold-light);">{{ $pendingSubmissions + $pendingReturns }} Tiket</div>
                </div>
            </div>

            <a href="{{ route('bmn.inventory') }}" class="btn btn-sm btn-outline" style="width: 100%;">
                Buka Rekap Inventaris BMN &rarr;
            </a>
        </div>
    </div>

    <!-- Rumah Dinas -->
    <div>
        <div style="display: flex; align-items: center; gap: 0.6rem; margin-bottom: 1rem;">
            <span style="font-size: 1.3rem;">🏠</span>
            <h2 style="font-size: 1.25rem; font-weight: 700; color: #fff;">Pilar C: Hunian Rumah Dinas</h2>
        </div>

        <div class="glass-card" style="height: calc(100% - 2.5rem);">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.5rem;">
                <div style="background: rgba(255, 255, 255, 0.03); padding: 1rem; border-radius: 8px;">
                    <div style="font-size: 0.72rem; color: var(--text-dim);">Total Kapasitas Unit</div>
                    <div style="font-size: 1.4rem; font-weight: 800; color: #fff;">{{ $totalHouses }} Rumah</div>
                </div>
                <div style="background: rgba(255, 255, 255, 0.03); padding: 1rem; border-radius: 8px;">
                    <div style="font-size: 0.72rem; color: var(--text-dim);">Tingkat Keterisian (Occupancy)</div>
                    <div style="font-size: 1.4rem; font-weight: 800; color: var(--primary-light);">{{ $occupancyRate }}%</div>
                </div>
                <div style="background: rgba(255, 255, 255, 0.03); padding: 1rem; border-radius: 8px;">
                    <div style="font-size: 0.72rem; color: var(--text-dim);">Pemeriksaan Petugas RT</div>
                    <div style="font-size: 1.4rem; font-weight: 800; color: var(--gold-light);">{{ $pendingPermits }} Pengajuan</div>
                </div>
                <div style="background: rgba(255, 255, 255, 0.03); padding: 1rem; border-radius: 8px;">
                    <div style="font-size: 0.72rem; color: var(--text-dim);">Menunggu Ketua STIP</div>
                    <div style="font-size: 1.4rem; font-weight: 800; color: #38bdf8;">{{ $verifiedPermits }} Berkas</div>
                </div>
            </div>

            <a href="{{ route('residence.approval') }}" class="btn btn-sm btn-outline" style="width: 100%;">
                Buka Lembar Persetujuan SIP &rarr;
            </a>
        </div>
    </div>
</div>

<!-- Section 3: Library Metrics -->
<div style="margin-bottom: 2rem;">
    <div style="display: flex; align-items: center; gap: 0.6rem; margin-bottom: 1rem;">
        <span style="font-size: 1.3rem;">📚</span>
        <h2 style="font-size: 1.25rem; font-weight: 700; color: #fff;">Pilar D: Sirkulasi & Koleksi Perpustakaan</h2>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1rem;">
        <div class="glass-card">
            <div style="font-size: 0.72rem; color: var(--text-dim);">Koleksi Judul & Stok</div>
            <div style="font-size: 1.5rem; font-weight: 800; color: #fff; margin-top: 0.2rem;">
                {{ $totalTitles }} Judul
            </div>
            <div style="font-size: 0.75rem; color: var(--text-muted); margin-top: 0.2rem;">
                Total: {{ $totalStock }} &bull; Tersedia: <strong style="color: #34d399;">{{ $availableStock }}</strong> eks
            </div>
        </div>

        <div class="glass-card">
            <div style="font-size: 0.72rem; color: var(--text-dim);">Peminjaman Berjalan</div>
            <div style="font-size: 1.5rem; font-weight: 800; color: var(--primary-light); margin-top: 0.2rem;">
                {{ $activeLoans }} Eksemplar
            </div>
            <div style="font-size: 0.75rem; color: #fb7185; margin-top: 0.2rem;">
                Terlambat: <strong>{{ $overdueLoans }}</strong> transaksi
            </div>
        </div>

        <div class="glass-card">
            <div style="font-size: 0.72rem; color: var(--text-dim);">Denda Terlunasi (Kas STIP)</div>
            <div style="font-size: 1.5rem; font-weight: 800; color: #34d399; margin-top: 0.2rem;">
                Rp {{ number_format($totalFineCollected, 0, ',', '.') }}
            </div>
            <div style="font-size: 0.75rem; color: var(--text-dim); margin-top: 0.2rem;">
                Telah dibayar di loket
            </div>
        </div>

        <div class="glass-card">
            <div style="font-size: 0.72rem; color: var(--text-dim);">Denda Tertunggak (Blokir)</div>
            <div style="font-size: 1.5rem; font-weight: 800; color: var(--gold-light); margin-top: 0.2rem;">
                Rp {{ number_format($totalFinePending, 0, ',', '.') }}
            </div>
            <div style="font-size: 0.75rem; color: var(--text-dim); margin-top: 0.2rem;">
                Menunggu pengembalian buku
            </div>
        </div>
    </div>
</div>

<!-- Section 4: Recent Integrated Activity Stream -->
<div class="glass-card">
    <h3 style="font-size: 1.15rem; font-weight: 700; color: #fff; margin-bottom: 1.25rem;">
        ⚡ Arus Aktivitas Lintas Modul Terkini
    </h3>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.5rem;">
        <div>
            <h4 style="font-size: 0.88rem; font-weight: 700; color: var(--primary-light); margin-bottom: 0.75rem;">
                Sesi Lab Terakhir
            </h4>
            <div style="display: flex; flex-direction: column; gap: 0.5rem; font-size: 0.8rem;">
                @foreach($recentBookings as $rb)
                    <div style="background: rgba(255, 255, 255, 0.03); padding: 0.6rem 0.8rem; border-radius: 6px;">
                        <div style="font-weight: 700; color: #fff;">{{ $rb->room?->code }} - {{ $rb->subject?->name }}</div>
                        <div style="font-size: 0.72rem; color: var(--text-dim);">{{ $rb->start_at->isoFormat('D MMM Y, H:i') }} &bull; {{ $rb->status->label() }}</div>
                    </div>
                @endforeach
            </div>
        </div>

        <div>
            <h4 style="font-size: 0.88rem; font-weight: 700; color: var(--gold-light); margin-bottom: 0.75rem;">
                Mutasi BMN Terakhir
            </h4>
            <div style="display: flex; flex-direction: column; gap: 0.5rem; font-size: 0.8rem;">
                @foreach($recentSubmissions as $rs)
                    <div style="background: rgba(255, 255, 255, 0.03); padding: 0.6rem 0.8rem; border-radius: 6px;">
                        <div style="font-weight: 700; color: #fff;">{{ $rs->item_name }}</div>
                        <div style="font-size: 0.72rem; color: var(--text-dim);">{{ $rs->unit?->name }} &bull; Qty: {{ $rs->quantity }} unit</div>
                    </div>
                @endforeach
            </div>
        </div>

        <div>
            <h4 style="font-size: 0.88rem; font-weight: 700; color: #34d399; margin-bottom: 0.75rem;">
                Sirkulasi Buku Terakhir
            </h4>
            <div style="display: flex; flex-direction: column; gap: 0.5rem; font-size: 0.8rem;">
                @foreach($recentCirculations as $rc)
                    <div style="background: rgba(255, 255, 255, 0.03); padding: 0.6rem 0.8rem; border-radius: 6px;">
                        <div style="font-weight: 700; color: #fff;">{{ $rc->book?->title }}</div>
                        <div style="font-size: 0.72rem; color: var(--text-dim);">{{ $rc->borrower?->name }} &bull; {{ $rc->status->label() }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
