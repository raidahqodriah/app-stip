@extends('layouts.app')

@section('title', 'Jadwal & Ketersediaan Lab Publik')

@section('content')
<div style="margin-bottom: 2rem;">
    <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 1rem;">
        <div>
            <div style="display: inline-flex; align-items: center; gap: 0.5rem; color: var(--primary-light); font-size: 0.8rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.5rem;">
                <span>📅 KALENDER KETERSEDIAAN PUBLIK</span> &bull; <span>FIRST COME FIRST SERVED</span>
            </div>
            <h1 style="font-size: 1.85rem; font-weight: 800; color: #fff;">
                Jadwal Operasional Laboratorium & Simulator
            </h1>
            <p style="color: var(--text-muted); font-size: 0.92rem; max-width: 650px;">
                Informasi ketersediaan slot lab STIP Jakarta tanpa menampilkan identitas peminjam (memenuhi standar privasi §2.3). Slot diatur per 30 menit (07.30 - 16.00 WIB).
            </p>
        </div>

        <div style="display: flex; gap: 0.75rem;">
            <a href="{{ route('lab.booking', ['room_code' => $activeRoom?->code]) }}" class="btn btn-gold">
                📝 Ajukan Booking di Lab Ini
            </a>
            <a href="{{ route('public.lab.detail', ['code' => $activeRoom?->code ?? 'CHL']) }}" class="btn btn-outline">
                🔍 Spesifikasi Lab
            </a>
        </div>
    </div>
</div>

<!-- Filter Bar -->
<div class="glass-card" style="margin-bottom: 2rem; padding: 1.25rem;">
    <form method="GET" action="{{ route('public.schedule') }}" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)) auto; gap: 1rem; align-items: flex-end;">
        <div>
            <label class="form-label">Pilih Laboratorium / Simulator</label>
            <select name="room_id" class="form-select" onchange="this.form.submit()">
                @foreach($rooms as $r)
                    <option value="{{ $r->id }}" {{ $activeRoom?->id == $r->id ? 'selected' : '' }}>
                        {{ $r->code }} - {{ $r->name }} ({{ ucfirst($r->lab_category ?? 'Umum') }})
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="form-label">Tanggal Pelaksanaan</label>
            <input type="date" name="date" class="form-control" value="{{ $date->toDateString() }}" onchange="this.form.submit()">
        </div>

        <div>
            <button type="submit" class="btn btn-primary" style="width: 100%;">
                Filter Jadwal
            </button>
        </div>
    </form>
</div>

<!-- Active Room Info Banner -->
@if($activeRoom)
<div class="glass-card" style="margin-bottom: 2rem; background: linear-gradient(135deg, rgba(2, 132, 199, 0.12) 0%, rgba(13, 25, 43, 0.85) 100%); border-color: rgba(56, 189, 248, 0.2);">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
        <div style="display: flex; align-items: center; gap: 1.25rem;">
            <div style="width: 3.5rem; height: 3.5rem; background: rgba(2, 132, 199, 0.2); border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.6rem; color: var(--primary-light); border: 1px solid rgba(56, 189, 248, 0.3);">
                📡
            </div>
            <div>
                <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.25rem;">
                    <span class="badge badge-cyan">{{ $activeRoom->code }}</span>
                    <span class="badge badge-slate">Kategori {{ ucfirst($activeRoom->lab_category ?? 'Teknika/Nautika') }}</span>
                    <span class="badge badge-emerald">Kapasitas {{ $activeRoom->capacity ?? 30 }} Taruna</span>
                </div>
                <h2 style="font-size: 1.25rem; font-weight: 700; color: #fff;">{{ $activeRoom->name }}</h2>
                <p style="font-size: 0.82rem; color: var(--text-muted);">
                    Lokasi: {{ $activeRoom->location ?? 'Gedung Laboratorium Terpadu STIP' }} &bull; Jam Operasional: 07.30 - 16.00 WIB
                </p>
            </div>
        </div>

        <div style="text-align: right;">
            <div style="font-size: 0.78rem; color: var(--text-muted); margin-bottom: 0.25rem;">Hari & Tanggal Dipilih:</div>
            <div style="font-size: 1.1rem; font-weight: 700; color: var(--gold-light);">
                {{ $date->isoFormat('dddd, D MMMM Y') }}
            </div>
        </div>
    </div>
</div>
@endif

<!-- Time Slots Grid -->
<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1.5rem; align-items: start;">
    <!-- Slot Matrix (30 Mins) -->
    <div class="glass-card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem;">
            <h3 style="font-size: 1.1rem; font-weight: 700; color: #fff;">
                Matriks Slot Waktu (Interval 30 Menit)
            </h3>
            <div style="display: flex; gap: 0.75rem; font-size: 0.75rem;">
                <span style="display: flex; align-items: center; gap: 0.35rem;">
                    <span style="width: 10px; height: 10px; border-radius: 50%; background: #10b981; display: inline-block;"></span> Tersedia
                </span>
                <span style="display: flex; align-items: center; gap: 0.35rem;">
                    <span style="width: 10px; height: 10px; border-radius: 50%; background: #ef4444; display: inline-block;"></span> Terisi / Booking
                </span>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 0.75rem;">
            @foreach($timeSlots as $slot)
                <div style="background: {{ $slot['is_booked'] ? 'rgba(239, 68, 68, 0.08)' : 'rgba(16, 185, 129, 0.06)' }}; 
                            border: 1px solid {{ $slot['is_booked'] ? 'rgba(239, 68, 68, 0.3)' : 'rgba(16, 185, 129, 0.2)' }}; 
                            border-radius: 10px; padding: 0.85rem 1rem; display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <div style="font-family: 'JetBrains Mono', monospace; font-size: 0.95rem; font-weight: 700; color: {{ $slot['is_booked'] ? '#fca5a5' : '#86efac' }};">
                            {{ $slot['start'] }} - {{ $slot['end'] }}
                        </div>
                        <div style="font-size: 0.75rem; color: var(--text-dim); margin-top: 0.2rem;">
                            @if($slot['is_booked'])
                                <span style="color: #fca5a5;">🔒 Sesi: {{ Str::limit($slot['subject'] ?? 'Praktikum Terjadwal', 24) }}</span>
                            @else
                                <span style="color: #86efac;">✓ Slot Kosong (Siap Booking)</span>
                            @endif
                        </div>
                    </div>

                    <div>
                        @if($slot['is_booked'])
                            <span class="badge badge-rose">{{ $slot['status'] }}</span>
                        @else
                            <a href="{{ route('lab.booking', ['room_code' => $activeRoom?->code, 'date' => $date->toDateString(), 'start' => $slot['start']]) }}" 
                               class="btn btn-sm btn-emerald" style="padding: 0.25rem 0.6rem; font-size: 0.72rem;">
                                Pilih Slot
                            </a>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Weekly Timeline Preview -->
    <div class="glass-card">
        <h3 style="font-size: 1.05rem; font-weight: 700; color: #fff; margin-bottom: 1rem;">
            Sesi Terjadwal Minggu Ini
        </h3>

        @if($weekBookings->isEmpty())
            <div style="text-align: center; padding: 2.5rem 1rem; color: var(--text-dim);">
                <div style="font-size: 2rem; margin-bottom: 0.5rem;">🏖️</div>
                <p>Belum ada sesi praktikum terjadwal pada minggu ini.</p>
            </div>
        @else
            <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                @foreach($weekBookings as $wb)
                    <div style="background: rgba(255, 255, 255, 0.03); border: 1px solid var(--border-subtle); border-radius: 8px; padding: 0.75rem 0.9rem;">
                        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 0.25rem;">
                            <span style="font-size: 0.78rem; font-weight: 700; color: var(--primary-light);">
                                {{ $wb->start_at->isoFormat('dddd, D MMM') }}
                            </span>
                            <span class="badge badge-gold" style="font-size: 0.65rem;">{{ $wb->status->label() }}</span>
                        </div>
                        <div style="font-size: 0.85rem; font-weight: 600; color: #fff;">
                            {{ $wb->subject?->name ?? 'Praktikum Lab' }}
                        </div>
                        <div style="font-size: 0.72rem; color: var(--text-muted); font-family: 'JetBrains Mono', monospace; margin-top: 0.2rem;">
                            ⏰ {{ $wb->start_at->format('H:i') }} - {{ $wb->end_at->format('H:i') }} WIB ({{ $wb->participant_count }} Peserta)
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        <div style="margin-top: 1.5rem; padding-top: 1rem; border-top: 1px solid var(--border-subtle); font-size: 0.78rem; color: var(--text-dim);">
            <strong>Ketentuan Anti-Bentrok:</strong> Pengajuan booking diverifikasi otomatis di tingkat database. Slot yang bentrok akan langsung ditolak sesuai prinsip FCFS (§3.3).
        </div>
    </div>
</div>
@endsection
