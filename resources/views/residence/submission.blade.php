@extends('layouts.app')

@section('title', 'Permohonan Izin Rumah Dinas (SIP)')

@section('content')
<div style="margin-bottom: 2rem;">
    <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 1rem;">
        <div>
            <div style="display: inline-flex; align-items: center; gap: 0.5rem; color: var(--gold-light); font-size: 0.8rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.5rem;">
                <span>🏠 MODUL C: SURAT IZIN RUMAH DINAS</span> &bull; <span>PENGUSULAN UNIT</span>
            </div>
            <h1 style="font-size: 1.85rem; font-weight: 800; color: #fff;">
                Form Permohonan Surat Izin Penghuni (SIP)
            </h1>
            <p style="color: var(--text-muted); font-size: 0.92rem; max-width: 650px;">
                Pengajuan dari unit pengusul untuk pegawai yang ditugaskan menghuni rumah dinas STIP Jakarta. Memerlukan verifikasi Petugas Rumah Tangga dan persetujuan Ketua STIP (§5).
            </p>
        </div>

        <div>
            <a href="{{ route('residence.approval') }}" class="btn btn-outline">
                ✍️ Lembar Persetujuan Ketua STIP
            </a>
        </div>
    </div>
</div>

<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem; align-items: start;">
    <!-- Submission Form -->
    <div class="glass-card">
        <h3 style="font-size: 1.15rem; font-weight: 700; color: #fff; margin-bottom: 1.25rem;">
            📝 Formulir Pengajuan Izin Penghunian
        </h3>

        <form action="{{ route('residence.submission.store') }}" method="POST">
            @csrf

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1rem;">
                <!-- Unit Pengusul -->
                <div class="form-group">
                    <label class="form-label">Unit Kerja Pengusul *</label>
                    <select name="unit_id" class="form-select" required>
                        @foreach($units as $u)
                            <option value="{{ $u->id }}" {{ old('unit_id') == $u->id ? 'selected' : '' }}>
                                {{ $u->name }} ({{ $u->code }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Pegawai Calon Penghuni -->
                <div class="form-group">
                    <label class="form-label">Pegawai Calon Penghuni *</label>
                    <select name="employee_id" class="form-select" required>
                        <option value="">-- Pilih Pegawai STIP --</option>
                        @foreach($employees as $emp)
                            <option value="{{ $emp->id }}" {{ old('employee_id') == $emp->id ? 'selected' : '' }}>
                                {{ $emp->name }} (NIP: {{ $emp->employee_number }}) - {{ $emp->position ?? 'Pegawai' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Rumah Dinas -->
                <div class="form-group" style="grid-column: 1 / -1;">
                    <label class="form-label">Pilih Unit Rumah Dinas *</label>
                    <select name="official_residence_id" class="form-select" required>
                        <option value="">-- Pilih Nomor & Lokasi Rumah Dinas --</option>
                        @foreach($residences as $res)
                            <option value="{{ $res->id }}" {{ old('official_residence_id') == $res->id ? 'selected' : '' }}>
                                Rumah Dinas Kav. {{ $res->house_number }} - {{ $res->address }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Rentang Waktu -->
                <div class="form-group">
                    <label class="form-label">Tanggal Mulai Masa Huni *</label>
                    <input type="date" name="occupancy_start" class="form-control" value="{{ old('occupancy_start', date('Y-m-d')) }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Tanggal Berakhir Masa Huni *</label>
                    <input type="date" name="occupancy_end" class="form-control" value="{{ old('occupancy_end', date('Y-m-d', strtotime('+2 years'))) }}" required>
                    <span class="form-hint">Masa izin standar 2 (dua) tahun kalender</span>
                </div>

                <div class="form-group" style="grid-column: 1 / -1;">
                    <label class="form-label">Dasar Pertimbangan / Catatan Peruntukan</label>
                    <textarea name="occupancy_notes" class="form-control" rows="3" placeholder="Contoh: Penugasan dosen tetap STIP Jakarta berdasarkan Keputusan Ketua STIP No. ...">{{ old('occupancy_notes') }}</textarea>
                </div>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 1rem; margin-top: 1.5rem;">
                <a href="{{ route('home') }}" class="btn btn-outline">Batal</a>
                <button type="submit" class="btn btn-gold" style="padding: 0.75rem 2rem;">
                    🚀 Kirim Pengajuan Izin Rumah Dinas
                </button>
            </div>
        </form>
    </div>

    <!-- Right: Workflow & Info -->
    <div>
        <div class="glass-card" style="margin-bottom: 1.5rem; border-color: rgba(2, 132, 199, 0.3);">
            <h4 style="font-size: 0.95rem; font-weight: 700; color: var(--primary-light); margin-bottom: 0.75rem;">
                📜 Ketentuan Alur SIP (§5)
            </h4>
            <ul style="font-size: 0.8rem; color: var(--text-muted); padding-left: 1.2rem; line-height: 1.7;">
                <li>Pengajuan diajukan oleh <strong>Unit Kerja Pengusul</strong>, bukan oleh calon penghuni secara individu.</li>
                <li><strong>Petugas Rumah Tangga</strong> memeriksa kelayakan berkas fisik & ketersediaan hunian.</li>
                <li><strong>Ketua STIP Jakarta</strong> memegang wewenang tunggal persetujuan penerbitan Surat Izin Penghunian resmi.</li>
                <li>Surat izin fisik dapat dicetak langsung setelah Ketua STIP memberikan persetujuan final.</li>
            </ul>
        </div>

        <div class="glass-card">
            <h4 style="font-size: 0.95rem; font-weight: 700; color: #fff; margin-bottom: 1rem;">
                Permohonan Rumah Dinas Aktif
            </h4>

            <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                @forelse($recentPermits as $p)
                    <div style="background: rgba(255, 255, 255, 0.03); border: 1px solid var(--border-subtle); border-radius: 8px; padding: 0.75rem;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.25rem;">
                            <span style="font-size: 0.75rem; color: var(--gold-light); font-weight: 700;">
                                Kav. {{ $p->officialResidence?->house_number ?? '-' }}
                            </span>
                            <span class="badge {{ $p->status->value === 'approved' ? 'badge-emerald' : 'badge-gold' }}" style="font-size: 0.65rem;">
                                {{ $p->status->label() }}
                            </span>
                        </div>
                        <div style="font-size: 0.85rem; font-weight: 700; color: #fff;">
                            {{ $p->employee?->name ?? 'Pegawai' }}
                        </div>
                        <div style="font-size: 0.72rem; color: var(--text-dim); margin-top: 0.15rem;">
                            Masa Huni: {{ $p->occupancy_start?->format('d/m/Y') }} s.d. {{ $p->occupancy_end?->format('d/m/Y') }}
                        </div>
                    </div>
                @empty
                    <div style="color: var(--text-dim); font-size: 0.8rem;">Belum ada pengajuan aktif.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
