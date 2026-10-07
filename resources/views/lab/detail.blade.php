@extends('layouts.app')

@section('title', 'Detail Booking ' . $booking->booking_number)

@section('content')
<div style="margin-bottom: 2rem;">
    <a href="{{ route('lab.monitoring') }}" style="color: var(--primary-light); text-decoration: none; font-size: 0.82rem; display: inline-flex; align-items: center; gap: 0.35rem; margin-bottom: 0.75rem;">
        &larr; Kembali ke Daftar Monitoring Lab
    </a>

    <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 1rem;">
        <div>
            <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.25rem;">
                <span class="badge badge-cyan">{{ $booking->room?->code }}</span>
                <span class="badge badge-gold">{{ $booking->status->label() }}</span>
                <span style="font-size: 0.8rem; color: var(--text-dim); font-family: 'JetBrains Mono', monospace;">{{ $booking->booking_number }}</span>
            </div>
            <h1 style="font-size: 1.85rem; font-weight: 800; color: #fff;">
                {{ $booking->purpose }}
            </h1>
            <p style="color: var(--text-muted); font-size: 0.88rem;">
                Mata Kuliah: <strong>{{ $booking->subject?->name }}</strong> &bull; Dosen PJ: <strong>{{ $booking->responsibleLecturer?->name }}</strong>
            </p>
        </div>

        <div style="display: flex; gap: 0.75rem;">
            @if($booking->status->value === 'approved' || $booking->status->value === 'completed')
                <a href="{{ route('documents.booking', ['number' => $booking->booking_number]) }}" class="btn btn-emerald">
                    🖨️ Cetak Surat Konfirmasi Booking
                </a>
            @endif
        </div>
    </div>
</div>

<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem; align-items: start;">
    <!-- Left Column: Specs, IMO, Materials & Realization -->
    <div>
        <!-- Booking Info Card -->
        <div class="glass-card" style="margin-bottom: 1.5rem;">
            <h3 style="font-size: 1.1rem; font-weight: 700; color: #fff; margin-bottom: 1rem;">
                📋 Parameter Permohonan
            </h3>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.25rem; font-size: 0.85rem;">
                <div>
                    <span style="color: var(--text-dim); display: block; font-size: 0.75rem; text-transform: uppercase;">Laboratorium</span>
                    <strong style="color: #fff;">{{ $booking->room?->name }}</strong>
                </div>
                <div>
                    <span style="color: var(--text-dim); display: block; font-size: 0.75rem; text-transform: uppercase;">Jadwal Sesi</span>
                    <strong style="color: var(--primary-light);">
                        {{ $booking->start_at->isoFormat('dddd, D MMMM Y') }} ({{ $booking->start_at->format('H:i') }} - {{ $booking->end_at->format('H:i') }} WIB)
                    </strong>
                </div>
                <div>
                    <span style="color: var(--text-dim); display: block; font-size: 0.75rem; text-transform: uppercase;">Peleton / Kelas</span>
                    <strong style="color: #fff;">{{ $booking->class_group }} ({{ $booking->participant_count }} Orang)</strong>
                </div>
                <div>
                    <span style="color: var(--text-dim); display: block; font-size: 0.75rem; text-transform: uppercase;">Unit / Jurusan</span>
                    <strong style="color: #fff;">{{ $booking->unit?->name }}</strong>
                </div>
            </div>

            @if($booking->notes)
                <div style="margin-top: 1.25rem; padding-top: 1rem; border-top: 1px solid var(--border-subtle); font-size: 0.82rem;">
                    <span style="color: var(--text-dim); display: block; margin-bottom: 0.25rem;">Catatan Pemohon:</span>
                    <p style="color: var(--text-muted); background: rgba(0, 0, 0, 0.25); padding: 0.75rem; border-radius: 8px;">{{ $booking->notes }}</p>
                </div>
            @endif
        </div>

        <!-- Competences List -->
        <div class="glass-card" style="margin-bottom: 1.5rem;">
            <h3 style="font-size: 1.1rem; font-weight: 700; color: #fff; margin-bottom: 0.75rem;">
                ⚓ Kompetensi IMO Model Course Terkait
            </h3>
            <div style="display: flex; flex-direction: column; gap: 0.6rem;">
                @forelse($booking->competences as $c)
                    <div style="background: rgba(255, 255, 255, 0.03); border: 1px solid var(--border-subtle); border-radius: 8px; padding: 0.75rem 1rem; display: flex; justify-content: space-between; align-items: center;">
                        <div>
                            <strong style="color: var(--primary-light);">{{ $c->code }}</strong> - {{ $c->title }}
                            <div style="font-size: 0.72rem; color: var(--gold-light);">Rujukan Kursus: IMO {{ $c->imoModelCourse?->code }} &bull; STCW: {{ $c->stcw_reference }}</div>
                        </div>
                    </div>
                @empty
                    <div style="color: var(--text-dim); font-size: 0.82rem;">Tidak ada data kompetensi terkait.</div>
                @endforelse
            </div>
        </div>

        <!-- Realization Section (Pencatatan Sesi) -->
        <div class="glass-card" style="margin-bottom: 1.5rem;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                <h3 style="font-size: 1.1rem; font-weight: 700; color: #fff;">
                    ⏱️ Realisasi Sesi Praktikum
                </h3>
                @if($booking->realization)
                    <span class="badge badge-emerald">Tercatat Selesai</span>
                @else
                    <span class="badge badge-slate">Belum Direalisasikan</span>
                @endif
            </div>

            @if($booking->realization)
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 1rem; font-size: 0.85rem;">
                    <div>
                        <span style="color: var(--text-dim); display: block; font-size: 0.75rem;">Waktu Aktual</span>
                        <strong>{{ $booking->realization->actual_start_at->format('H:i') }} - {{ $booking->realization->actual_end_at->format('H:i') }} WIB</strong>
                    </div>
                    <div>
                        <span style="color: var(--text-dim); display: block; font-size: 0.75rem;">Peserta Hadir Aktual</span>
                        <strong>{{ $booking->realization->actual_participant_count }} Orang</strong>
                    </div>
                    <div>
                        <span style="color: var(--text-dim); display: block; font-size: 0.75rem;">Kondisi Alat Paska Sesi</span>
                        <span class="badge badge-emerald">{{ $booking->realization->condition_after?->label() ?? 'Baik' }}</span>
                    </div>
                </div>
                @if($booking->realization->incident_note)
                    <div style="margin-top: 1rem; font-size: 0.82rem; color: var(--text-muted); background: rgba(0, 0, 0, 0.25); padding: 0.65rem; border-radius: 6px;">
                        <strong>Catatan Insiden / Kendala:</strong> {{ $booking->realization->incident_note }}
                    </div>
                @endif
            @elseif($booking->status->value === 'approved' || $booking->status->value === 'in_use')
                <p style="font-size: 0.82rem; color: var(--text-muted); margin-bottom: 1rem;">
                    Catat jam aktual pelaksanaan praktikum, jumlah peserta hadir, dan kondisi simulator setelah praktikum selesai.
                </p>
                <form action="{{ route('lab.realization.store', ['id' => $booking->id]) }}" method="POST">
                    @csrf
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
                        <div class="form-group">
                            <label class="form-label">Waktu Mulai Aktual</label>
                            <input type="datetime-local" name="actual_start_at" class="form-control" value="{{ $booking->start_at->format('Y-m-d\TH:i') }}" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Waktu Selesai Aktual</label>
                            <input type="datetime-local" name="actual_end_at" class="form-control" value="{{ $booking->end_at->format('Y-m-d\TH:i') }}" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Peserta Hadir (Orang)</label>
                            <input type="number" name="actual_participant_count" class="form-control" value="{{ $booking->participant_count }}" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Kondisi Alat Selesai Sesi</label>
                            <select name="condition_after" class="form-select" required>
                                <option value="good">Baik (Normal)</option>
                                <option value="minor_damage">Rusak Ringan (Ada Catatan)</option>
                                <option value="major_damage">Rusak Berat (Butuh Teknisi)</option>
                            </select>
                        </div>
                        <div class="form-group" style="grid-column: 1 / -1;">
                            <label class="form-label">Catatan Insiden / Kendala Teknis</label>
                            <input type="text" name="incident_note" class="form-control" placeholder="Kosongkan jika praktikum berjalan lancar">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-emerald" style="width: 100%;">
                        💾 Simpan Realisasi & Selesaikan Sesi Lab
                    </button>
                </form>
            @else
                <p style="font-size: 0.82rem; color: var(--text-dim);">
                    Pencatatan realisasi sesi dapat dilakukan setelah permohonan disetujui resmi oleh Kepala Unit SPP.
                </p>
            @endif
        </div>
    </div>

    <!-- Right Column: Status Log Timeline & Actions -->
    <div>
        <!-- Approval Stepper Actions -->
        @if($booking->status->value === 'submitted' || $booking->status->value === 'verified')
            <div class="glass-card" style="margin-bottom: 1.5rem; border-color: rgba(245, 158, 11, 0.4);">
                <h4 style="font-size: 0.95rem; font-weight: 700; color: var(--gold-light); margin-bottom: 0.75rem;">
                    ⚡ Tindakan Alur Persetujuan
                </h4>

                @if($booking->status->value === 'submitted')
                    <p style="font-size: 0.78rem; color: var(--text-muted); margin-bottom: 0.75rem;">
                        Tahap 1: Verifikasi kesiapan fasilitas & ketersediaan bahan oleh <strong>Petugas SPP</strong>.
                    </p>
                    <form action="{{ route('lab.status.update', ['id' => $booking->id]) }}" method="POST">
                        @csrf
                        <input type="hidden" name="action" value="verify">
                        <button type="submit" class="btn btn-primary" style="width: 100%; margin-bottom: 0.5rem;">
                            ✓ Lolos Verifikasi Petugas
                        </button>
                    </form>
                @elseif($booking->status->value === 'verified')
                    <p style="font-size: 0.78rem; color: var(--text-muted); margin-bottom: 0.75rem;">
                        Tahap 2: Persetujuan akhir oleh <strong>Kepala Unit SPP</strong>.
                    </p>
                    <form action="{{ route('lab.status.update', ['id' => $booking->id]) }}" method="POST">
                        @csrf
                        <input type="hidden" name="action" value="approve">
                        <button type="submit" class="btn btn-gold" style="width: 100%; margin-bottom: 0.5rem;">
                            ★ Setujui Permohonan (Kepala Unit)
                        </button>
                    </form>
                @endif

                <form action="{{ route('lab.status.update', ['id' => $booking->id]) }}" method="POST" style="margin-top: 0.5rem;">
                    @csrf
                    <input type="hidden" name="action" value="reject">
                    <input type="text" name="note" class="form-control" placeholder="Alasan penolakan..." style="font-size: 0.78rem; margin-bottom: 0.4rem;" required>
                    <button type="submit" class="btn btn-rose btn-sm" style="width: 100%;">
                        ✕ Tolak Permohonan
                    </button>
                </form>
            </div>
        @endif

        <!-- Audit Trail / Request Logs -->
        <div class="glass-card">
            <h4 style="font-size: 0.95rem; font-weight: 700; color: #fff; margin-bottom: 1rem;">
                📜 Riwayat Perubahan Status (Audit Trail)
            </h4>

            <div style="display: flex; flex-direction: column; gap: 0.85rem;">
                @forelse($booking->requestLogs as $log)
                    <div style="padding-left: 0.85rem; border-left: 2px solid var(--primary-light); position: relative; font-size: 0.8rem;">
                        <div style="font-weight: 700; color: #fff;">
                            {{ ucfirst($log->action) }} &rarr; <span class="badge badge-cyan" style="font-size: 0.65rem;">{{ $log->to_status }}</span>
                        </div>
                        <div style="font-size: 0.72rem; color: var(--text-dim); margin-top: 0.15rem;">
                            Oleh: {{ $log->role }} &bull; {{ $log->created_at->isoFormat('D MMM Y, H:i') }} WIB
                        </div>
                        @if($log->note)
                            <div style="color: var(--text-muted); font-size: 0.75rem; margin-top: 0.25rem;">
                                "{{ $log->note }}"
                            </div>
                        @endif
                    </div>
                @empty
                    <div style="color: var(--text-dim); font-size: 0.8rem;">Belum ada log tercatat.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
