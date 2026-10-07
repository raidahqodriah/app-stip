@extends('layouts.app')

@section('title', 'Monitoring & Verifikasi Booking Lab')

@section('content')
<div style="margin-bottom: 2rem;">
    <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 1rem;">
        <div>
            <div style="display: inline-flex; align-items: center; gap: 0.5rem; color: var(--primary-light); font-size: 0.8rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.5rem;">
                <span>📡 MONITORING TRANSAKSI LAB</span> &bull; <span>VERIFIKASI DUA TAHAP</span>
            </div>
            <h1 style="font-size: 1.85rem; font-weight: 800; color: #fff;">
                Pelacakan Status & Alur Persetujuan Booking
            </h1>
            <p style="color: var(--text-muted); font-size: 0.92rem; max-width: 650px;">
                Pantau proses verifikasi kesiapan simulator oleh Petugas SPP dan persetujuan akhir oleh Kepala Unit SPP (§3.2 & §7.1).
            </p>
        </div>

        <div style="display: flex; gap: 0.75rem;">
            <a href="{{ route('lab.booking') }}" class="btn btn-gold">
                ➕ Buat Booking Baru
            </a>
            <a href="{{ route('public.schedule') }}" class="btn btn-outline">
                📅 Kalender Ketersediaan
            </a>
        </div>
    </div>
</div>

<!-- KPI Stats Bar -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 1rem; margin-bottom: 2rem;">
    <a href="{{ route('lab.monitoring') }}" style="text-decoration: none;">
        <div class="glass-card" style="padding: 1.25rem; border-color: {{ !$statusFilter ? 'var(--primary-light)' : 'var(--border-subtle)' }};">
            <div style="font-size: 0.75rem; color: var(--text-dim); text-transform: uppercase;">Total Booking</div>
            <div style="font-size: 1.6rem; font-weight: 800; color: #fff; margin-top: 0.2rem;">{{ $stats['total'] }}</div>
        </div>
    </a>
    <a href="{{ route('lab.monitoring', ['status' => 'submitted']) }}" style="text-decoration: none;">
        <div class="glass-card" style="padding: 1.25rem; border-color: {{ $statusFilter == 'submitted' ? '#fbbf24' : 'var(--border-subtle)' }};">
            <div style="font-size: 0.75rem; color: #fbbf24; text-transform: uppercase;">Menunggu Petugas (T-1)</div>
            <div style="font-size: 1.6rem; font-weight: 800; color: #fbbf24; margin-top: 0.2rem;">{{ $stats['submitted'] }}</div>
        </div>
    </a>
    <a href="{{ route('lab.monitoring', ['status' => 'verified']) }}" style="text-decoration: none;">
        <div class="glass-card" style="padding: 1.25rem; border-color: {{ $statusFilter == 'verified' ? '#38bdf8' : 'var(--border-subtle)' }};">
            <div style="font-size: 0.75rem; color: #38bdf8; text-transform: uppercase;">Menunggu Kepala Unit (T-2)</div>
            <div style="font-size: 1.6rem; font-weight: 800; color: #38bdf8; margin-top: 0.2rem;">{{ $stats['verified'] }}</div>
        </div>
    </a>
    <a href="{{ route('lab.monitoring', ['status' => 'approved']) }}" style="text-decoration: none;">
        <div class="glass-card" style="padding: 1.25rem; border-color: {{ $statusFilter == 'approved' ? '#34d399' : 'var(--border-subtle)' }};">
            <div style="font-size: 0.75rem; color: #34d399; text-transform: uppercase;">Disetujui (Siap Cetak)</div>
            <div style="font-size: 1.6rem; font-weight: 800; color: #34d399; margin-top: 0.2rem;">{{ $stats['approved'] }}</div>
        </div>
    </a>
    <a href="{{ route('lab.monitoring', ['status' => 'completed']) }}" style="text-decoration: none;">
        <div class="glass-card" style="padding: 1.25rem; border-color: {{ $statusFilter == 'completed' ? '#a78bfa' : 'var(--border-subtle)' }};">
            <div style="font-size: 0.75rem; color: #a78bfa; text-transform: uppercase;">Realisasi Selesai</div>
            <div style="font-size: 1.6rem; font-weight: 800; color: #a78bfa; margin-top: 0.2rem;">{{ $stats['completed'] }}</div>
        </div>
    </a>
</div>

<!-- Workflow Diagram Helper -->
<div class="glass-card" style="margin-bottom: 2rem; background: rgba(13, 27, 46, 0.6); padding: 1.25rem;">
    <div style="font-size: 0.78rem; font-weight: 700; color: var(--gold-light); text-transform: uppercase; margin-bottom: 0.75rem;">
        Alur Prosedural Status Booking (§7.1)
    </div>
    <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 0.5rem; font-size: 0.75rem;">
        <span class="badge badge-slate">1. Draft / Submitted</span>
        <span style="color: var(--text-dim);">&rarr;</span>
        <span class="badge badge-cyan">2. Verifikasi Petugas SPP (`lab.verify`)</span>
        <span style="color: var(--text-dim);">&rarr;</span>
        <span class="badge badge-gold">3. Persetujuan Kepala Unit SPP (`lab.approve`)</span>
        <span style="color: var(--text-dim);">&rarr;</span>
        <span class="badge badge-emerald">4. Disetujui (Cetak Bukti PDF)</span>
        <span style="color: var(--text-dim);">&rarr;</span>
        <span class="badge badge-cyan">5. Sesi Praktikum & Realisasi</span>
    </div>
</div>

<!-- Bookings Table -->
<div class="glass-card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem; flex-wrap: wrap; gap: 1rem;">
        <h3 style="font-size: 1.1rem; font-weight: 700; color: #fff;">
            Daftar Permohonan Booking Laboratorium
        </h3>

        <!-- Filter Form -->
        <form method="GET" action="{{ route('lab.monitoring') }}" style="display: flex; gap: 0.75rem;">
            <select name="status" class="form-select" style="padding: 0.4rem 0.75rem; font-size: 0.8rem; width: auto;" onchange="this.form.submit()">
                <option value="">-- Semua Status --</option>
                <option value="submitted" {{ $statusFilter == 'submitted' ? 'selected' : '' }}>Menunggu Verifikasi (Submitted)</option>
                <option value="verified" {{ $statusFilter == 'verified' ? 'selected' : '' }}>Terverifikasi (Verified)</option>
                <option value="approved" {{ $statusFilter == 'approved' ? 'selected' : '' }}>Disetujui (Approved)</option>
                <option value="completed" {{ $statusFilter == 'completed' ? 'selected' : '' }}>Selesai (Completed)</option>
                <option value="revision_requested" {{ $statusFilter == 'revision_requested' ? 'selected' : '' }}>Perlu Revisi</option>
                <option value="rejected" {{ $statusFilter == 'rejected' ? 'selected' : '' }}>Ditolak</option>
            </select>
        </form>
    </div>

    <div class="table-responsive">
        <table class="custom-table">
            <thead>
                <tr>
                    <th>Nomor Booking</th>
                    <th>Lab / Fasilitas</th>
                    <th>Mata Kuliah & Tujuan</th>
                    <th>Waktu Pelaksanaan</th>
                    <th>Peserta / Kelas</th>
                    <th>Status Alur</th>
                    <th>Aksi & Workflow</th>
                </tr>
            </thead>
            <tbody>
                @forelse($bookings as $b)
                    <tr style="{{ request('highlight') == $b->id ? 'background: rgba(2, 132, 199, 0.15);' : '' }}">
                        <td>
                            <strong style="color: var(--primary-light); font-family: 'JetBrains Mono', monospace; font-size: 0.85rem;">
                                {{ $b->booking_number }}
                            </strong>
                            <div style="font-size: 0.7rem; color: var(--text-dim);">
                                Diajukan: {{ $b->submitted_at?->diffForHumans() ?? '-' }}
                            </div>
                        </td>

                        <td>
                            <span class="badge badge-cyan">{{ $b->room?->code }}</span>
                            <div style="font-weight: 600; color: #fff; margin-top: 0.2rem;">
                                {{ $b->room?->name }}
                            </div>
                        </td>

                        <td>
                            <div style="font-weight: 600; color: #fff;">
                                {{ $b->subject?->name ?? 'Praktikum' }}
                            </div>
                            <div style="font-size: 0.75rem; color: var(--text-dim); margin-top: 0.15rem;">
                                {{ Str::limit($b->purpose, 35) }}
                            </div>
                            <div style="font-size: 0.72rem; color: var(--gold-light);">
                                Dosen PJ: {{ $b->responsibleLecturer?->name ?? '-' }}
                            </div>
                        </td>

                        <td>
                            <div style="font-weight: 600; color: #fff;">
                                {{ $b->start_at->isoFormat('D MMM Y') }}
                            </div>
                            <div style="font-size: 0.75rem; color: var(--text-muted); font-family: 'JetBrains Mono', monospace;">
                                {{ $b->start_at->format('H:i') }} - {{ $b->end_at->format('H:i') }} WIB
                            </div>
                        </td>

                        <td>
                            <div style="font-weight: 600; color: #fff;">
                                {{ $b->participant_count }} Orang
                            </div>
                            <div style="font-size: 0.75rem; color: var(--text-dim);">
                                {{ $b->class_group }}
                            </div>
                        </td>

                        <td>
                            @php
                                $badgeClass = match($b->status->value) {
                                    'submitted' => 'badge-gold',
                                    'verified' => 'badge-cyan',
                                    'approved' => 'badge-emerald',
                                    'completed' => 'badge-slate',
                                    'rejected', 'cancelled' => 'badge-rose',
                                    default => 'badge-slate',
                                };
                            @endphp
                            <span class="badge {{ $badgeClass }}">
                                {{ $b->status->label() }}
                            </span>
                        </td>

                        <td>
                            <div style="display: flex; gap: 0.35rem; flex-wrap: wrap;">
                                <a href="{{ route('lab.detail', ['id' => $b->id]) }}" class="btn btn-sm btn-outline" title="Lihat Detail & Riwayat Log">
                                    Detail
                                </a>

                                <!-- Workflow Stage Actions -->
                                @if($b->status->value === 'submitted')
                                    <form action="{{ route('lab.status.update', ['id' => $b->id]) }}" method="POST" style="display: inline;">
                                        @csrf
                                        <input type="hidden" name="action" value="verify">
                                        <button type="submit" class="btn btn-sm btn-primary" title="Verifikasi Kesiapan Lab & Bahan">
                                            ✓ Verifikasi (Petugas)
                                        </button>
                                    </form>
                                @elseif($b->status->value === 'verified')
                                    <form action="{{ route('lab.status.update', ['id' => $b->id]) }}" method="POST" style="display: inline;">
                                        @csrf
                                        <input type="hidden" name="action" value="approve">
                                        <button type="submit" class="btn btn-sm btn-gold" title="Setujui Booking (Kepala Unit)">
                                            ★ Setujui (Kepala Unit)
                                        </button>
                                    </form>
                                @elseif($b->status->value === 'approved')
                                    <a href="{{ route('documents.booking', ['number' => $b->booking_number]) }}" class="btn btn-sm btn-emerald" title="Cetak Surat Konfirmasi Booking">
                                        🖨️ Bukti Cetak
                                    </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 3rem; color: var(--text-dim);">
                            Tidak ada permohonan booking yang sesuai dengan filter.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 1.25rem;">
        {{ $bookings->links() }}
    </div>
</div>
@endsection
