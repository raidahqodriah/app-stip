@extends('layouts.app')

@section('title', 'Alur Persetujuan Izin Rumah Dinas')

@section('content')
<div style="margin-bottom: 2rem;">
    <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 1rem;">
        <div>
            <div style="display: inline-flex; align-items: center; gap: 0.5rem; color: var(--gold-light); font-size: 0.8rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.5rem;">
                <span>🏠 MODUL C: RUMAH DINAS</span> &bull; <span>PERSETUJUAN KETUA STIP</span>
            </div>
            <h1 style="font-size: 1.85rem; font-weight: 800; color: #fff;">
                Alur Persetujuan Surat Izin Penghunian (SIP)
            </h1>
            <p style="color: var(--text-muted); font-size: 0.92rem; max-width: 650px;">
                Daftar permohonan penghunian rumah dinas. Ketua STIP tidak dapat menyetujui sebelum Petugas Rumah Tangga memverifikasi berkas kelayakan (§5).
            </p>
        </div>

        <div>
            <a href="{{ route('residence.submission') }}" class="btn btn-gold">
                ➕ Buat Pengajuan Baru
            </a>
        </div>
    </div>
</div>

<!-- Stats Bar -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 1rem; margin-bottom: 2rem;">
    <a href="{{ route('residence.approval') }}" style="text-decoration: none;">
        <div class="glass-card" style="padding: 1.25rem; border-color: {{ !$statusFilter ? 'var(--primary-light)' : 'var(--border-subtle)' }};">
            <div style="font-size: 0.75rem; color: var(--text-dim); text-transform: uppercase;">Total Permohonan</div>
            <div style="font-size: 1.6rem; font-weight: 800; color: #fff; margin-top: 0.2rem;">{{ $stats['total'] }}</div>
        </div>
    </a>
    <a href="{{ route('residence.approval', ['status' => 'submitted']) }}" style="text-decoration: none;">
        <div class="glass-card" style="padding: 1.25rem; border-color: {{ $statusFilter == 'submitted' ? '#fbbf24' : 'var(--border-subtle)' }};">
            <div style="font-size: 0.75rem; color: #fbbf24; text-transform: uppercase;">Pemeriksaan Petugas RT</div>
            <div style="font-size: 1.6rem; font-weight: 800; color: #fbbf24; margin-top: 0.2rem;">{{ $stats['submitted'] }}</div>
        </div>
    </a>
    <a href="{{ route('residence.approval', ['status' => 'verified']) }}" style="text-decoration: none;">
        <div class="glass-card" style="padding: 1.25rem; border-color: {{ $statusFilter == 'verified' ? '#38bdf8' : 'var(--border-subtle)' }};">
            <div style="font-size: 0.75rem; color: #38bdf8; text-transform: uppercase;">Menunggu Ketua STIP</div>
            <div style="font-size: 1.6rem; font-weight: 800; color: #38bdf8; margin-top: 0.2rem;">{{ $stats['verified'] }}</div>
        </div>
    </a>
    <a href="{{ route('residence.approval', ['status' => 'approved']) }}" style="text-decoration: none;">
        <div class="glass-card" style="padding: 1.25rem; border-color: {{ $statusFilter == 'approved' ? '#34d399' : 'var(--border-subtle)' }};">
            <div style="font-size: 0.75rem; color: #34d399; text-transform: uppercase;">Disetujui (SIP Terbit)</div>
            <div style="font-size: 1.6rem; font-weight: 800; color: #34d399; margin-top: 0.2rem;">{{ $stats['approved'] }}</div>
        </div>
    </a>
</div>

<!-- Workflow Stepper Guide -->
<div class="glass-card" style="margin-bottom: 2rem; background: rgba(13, 27, 46, 0.6); padding: 1.25rem;">
    <div style="font-size: 0.78rem; font-weight: 700; color: var(--gold-light); text-transform: uppercase; margin-bottom: 0.75rem;">
        Diagram Alur Persetujuan Rumah Dinas (§5)
    </div>
    <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 0.5rem; font-size: 0.75rem;">
        <span class="badge badge-slate">1. Unit Pengusul Mengajukan (`draft &rarr; submitted`)</span>
        <span style="color: var(--text-dim);">&rarr;</span>
        <span class="badge badge-cyan">2. Petugas RT Memeriksa Fisik (`residence.process &rarr; verified`)</span>
        <span style="color: var(--text-dim);">&rarr;</span>
        <span class="badge badge-gold">3. Ketua STIP Memberikan Izin (`residence.approve &rarr; approved`)</span>
        <span style="color: var(--text-dim);">&rarr;</span>
        <span class="badge badge-emerald">4. Penerbitan Surat Izin SIP Resmi Siap Cetak</span>
    </div>
</div>

<!-- Table List -->
<div class="glass-card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem; flex-wrap: wrap; gap: 1rem;">
        <h3 style="font-size: 1.1rem; font-weight: 700; color: #fff;">
            Daftar Permohonan Izin Rumah Dinas
        </h3>

        <form method="GET" action="{{ route('residence.approval') }}">
            <select name="status" class="form-select" style="padding: 0.4rem 0.75rem; font-size: 0.8rem; width: auto;" onchange="this.form.submit()">
                <option value="">-- Semua Status --</option>
                <option value="submitted" {{ $statusFilter == 'submitted' ? 'selected' : '' }}>Menunggu Petugas RT</option>
                <option value="verified" {{ $statusFilter == 'verified' ? 'selected' : '' }}>Menunggu Ketua STIP</option>
                <option value="approved" {{ $statusFilter == 'approved' ? 'selected' : '' }}>Disetujui (SIP Terbit)</option>
            </select>
        </form>
    </div>

    <div class="table-responsive">
        <table class="custom-table">
            <thead>
                <tr>
                    <th>Nomor SIP & Tanggal</th>
                    <th>Pegawai Calon Penghuni</th>
                    <th>Unit Rumah Dinas</th>
                    <th>Masa Berlaku Izin</th>
                    <th>Status Alur</th>
                    <th>Aksi & Workflow</th>
                </tr>
            </thead>
            <tbody>
                @forelse($permits as $p)
                    <tr>
                        <td>
                            @if($p->permit_number)
                                <strong style="color: var(--gold-light); font-family: 'JetBrains Mono', monospace; font-size: 0.82rem;">
                                    {{ $p->permit_number }}
                                </strong>
                            @else
                                <span style="color: var(--text-dim); font-size: 0.75rem; font-style: italic;">
                                    (Menunggu Terbit)
                                </span>
                            @endif
                            <div style="font-size: 0.72rem; color: var(--text-dim); margin-top: 0.15rem;">
                                Diajukan: {{ $p->created_at->format('d/m/Y') }}
                            </div>
                        </td>

                        <td>
                            <div style="font-weight: 700; color: #fff;">
                                {{ $p->employee?->name }}
                            </div>
                            <div style="font-size: 0.75rem; color: var(--text-muted);">
                                NIP: {{ $p->employee?->employee_number }} &bull; {{ $p->unit?->name }}
                            </div>
                        </td>

                        <td>
                            <div style="font-weight: 600; color: var(--primary-light);">
                                Kav. {{ $p->officialResidence?->house_number }}
                            </div>
                            <div style="font-size: 0.72rem; color: var(--text-dim);">
                                {{ $p->officialResidence?->address }}
                            </div>
                        </td>

                        <td>
                            <div style="font-weight: 600; color: #fff; font-size: 0.82rem;">
                                {{ $p->occupancy_start?->format('d/m/Y') }} s.d. {{ $p->occupancy_end?->format('d/m/Y') }}
                            </div>
                        </td>

                        <td>
                            @php
                                $badgeClass = match($p->status->value) {
                                    'submitted' => 'badge-gold',
                                    'verified' => 'badge-cyan',
                                    'approved' => 'badge-emerald',
                                    default => 'badge-slate',
                                };
                            @endphp
                            <span class="badge {{ $badgeClass }}">
                                {{ $p->status->label() }}
                            </span>
                        </td>

                        <td>
                            <div style="display: flex; gap: 0.35rem; flex-wrap: wrap;">
                                @if($p->status->value === 'submitted')
                                    <form action="{{ route('residence.process', ['id' => $p->id]) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="action" value="verify">
                                        <button type="submit" class="btn btn-sm btn-primary" title="Verifikasi Berkas Kelayakan">
                                            ✓ Verifikasi (Petugas RT)
                                        </button>
                                    </form>
                                @elseif($p->status->value === 'verified')
                                    <form action="{{ route('residence.process', ['id' => $p->id]) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="action" value="approve">
                                        <button type="submit" class="btn btn-sm btn-gold" title="Persetujuan Resmi Ketua STIP">
                                            ★ Setujui (Ketua STIP)
                                        </button>
                                    </form>
                                @elseif($p->status->value === 'approved')
                                    <a href="{{ route('documents.residence_permit', ['number' => $p->id]) }}" class="btn btn-sm btn-emerald" title="Cetak Surat Izin Penghuni Resmi">
                                        🖨️ Cetak Surat SIP
                                    </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 3rem; color: var(--text-dim);">
                            Tidak ada permohonan izin rumah dinas yang sesuai kriteria.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 1.25rem;">
        {{ $permits->links() }}
    </div>
</div>
@endsection
