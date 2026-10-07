@extends('layouts.app')

@section('title', 'Pengajuan BMN Baru - Admin Unit Kerja')

@section('content')
<div style="margin-bottom: 2rem;">
    <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 1rem;">
        <div>
            <div style="display: inline-flex; align-items: center; gap: 0.5rem; color: var(--gold-light); font-size: 0.8rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.5rem;">
                <span>📦 MODUL B: BARANG MILIK NEGARA</span> &bull; <span>PENGAJUAN BARU</span>
            </div>
            <h1 style="font-size: 1.85rem; font-weight: 800; color: #fff;">
                Form Pengajuan Pencatatan BMN Baru
            </h1>
            <p style="color: var(--text-muted); font-size: 0.92rem; max-width: 650px;">
                Admin Unit Kerja mendaftarkan perolehan aset BMN baru untuk diverifikasi oleh Petugas BMN sebelum resmi masuk ke rekap inventaris (§4.1).
            </p>
        </div>

        <div>
            <a href="{{ route('bmn.inventory') }}" class="btn btn-outline">
                📦 Lihat Rekap Inventaris
            </a>
        </div>
    </div>
</div>

<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem; align-items: start;">
    <!-- Submission Form -->
    <div class="glass-card">
        <h3 style="font-size: 1.15rem; font-weight: 700; color: #fff; margin-bottom: 1.25rem;">
            📝 Formulir Data Barang Milik Negara
        </h3>

        <form action="{{ route('bmn.submission.store') }}" method="POST">
            @csrf

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1rem;">
                <!-- Unit & Room -->
                <div class="form-group">
                    <label class="form-label">Unit Kerja Pengguna *</label>
                    <select name="unit_id" class="form-select" required>
                        @foreach($units as $u)
                            <option value="{{ $u->id }}" {{ old('unit_id') == $u->id ? 'selected' : '' }}>
                                {{ $u->name }} ({{ $u->code }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Ruangan Penempatan *</label>
                    <select name="room_id" class="form-select" required>
                        @foreach($rooms as $r)
                            <option value="{{ $r->id }}" {{ old('room_id') == $r->id ? 'selected' : '' }}>
                                {{ $r->code }} - {{ $r->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Item Identity -->
                <div class="form-group" style="grid-column: 1 / -1;">
                    <label class="form-label">Nama Barang Milik Negara *</label>
                    <input type="text" name="item_name" class="form-control" placeholder="Contoh: Laptop Core i7, Printer Laserjet, dsb." value="{{ old('item_name') }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Kode BMN (Opsional)</label>
                    <input type="text" name="bmn_code" class="form-control" placeholder="Contoh: BMN-NAUT-2026-008" value="{{ old('bmn_code') }}">
                </div>

                <div class="form-group">
                    <label class="form-label">Nomor Urut Pendaftaran (NUP)</label>
                    <input type="text" name="register_number" class="form-control" placeholder="Contoh: NUP-0089" value="{{ old('register_number') }}">
                </div>

                <div class="form-group">
                    <label class="form-label">Merk / Brand *</label>
                    <input type="text" name="brand" class="form-control" placeholder="Contoh: ASUS, Lenovo, Dell" value="{{ old('brand') }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Model / Tipe *</label>
                    <input type="text" name="model" class="form-control" placeholder="Contoh: ExpertBook B1400" value="{{ old('model') }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Nomor Seri (Serial Number)</label>
                    <input type="text" name="serial_number" class="form-control" placeholder="Contoh: SN-99882211" value="{{ old('serial_number') }}">
                </div>

                <div class="form-group">
                    <label class="form-label">Jumlah Unit (Quantity) *</label>
                    <input type="number" name="quantity" class="form-control" min="1" value="{{ old('quantity', 1) }}" required>
                </div>

                <!-- Acquisition info -->
                <div class="form-group">
                    <label class="form-label">Tanggal Perolehan *</label>
                    <input type="date" name="acquisition_date" class="form-control" value="{{ old('acquisition_date', date('Y-m-d')) }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Sumber Dana Perolehan *</label>
                    <input type="text" name="acquisition_source" class="form-control" placeholder="Contoh: DIPA STIP T.A. 2026, Hibah Kemenhub" value="{{ old('acquisition_source', 'DIPA STIP T.A. 2026') }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Kondisi Awal Barang *</label>
                    <select name="condition" class="form-select" required>
                        <option value="good">Baik (100% Baru/Normal)</option>
                        <option value="minor_damage">Rusak Ringan (Bekas Pemakaian)</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Nama Penanggung Jawab Pegawai *</label>
                    <input type="text" name="responsible_name" class="form-control" placeholder="Contoh: Capt. Budi Santoso, M.Mar." value="{{ old('responsible_name') }}" required>
                </div>

                <!-- Decree toggle -->
                <div class="form-group" style="grid-column: 1 / -1; background: rgba(255, 255, 255, 0.03); padding: 1rem; border-radius: 8px; border: 1px solid var(--border-subtle);">
                    <label style="display: flex; align-items: center; gap: 0.65rem; cursor: pointer;">
                        <input type="checkbox" name="requires_decree" value="1" {{ old('requires_decree') ? 'checked' : '' }} style="width: 1.15rem; height: 1.15rem;">
                        <div>
                            <strong style="color: #fff; font-size: 0.88rem;">Barang Memerlukan Surat Penetapan Penggunaan BMN (Decree)</strong>
                            <div style="font-size: 0.75rem; color: var(--text-dim); margin-top: 0.15rem;">
                                Centang untuk barang inventaris perorangan/spesifik (seperti Laptop, PC, Kendaraan Dinas) yang membutuhkan bukti SK Penetapan siap cetak.
                            </div>
                        </div>
                    </label>
                </div>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 1rem; margin-top: 1.5rem;">
                <a href="{{ route('bmn.inventory') }}" class="btn btn-outline">Batal</a>
                <button type="submit" class="btn btn-gold" style="padding: 0.75rem 2rem;">
                    🚀 Kirim Pengajuan BMN ke Petugas
                </button>
            </div>
        </form>
    </div>

    <!-- Right: Workflow info & Recent Submissions -->
    <div>
        <div class="glass-card" style="margin-bottom: 1.5rem; border-color: rgba(245, 158, 11, 0.3);">
            <h4 style="font-size: 0.95rem; font-weight: 700; color: var(--gold-light); margin-bottom: 0.75rem;">
                ℹ️ Ketentuan Verifikasi BMN (§4.1)
            </h4>
            <p style="font-size: 0.8rem; color: var(--text-muted); line-height: 1.6; margin-bottom: 0.75rem;">
                1. Data yang diajukan <strong>belum masuk ke master inventaris aktif</strong> sampai disetujui oleh Petugas Pengelola BMN STIP Jakarta.
            </p>
            <p style="font-size: 0.8rem; color: var(--text-muted); line-height: 1.6;">
                2. Apabila membutuhkan perbaikan kelengkapan administrasi, Petugas akan memberikan catatan revisi (`revision_requested`).
            </p>
        </div>

        <div class="glass-card">
            <h4 style="font-size: 0.95rem; font-weight: 700; color: #fff; margin-bottom: 1rem;">
                Pengajuan Terakhir
            </h4>

            <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                @forelse($recentSubmissions as $sub)
                    <div style="background: rgba(255, 255, 255, 0.03); border: 1px solid var(--border-subtle); border-radius: 8px; padding: 0.75rem;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.25rem;">
                            <span style="font-size: 0.75rem; color: var(--primary-light); font-weight: 700;">
                                {{ $sub->unit?->name }}
                            </span>
                            <span class="badge {{ $sub->status->value === 'approved' ? 'badge-emerald' : 'badge-gold' }}" style="font-size: 0.65rem;">
                                {{ $sub->status->label() }}
                            </span>
                        </div>
                        <div style="font-size: 0.85rem; font-weight: 700; color: #fff;">
                            {{ $sub->item_name }}
                        </div>
                        <div style="font-size: 0.72rem; color: var(--text-dim); margin-top: 0.2rem;">
                            {{ $sub->quantity }} unit &bull; PJ: {{ $sub->responsible_name }}
                        </div>
                    </div>
                @empty
                    <div style="color: var(--text-dim); font-size: 0.8rem;">Belum ada pengajuan BMN sebelumnya.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
