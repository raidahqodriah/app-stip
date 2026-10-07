@extends('layouts.app')

@section('title', 'Pusat Dokumen Cetak Resmi STIP')

@section('content')
<div style="margin-bottom: 2rem;">
    <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 1rem;">
        <div>
            <div style="display: inline-flex; align-items: center; gap: 0.5rem; color: var(--gold-light); font-size: 0.8rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.5rem;">
                <span>📜 MEKANISME DOKUMEN RESMI</span> &bull; <span>SIAP CETAK A4</span>
            </div>
            <h1 style="font-size: 1.85rem; font-weight: 800; color: #fff;">
                Pusat Terbitan Dokumen Resmi Kampus
            </h1>
            <p style="color: var(--text-muted); font-size: 0.92rem; max-width: 650px;">
                Semua dokumen administrasi terbit otomatis dengan nomor unik, kode verifikasi digital, dan jumlah cetak tercatat (§7.2).
            </p>
        </div>
    </div>
</div>

<!-- Document Type Shortcut Cards -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1rem; margin-bottom: 2rem;">
    <div class="glass-card" style="border-top: 3px solid var(--primary-light);">
        <div style="font-size: 1.5rem; margin-bottom: 0.5rem;">⚓</div>
        <h4 style="font-size: 0.95rem; font-weight: 700; color: #fff;">Konfirmasi Booking Lab</h4>
        <p style="font-size: 0.78rem; color: var(--text-muted); margin: 0.35rem 0 1rem;">
            Syarat terbit: Status <strong>Approved</strong> oleh Kepala Unit SPP.
        </p>
        <a href="{{ route('documents.index', ['type' => 'booking_confirmation']) }}" class="btn btn-sm btn-outline">
            Filter Dokumen Lab &rarr;
        </a>
    </div>

    <div class="glass-card" style="border-top: 3px solid var(--gold-light);">
        <div style="font-size: 1.5rem; margin-bottom: 0.5rem;">📜</div>
        <h4 style="font-size: 0.95rem; font-weight: 700; color: #fff;">Surat Penetapan BMN</h4>
        <p style="font-size: 0.78rem; color: var(--text-muted); margin: 0.35rem 0 1rem;">
            Syarat terbit: Diverifikasi Petugas BMN dan butuh SK Penetapan.
        </p>
        <a href="{{ route('documents.index', ['type' => 'bmn_decree']) }}" class="btn btn-sm btn-outline">
            Filter SK BMN &rarr;
        </a>
    </div>

    <div class="glass-card" style="border-top: 3px solid #fb7185;">
        <div style="font-size: 1.5rem; margin-bottom: 0.5rem;">↩️</div>
        <h4 style="font-size: 0.95rem; font-weight: 700; color: #fff;">Tanda Terima BMN Rusak</h4>
        <p style="font-size: 0.78rem; color: var(--text-muted); margin: 0.35rem 0 1rem;">
            Syarat terbit: Pengembalian barang diverifikasi oleh Petugas BMN.
        </p>
        <a href="{{ route('documents.index', ['type' => 'bmn_return_receipt']) }}" class="btn btn-sm btn-outline">
            Filter Tanda Terima &rarr;
        </a>
    </div>

    <div class="glass-card" style="border-top: 3px solid #34d399;">
        <div style="font-size: 1.5rem; margin-bottom: 0.5rem;">🏠</div>
        <h4 style="font-size: 0.95rem; font-weight: 700; color: #fff;">Surat Izin Penghuni (SIP)</h4>
        <p style="font-size: 0.78rem; color: var(--text-muted); margin: 0.35rem 0 1rem;">
            Syarat terbit: Disetujui resmi oleh <strong>Ketua STIP Jakarta</strong>.
        </p>
        <a href="{{ route('documents.index', ['type' => 'residence_permit']) }}" class="btn btn-sm btn-outline">
            Filter Surat SIP &rarr;
        </a>
    </div>
</div>

<!-- Generated Documents Table -->
<div class="glass-card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem;">
        <h3 style="font-size: 1.1rem; font-weight: 700; color: #fff;">
            Daftar Dokumen yang Telah Diterbitkan Sistem
        </h3>

        @if($type)
            <a href="{{ route('documents.index') }}" class="btn btn-sm btn-outline">
                ✕ Hapus Filter Tipe
            </a>
        @endif
    </div>

    <div class="table-responsive">
        <table class="custom-table">
            <thead>
                <tr>
                    <th>Nomor Dokumen</th>
                    <th>Jenis Dokumen Resmi</th>
                    <th>Diterbitkan Untuk</th>
                    <th>Diterbitkan Oleh</th>
                    <th>Tanggal Terbit</th>
                    <th>Jml Cetak</th>
                    <th>Aksi Cetak</th>
                </tr>
            </thead>
            <tbody>
                @forelse($documents as $doc)
                    <tr>
                        <td>
                            <strong style="color: var(--primary-light); font-family: 'JetBrains Mono', monospace; font-size: 0.85rem;">
                                {{ $doc->document_number }}
                            </strong>
                        </td>

                        <td>
                            <div style="font-weight: 600; color: #fff;">
                                {{ $doc->template?->name }}
                            </div>
                            <span class="badge badge-slate" style="font-size: 0.65rem;">
                                {{ $doc->template?->type }}
                            </span>
                        </td>

                        <td>
                            <div style="font-size: 0.82rem; color: #e2e8f0;">
                                {{ class_basename($doc->documentable_type) }} #{{ $doc->documentable_id }}
                            </div>
                        </td>

                        <td>
                            <div style="font-weight: 600; color: #fff;">
                                {{ $doc->generator?->name ?? 'Admin Sistem' }}
                            </div>
                        </td>

                        <td>
                            <div style="font-size: 0.8rem; color: var(--text-muted);">
                                {{ $doc->created_at->format('d/m/Y H:i') }} WIB
                            </div>
                        </td>

                        <td>
                            <span class="badge badge-gold">
                                {{ $doc->print_count }}x Cetak
                            </span>
                        </td>

                        <td>
                            @php
                                $printRoute = match($doc->template?->type) {
                                    'booking_confirmation' => route('documents.booking', ['number' => $doc->document_number]),
                                    'bmn_decree' => route('documents.bmn_decree', ['number' => $doc->documentable_id]),
                                    'bmn_return_receipt' => route('documents.bmn_return', ['number' => $doc->documentable_id]),
                                    'residence_permit' => route('documents.residence_permit', ['number' => $doc->documentable_id]),
                                    default => '#',
                                };
                            @endphp
                            <a href="{{ $printRoute }}" class="btn btn-sm btn-emerald">
                                🖨️ Buka Format Cetak
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 3rem; color: var(--text-dim);">
                            Belum ada dokumen yang diterbitkan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 1.25rem;">
        {{ $documents->links() }}
    </div>
</div>
@endsection
