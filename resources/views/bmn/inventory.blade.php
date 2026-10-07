@extends('layouts.app')

@section('title', 'Rekap Inventaris BMN Per Unit & Ruangan')

@section('content')
<div style="margin-bottom: 2rem;">
    <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 1rem;">
        <div>
            <div style="display: inline-flex; align-items: center; gap: 0.5rem; color: var(--gold-light); font-size: 0.8rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.5rem;">
                <span>📦 MODUL B: BARANG MILIK NEGARA (BMN)</span> &bull; <span>INVENTARIS TERPADU</span>
            </div>
            <h1 style="font-size: 1.85rem; font-weight: 800; color: #fff;">
                Rekapitulasi Inventaris BMN Unit & Ruangan
            </h1>
            <p style="color: var(--text-muted); font-size: 0.92rem; max-width: 650px;">
                Daftar master Barang Milik Negara yang ditempatkan di seluruh unit kerja, program studi, dan laboratorium STIP Jakarta (§4.3).
            </p>
        </div>

        <div style="display: flex; gap: 0.75rem;">
            <a href="{{ route('bmn.submission') }}" class="btn btn-gold">
                ➕ Pengajuan BMN Baru
            </a>
            <a href="{{ route('bmn.return') }}" class="btn btn-outline">
                ↩️ Form Pengembalian BMN
            </a>
        </div>
    </div>
</div>

<!-- Stats Overview -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 1rem; margin-bottom: 2rem;">
    <div class="glass-card" style="padding: 1.25rem;">
        <div style="font-size: 0.75rem; color: var(--text-dim); text-transform: uppercase;">Total Aset BMN</div>
        <div style="font-size: 1.6rem; font-weight: 800; color: #fff; margin-top: 0.2rem;">{{ $stats['total_items'] }}</div>
    </div>
    <div class="glass-card" style="padding: 1.25rem;">
        <div style="font-size: 0.75rem; color: #34d399; text-transform: uppercase;">Aset Aktif Digunakan</div>
        <div style="font-size: 1.6rem; font-weight: 800; color: #34d399; margin-top: 0.2rem;">{{ $stats['active_items'] }}</div>
    </div>
    <div class="glass-card" style="padding: 1.25rem;">
        <div style="font-size: 0.75rem; color: #fb7185; text-transform: uppercase;">Kondisi Rusak (Perlu BAP)</div>
        <div style="font-size: 1.6rem; font-weight: 800; color: #fb7185; margin-top: 0.2rem;">{{ $stats['damaged_items'] }}</div>
    </div>
    <div class="glass-card" style="padding: 1.25rem;">
        <div style="font-size: 0.75rem; color: var(--gold-light); text-transform: uppercase;">Pengajuan Baru Pending</div>
        <div style="font-size: 1.6rem; font-weight: 800; color: var(--gold-light); margin-top: 0.2rem;">{{ $stats['pending_submissions'] }}</div>
    </div>
</div>

<!-- Filters Bar -->
<div class="glass-card" style="margin-bottom: 2rem; padding: 1.25rem;">
    <form method="GET" action="{{ route('bmn.inventory') }}" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)) auto; gap: 1rem; align-items: flex-end;">
        <div>
            <label class="form-label">Cari Nama / Kode / Merk</label>
            <input type="text" name="search" class="form-control" placeholder="Contoh: Laptop, ASUS, NUP..." value="{{ $search }}">
        </div>

        <div>
            <label class="form-label">Filter Unit Kerja</label>
            <select name="unit_id" class="form-select" onchange="this.form.submit()">
                <option value="">-- Semua Unit Kerja --</option>
                @foreach($units as $u)
                    <option value="{{ $u->id }}" {{ $unitFilter == $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="form-label">Filter Ruangan</label>
            <select name="room_id" class="form-select" onchange="this.form.submit()">
                <option value="">-- Semua Ruangan --</option>
                @foreach($rooms as $r)
                    <option value="{{ $r->id }}" {{ $roomFilter == $r->id ? 'selected' : '' }}>{{ $r->code }} - {{ $r->name }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="form-label">Kondisi Barang</label>
            <select name="condition" class="form-select" onchange="this.form.submit()">
                <option value="">-- Semua Kondisi --</option>
                <option value="good" {{ $conditionFilter == 'good' ? 'selected' : '' }}>Baik (Normal)</option>
                <option value="minor_damage" {{ $conditionFilter == 'minor_damage' ? 'selected' : '' }}>Rusak Ringan</option>
                <option value="major_damage" {{ $conditionFilter == 'major_damage' ? 'selected' : '' }}>Rusak Berat</option>
                <option value="lost" {{ $conditionFilter == 'lost' ? 'selected' : '' }}>Hilang</option>
            </select>
        </div>

        <div>
            <button type="submit" class="btn btn-primary" style="width: 100%;">
                Filter Data
            </button>
        </div>
    </form>
</div>

<!-- Inventory Table -->
<div class="glass-card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem;">
        <h3 style="font-size: 1.1rem; font-weight: 700; color: #fff;">
            Daftar Inventaris Terdaftar
        </h3>
        <span style="font-size: 0.8rem; color: var(--text-dim);">
            Menampilkan {{ $items->total() }} barang
        </span>
    </div>

    <div class="table-responsive">
        <table class="custom-table">
            <thead>
                <tr>
                    <th>Kode BMN / NUP</th>
                    <th>Nama Barang & Spesifikasi</th>
                    <th>Penempatan & Ruangan</th>
                    <th>Penanggung Jawab</th>
                    <th>Kondisi</th>
                    <th>Status</th>
                    <th>Dokumen & Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($items as $item)
                    <tr>
                        <td>
                            <strong style="color: var(--gold-light); font-family: 'JetBrains Mono', monospace; font-size: 0.82rem;">
                                {{ $item->bmn_code ?? '-' }}
                            </strong>
                            <div style="font-size: 0.72rem; color: var(--text-dim); font-family: 'JetBrains Mono', monospace;">
                                {{ $item->register_number ?? '-' }}
                            </div>
                        </td>

                        <td>
                            <div style="font-weight: 700; color: #fff;">
                                {{ $item->item_name }}
                            </div>
                            <div style="font-size: 0.75rem; color: var(--text-muted); margin-top: 0.15rem;">
                                {{ $item->brand }} {{ $item->model }} &bull; Qty: <strong>{{ $item->quantity }} unit</strong>
                            </div>
                            @if($item->serial_number)
                                <div style="font-size: 0.7rem; color: var(--text-dim); font-family: 'JetBrains Mono', monospace;">
                                    S/N: {{ $item->serial_number }}
                                </div>
                            @endif
                        </td>

                        <td>
                            <div style="font-weight: 600; color: var(--primary-light);">
                                {{ $item->unit?->name }}
                            </div>
                            <div style="font-size: 0.75rem; color: var(--text-dim);">
                                Ruang: {{ $item->room?->name }} ({{ $item->room?->code }})
                            </div>
                        </td>

                        <td>
                            <div style="font-weight: 600; color: #fff;">
                                {{ $item->responsible_name }}
                            </div>
                            <div style="font-size: 0.72rem; color: var(--text-dim);">
                                Perolehan: {{ $item->acquisition_date?->format('d/m/Y') }} &bull; {{ $item->acquisition_source }}
                            </div>
                        </td>

                        <td>
                            @php
                                $condClass = match($item->condition->value) {
                                    'good' => 'badge-emerald',
                                    'minor_damage' => 'badge-gold',
                                    'major_damage', 'lost' => 'badge-rose',
                                    default => 'badge-slate',
                                };
                            @endphp
                            <span class="badge {{ $condClass }}">
                                {{ $item->condition->label() }}
                            </span>
                        </td>

                        <td>
                            <span class="badge {{ $item->status === 'active' ? 'badge-cyan' : 'badge-slate' }}">
                                {{ ucfirst($item->status) }}
                            </span>
                        </td>

                        <td>
                            <div style="display: flex; gap: 0.35rem;">
                                @if($item->requires_decree)
                                    <a href="{{ route('documents.bmn_decree', ['number' => $item->bmn_code ?? $item->id]) }}" class="btn btn-sm btn-gold" title="Cetak Surat Penetapan BMN">
                                        📜 SK Penetapan
                                    </a>
                                @endif
                                <a href="{{ route('bmn.return', ['item_id' => $item->id]) }}" class="btn btn-sm btn-outline" title="Kembalikan Barang">
                                    ↩️ Return
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 3rem; color: var(--text-dim);">
                            Tidak ada data inventaris BMN yang sesuai dengan kriteria pencarian.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 1.25rem;">
        {{ $items->links() }}
    </div>
</div>
@endsection
