@extends('layouts.app')

@section('title', 'Katalog Lab ' . $room->code . ' - ' . $room->name)

@section('content')
<div style="margin-bottom: 2rem;">
    <a href="{{ route('public.schedule') }}" style="color: var(--primary-light); text-decoration: none; font-size: 0.82rem; display: inline-flex; align-items: center; gap: 0.35rem; margin-bottom: 0.75rem;">
        &larr; Kembali ke Jadwal & Katalog Publik
    </a>

    <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 1.5rem;">
        <div>
            <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem;">
                <span class="badge badge-cyan">{{ $room->code }}</span>
                <span class="badge badge-slate">Kategori {{ ucfirst($room->lab_category ?? 'Semua Prodi') }}</span>
                <span class="badge badge-emerald">Aktif & Operasional</span>
            </div>
            <h1 style="font-size: 2rem; font-weight: 800; color: #fff;">
                {{ $room->name }}
            </h1>
            <p style="color: var(--text-muted); font-size: 0.95rem; margin-top: 0.25rem;">
                Unit Pengelola: {{ $room->unit?->name ?? 'Unit Sarana Praktik Pelaut (SPP)' }} &bull; Lokasi: {{ $room->location ?? 'Gedung SPP Lantai 2' }}
            </p>
        </div>

        <div style="display: flex; gap: 0.75rem;">
            <a href="{{ route('public.schedule', ['room_id' => $room->id]) }}" class="btn btn-outline">
                📅 Cek Kalender Slot
            </a>
            <a href="{{ route('lab.booking', ['room_code' => $room->code]) }}" class="btn btn-gold">
                📝 Booking Simulator Ini
            </a>
        </div>
    </div>
</div>

<!-- Specs Grid -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1rem; margin-bottom: 2rem;">
    <div class="glass-card" style="padding: 1.25rem;">
        <div style="font-size: 0.75rem; color: var(--text-dim); text-transform: uppercase;">Kapasitas Ruangan</div>
        <div style="font-size: 1.5rem; font-weight: 800; color: #fff; margin-top: 0.25rem;">
            {{ $room->capacity ?? 30 }} <span style="font-size: 0.85rem; font-weight: 500; color: var(--text-muted);">Taruna/Sesi</span>
        </div>
    </div>
    <div class="glass-card" style="padding: 1.25rem;">
        <div style="font-size: 0.75rem; color: var(--text-dim); text-transform: uppercase;">Penanggung Jawab (PIC)</div>
        <div style="font-size: 1.15rem; font-weight: 700; color: var(--primary-light); margin-top: 0.25rem;">
            {{ $room->pic?->name ?? 'Pranata Lab Pendidikan (PLP)' }}
        </div>
    </div>
    <div class="glass-card" style="padding: 1.25rem;">
        <div style="font-size: 0.75rem; color: var(--text-dim); text-transform: uppercase;">Jam Operasional</div>
        <div style="font-size: 1.15rem; font-weight: 700; color: #fff; margin-top: 0.25rem;">
            07.30 - 16.00 <span style="font-size: 0.8rem; color: var(--text-muted);">WIB</span>
        </div>
    </div>
    <div class="glass-card" style="padding: 1.25rem;">
        <div style="font-size: 0.75rem; color: var(--text-dim); text-transform: uppercase;">Aturan Penjadwalan</div>
        <div style="font-size: 1.15rem; font-weight: 700; color: var(--gold-light); margin-top: 0.25rem;">
            FCFS Slot 30 Mnt
        </div>
    </div>
</div>

<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem; align-items: start;">
    <!-- Left Column: Curriculum & Competences -->
    <div>
        <!-- IMO Model Courses & Competences -->
        <div class="glass-card" style="margin-bottom: 2rem;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem;">
                <h3 style="font-size: 1.15rem; font-weight: 700; color: #fff;">
                    ⚓ Keselarasan IMO Model Course & Regulasi STCW
                </h3>
                <span class="badge badge-gold">Wajib Diisi Saat Booking</span>
            </div>
            <p style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 1.25rem;">
                Berdasarkan regulasi PRD §3.3 Aturan 10, setiap permohonan booking wajib terhubung ke minimal satu kompetensi IMO Model Course yang telah diverifikasi Kaprodi.
            </p>

            <div style="display: flex; flex-direction: column; gap: 1rem;">
                @php
                    $allCompetences = $room->subjects->flatMap->competences->unique('id');
                @endphp

                @if($allCompetences->isEmpty())
                    <div style="padding: 1.5rem; background: rgba(255, 255, 255, 0.02); border-radius: 8px; text-align: center; color: var(--text-dim); font-size: 0.85rem;">
                        Kompetensi IMO dikaitkan dinamis melalui mata kuliah yang diajukan.
                    </div>
                @else
                    @foreach($allCompetences as $comp)
                        <div style="background: rgba(255, 255, 255, 0.03); border: 1px solid var(--border-subtle); border-radius: 10px; padding: 1rem;">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.35rem;">
                                <span class="badge badge-cyan">IMO {{ $comp->imoModelCourse?->code ?? 'IMO' }}</span>
                                <span style="font-size: 0.75rem; color: var(--gold-light); font-family: 'JetBrains Mono', monospace;">
                                    STCW: {{ $comp->stcw_reference ?? 'Reg. A-II/1' }}
                                </span>
                            </div>
                            <div style="font-size: 0.92rem; font-weight: 700; color: #fff;">
                                {{ $comp->code }} - {{ $comp->title }}
                            </div>
                            <div style="font-size: 0.78rem; color: var(--text-muted); margin-top: 0.25rem;">
                                Rujukan: {{ $comp->imoModelCourse?->title ?? 'IMO Model Course Series' }}
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>

        <!-- Mata Kuliah Terkait -->
        <div class="glass-card" style="margin-bottom: 2rem;">
            <h3 style="font-size: 1.15rem; font-weight: 700; color: #fff; margin-bottom: 1rem;">
                📖 Mata Kuliah yang Menggunakan Lab Ini
            </h3>
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); gap: 0.75rem;">
                @forelse($room->subjects as $subj)
                    <div style="background: rgba(255, 255, 255, 0.03); border: 1px solid var(--border-subtle); border-radius: 8px; padding: 0.85rem;">
                        <span class="badge badge-slate" style="font-size: 0.65rem;">{{ $subj->code }}</span>
                        <div style="font-weight: 700; color: #fff; margin-top: 0.35rem; font-size: 0.88rem;">
                            {{ $subj->name }}
                        </div>
                        <div style="font-size: 0.75rem; color: var(--text-dim); margin-top: 0.2rem;">
                            Semester {{ $subj->semester ?? '-' }} &bull; {{ $subj->credits ?? 3 }} SKS ({{ ucfirst($subj->category) }})
                        </div>
                    </div>
                @empty
                    <div style="grid-column: 1/-1; color: var(--text-dim); font-size: 0.85rem;">
                        Belum ada pemetaan mata kuliah langsung pada lab ini.
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Fasilitas & Kit Bahan -->
        <div class="glass-card">
            <h3 style="font-size: 1.15rem; font-weight: 700; color: #fff; margin-bottom: 1rem;">
                🧰 Fasilitas & Bahan Praktikum
            </h3>
            <div class="table-responsive">
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th>Nama Fasilitas / Bahan</th>
                            <th>Tipe</th>
                            <th>Satuan</th>
                            <th>Ketersediaan Stok</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($room->materials as $mat)
                            <tr>
                                <td style="font-weight: 600;">{{ $mat->name }}</td>
                                <td>
                                    <span class="badge badge-cyan">{{ ucfirst($mat->type) }}</span>
                                </td>
                                <td>{{ $mat->unit }}</td>
                                <td>
                                    <strong style="color: #34d399;">{{ $mat->stock_qty }} {{ $mat->unit }}</strong>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" style="text-align: center; color: var(--text-dim);">
                                    Tidak ada peralatan atau kit khusus yang terdaftar.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Right Column: Upcoming Sessions & Actions -->
    <div>
        <div class="glass-card" style="margin-bottom: 1.5rem;">
            <h3 style="font-size: 1.05rem; font-weight: 700; color: #fff; margin-bottom: 1rem;">
                Sesi Praktikum Akan Datang
            </h3>

            @if($upcomingBookings->isEmpty())
                <div style="text-align: center; padding: 2rem 1rem; color: var(--text-dim); font-size: 0.85rem;">
                    Belum ada jadwal sesi disetujui dalam waktu dekat.
                </div>
            @else
                <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                    @foreach($upcomingBookings as $ub)
                        <div style="background: rgba(255, 255, 255, 0.03); border: 1px solid var(--border-subtle); border-radius: 8px; padding: 0.85rem;">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.25rem;">
                                <span style="font-size: 0.75rem; color: var(--primary-light); font-weight: 700;">
                                    {{ $ub->start_at->isoFormat('D MMM Y') }}
                                </span>
                                <span class="badge badge-emerald" style="font-size: 0.65rem;">{{ $ub->status->label() }}</span>
                            </div>
                            <div style="font-size: 0.85rem; font-weight: 700; color: #fff;">
                                {{ $ub->subject?->name ?? 'Praktikum' }}
                            </div>
                            <div style="font-size: 0.75rem; color: var(--text-muted); margin-top: 0.2rem;">
                                Dosen PJ: {{ $ub->responsibleLecturer?->name ?? 'Dosen Pengampu' }}
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            <div style="margin-top: 1.5rem;">
                <a href="{{ route('lab.booking', ['room_code' => $room->code]) }}" class="btn btn-gold" style="width: 100%;">
                    Ajukan Booking Sekarang
                </a>
            </div>
        </div>

        <div class="glass-card" style="background: rgba(2, 132, 199, 0.08); border-color: rgba(2, 132, 199, 0.25);">
            <h4 style="font-size: 0.9rem; font-weight: 700; color: var(--primary-light); margin-bottom: 0.5rem;">
                💡 Prosedur Verifikasi Dua Tahap
            </h4>
            <p style="font-size: 0.78rem; color: var(--text-muted); line-height: 1.5;">
                Pengajuan lab akan melalui pemeriksaan kesiapan alat oleh <strong>Petugas SPP</strong> (Tahap 1), lalu disetujui resmi oleh <strong>Kepala Unit SPP</strong> (Tahap 2). Setelah disetujui, surat konfirmasi booking siap dicetak otomatis.
            </p>
        </div>
    </div>
</div>
@endsection
