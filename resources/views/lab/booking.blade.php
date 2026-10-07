@extends('layouts.app')

@section('title', 'Form Permohonan Booking Lab / Simulator SPP')

@section('content')
<div style="margin-bottom: 2rem;">
    <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 1rem;">
        <div>
            <div style="display: inline-flex; align-items: center; gap: 0.5rem; color: var(--gold-light); font-size: 0.8rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.5rem;">
                <span>⚓ MODUL A: LAB & SIMULATOR SPP</span> &bull; <span>ALUR 3 LANGKAH</span>
            </div>
            <h1 style="font-size: 1.85rem; font-weight: 800; color: #fff;">
                Form Permohonan Booking Laboratorium
            </h1>
            <p style="color: var(--text-muted); font-size: 0.92rem; max-width: 650px;">
                Isi data permohonan penggunaan simulator. Wajib mengaitkan mata kuliah dengan minimal 1 kompetensi IMO Model Course sesuai standar STCW (§3.3 Aturan 10).
            </p>
        </div>

        <div>
            <a href="{{ route('lab.monitoring') }}" class="btn btn-outline">
                📊 Lihat Status Booking Saya
            </a>
        </div>
    </div>
</div>

<form action="{{ route('lab.booking.store') }}" method="POST">
    @csrf

    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem; align-items: start;">
        <!-- Left: Form Steps -->
        <div>
            <!-- Step 1: Ruangan & Waktu -->
            <div class="glass-card" style="margin-bottom: 1.5rem; position: relative; overflow: hidden;">
                <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1.25rem;">
                    <div style="width: 2rem; height: 2rem; border-radius: 50%; background: var(--primary); color: #fff; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 0.9rem;">
                        1
                    </div>
                    <div>
                        <h3 style="font-size: 1.1rem; font-weight: 700; color: #fff;">Pilih Laboratorium & Waktu Sesi (FCFS)</h3>
                        <p style="font-size: 0.78rem; color: var(--text-dim);">Slot operasional Senin–Jumat 07.30–16.00 WIB (interval 30 menit)</p>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1rem;">
                    <div class="form-group" style="grid-column: 1 / -1;">
                        <label class="form-label">Laboratorium / Simulator Target *</label>
                        <select name="room_id" id="room_select" class="form-select" required onchange="handleRoomChange(this)">
                            <option value="">-- Pilih Fasilitas Lab --</option>
                            @foreach($rooms as $r)
                                <option value="{{ $r->id }}" 
                                        data-capacity="{{ $r->capacity ?? 30 }}" 
                                        data-code="{{ $r->code }}"
                                        {{ (old('room_id') == $r->id || (isset($preselectedRoom) && $preselectedRoom->id == $r->id)) ? 'selected' : '' }}>
                                    {{ $r->code }} - {{ $r->name }} (Maks. {{ $r->capacity ?? 30 }} Peserta)
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Tanggal Pelaksanaan *</label>
                        <input type="date" name="booking_date" class="form-control" 
                               value="{{ old('booking_date', request('date', date('Y-m-d', strtotime('+2 days')))) }}" 
                               min="{{ date('Y-m-d') }}" required>
                        <span class="form-hint">Lead time min. 3 hari kerja sebelum pelaksanaan</span>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Jam Mulai Sesi *</label>
                        <select name="start_time" class="form-select" required>
                            @foreach(['07:30', '08:00', '08:30', '09:00', '09:30', '10:00', '10:30', '11:00', '11:30', '13:00', '13:30', '14:00', '14:30', '15:00'] as $t)
                                <option value="{{ $t }}:00" {{ old('start_time', request('start', '08:00:00')) == $t.':00' ? 'selected' : '' }}>
                                    {{ $t }} WIB
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Jam Selesai Sesi *</label>
                        <select name="end_time" class="form-select" required>
                            @foreach(['08:30', '09:00', '09:30', '10:00', '10:30', '11:00', '11:30', '12:00', '13:30', '14:00', '14:30', '15:00', '15:30', '16:00'] as $t)
                                <option value="{{ $t }}:00" {{ old('end_time', '11:00:00') == $t.':00' ? 'selected' : '' }}>
                                    {{ $t }} WIB
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <!-- Step 2: Mata Kuliah & IMO Course -->
            <div class="glass-card" style="margin-bottom: 1.5rem;">
                <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1.25rem;">
                    <div style="width: 2rem; height: 2rem; border-radius: 50%; background: var(--gold); color: #000; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 0.9rem;">
                        2
                    </div>
                    <div>
                        <h3 style="font-size: 1.1rem; font-weight: 700; color: #fff;">Mata Kuliah & Kompetensi IMO Model Course</h3>
                        <p style="font-size: 0.78rem; color: var(--text-dim);">Pilih mata kuliah untuk memuat rujukan IMO & kit bahan default</p>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Mata Kuliah Praktikum *</label>
                    <select name="subject_id" id="subject_select" class="form-select" required onchange="handleSubjectChange(this)">
                        <option value="">-- Pilih Mata Kuliah --</option>
                        @foreach($subjects as $s)
                            <option value="{{ $s->id }}" 
                                    data-competences="{{ json_encode($s->competences) }}"
                                    {{ old('subject_id') == $s->id ? 'selected' : '' }}>
                                {{ $s->code }} - {{ $s->name }} (Prodi {{ strtoupper($s->category) }}, Smt {{ $s->semester ?? '-' }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label" style="display: flex; justify-content: space-between;">
                        <span>Kompetensi IMO Terkait * (Pilih Minimal 1)</span>
                        <span style="color: var(--primary-light); font-size: 0.75rem;">Regulasi STCW 1978/2010</span>
                    </label>
                    <div id="competence_container" style="display: flex; flex-direction: column; gap: 0.6rem; max-height: 220px; overflow-y: auto; padding: 0.5rem; background: rgba(0, 0, 0, 0.25); border-radius: 8px; border: 1px solid var(--border-subtle);">
                        <p style="font-size: 0.82rem; color: var(--text-dim); text-align: center; padding: 1rem;">
                            Silakan pilih Mata Kuliah di atas terlebih dahulu untuk memuat daftar kompetensi IMO.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Step 3: Peserta & Dosen PJ -->
            <div class="glass-card" style="margin-bottom: 1.5rem;">
                <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1.25rem;">
                    <div style="width: 2rem; height: 2rem; border-radius: 50%; background: #10b981; color: #fff; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 0.9rem;">
                        3
                    </div>
                    <div>
                        <h3 style="font-size: 1.1rem; font-weight: 700; color: #fff;">Peserta, Dosen Penanggung Jawab & Catatan</h3>
                        <p style="font-size: 0.78rem; color: var(--text-dim);">Identitas peleton taruna dan dosen pendamping sesi praktikum</p>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1rem;">
                    <div class="form-group">
                        <label class="form-label">Unit Pengusul / Jurusan *</label>
                        <select name="unit_id" class="form-select" required>
                            @foreach($units as $u)
                                <option value="{{ $u->id }}" {{ old('unit_id') == $u->id ? 'selected' : '' }}>
                                    {{ $u->name }} ({{ $u->code }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Dosen Penanggung Jawab *</label>
                        <select name="responsible_lecturer_id" class="form-select" required>
                            <option value="">-- Pilih Dosen Penanggung Jawab --</option>
                            @foreach($lecturers as $lec)
                                <option value="{{ $lec->id }}" {{ old('responsible_lecturer_id') == $lec->id ? 'selected' : '' }}>
                                    {{ $lec->name }} - {{ $lec->employee_number }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Peleton / Kelas / Tingkat *</label>
                        <input type="text" name="class_group" class="form-control" placeholder="Contoh: Nautika VII-A" value="{{ old('class_group', 'Nautika VII-A') }}" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Jumlah Peserta (Orang) *</label>
                        <input type="number" name="participant_count" id="participant_input" class="form-control" min="1" max="100" value="{{ old('participant_count', 25) }}" required>
                    </div>

                    <div class="form-group" style="grid-column: 1 / -1;">
                        <label class="form-label">Tujuan Praktikum / Deskripsi Modul *</label>
                        <input type="text" name="purpose" class="form-control" placeholder="Contoh: Pengoperasian Simulator Derek Palka dan Radar ARPA" value="{{ old('purpose') }}" required>
                    </div>

                    <div class="form-group" style="grid-column: 1 / -1;">
                        <label class="form-label">Catatan Tambahan & Kebutuhan Bahan (Opsional)</label>
                        <textarea name="notes" class="form-control" rows="3" placeholder="Sebutkan peralatan pelengkap atau software modul khusus yang dibutuhkan...">{{ old('notes') }}</textarea>
                    </div>
                </div>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 1rem;">
                <a href="{{ route('home') }}" class="btn btn-outline">Batal</a>
                <button type="submit" class="btn btn-gold" style="padding: 0.75rem 2rem; font-size: 0.95rem;">
                    🚀 Kirim Permohonan Booking Lab
                </button>
            </div>
        </div>

        <!-- Right: Real-time Rule Check & Kit Preview -->
        <div>
            <!-- Summary Box -->
            <div class="glass-card" style="margin-bottom: 1.5rem; border-color: rgba(2, 132, 199, 0.3);">
                <h4 style="font-size: 0.95rem; font-weight: 700; color: #fff; margin-bottom: 0.75rem; display: flex; align-items: center; gap: 0.4rem;">
                    <span>🛡️</span> Validasi Sistem FCFS
                </h4>
                <ul style="font-size: 0.8rem; color: var(--text-muted); padding-left: 1.2rem; line-height: 1.7;">
                    <li><strong>Anti-Bentrok:</strong> Slot waktu dikunci otomatis di database dengan constraint unik.</li>
                    <li><strong>Alur Persetujuan:</strong> Petugas SPP verifikasi alat & bahan &rarr; Kepala Unit SPP menyetujui.</li>
                    <li><strong>Dokumen Sah:</strong> Konfirmasi booking PDF terbit otomatis setelah disetujui.</li>
                    <li><strong>Batal / Expired:</strong> Jika ditolak atau lewat SLA, slot langsung dilepas kembali.</li>
                </ul>
            </div>

            <!-- Material Kit Preview -->
            <div class="glass-card" style="margin-bottom: 1.5rem;">
                <h4 style="font-size: 0.95rem; font-weight: 700; color: #fff; margin-bottom: 0.75rem;">
                    📦 Kit Bahan Default
                </h4>
                <div style="font-size: 0.8rem; color: var(--text-dim); line-height: 1.6;" id="material_kit_preview">
                    Kit bahan akan disesuaikan secara otomatis saat mata kuliah dan laboratorium dipilih.
                </div>
            </div>

            <!-- Support Contacts -->
            <div class="glass-card" style="background: rgba(255, 255, 255, 0.02); font-size: 0.8rem; color: var(--text-dim);">
                <div style="font-weight: 700; color: var(--text-muted); margin-bottom: 0.25rem;">Bantuan Operasional SPP</div>
                <div>Kantor PLP Unit SPP: Ext. 204 / 205</div>
                <div>Email: spp@stipjakarta.ac.id</div>
            </div>
        </div>
    </div>
</form>
@endsection

@section('scripts')
<script>
    const subjectsData = @json($subjects);

    function handleSubjectChange(selectEl) {
        const subjectId = selectEl.value;
        const container = document.getElementById('competence_container');
        container.innerHTML = '';

        if (!subjectId) {
            container.innerHTML = '<p style="font-size: 0.82rem; color: var(--text-dim); text-align: center; padding: 1rem;">Silakan pilih Mata Kuliah di atas terlebih dahulu.</p>';
            return;
        }

        const selected = subjectsData.find(s => s.id == subjectId);
        if (selected && selected.competences && selected.competences.length > 0) {
            selected.competences.forEach((comp, idx) => {
                const imoCode = comp.imo_model_course ? comp.imo_model_course.code : 'IMO';
                const label = document.createElement('label');
                label.style.display = 'flex';
                label.style.alignItems = 'flex-start';
                label.style.gap = '0.6rem';
                label.style.fontSize = '0.82rem';
                label.style.color = '#e2e8f0';
                label.style.cursor = 'pointer';
                label.style.padding = '0.35rem 0.5rem';
                label.style.borderRadius = '6px';
                label.style.background = 'rgba(255, 255, 255, 0.03)';

                label.innerHTML = `
                    <input type="checkbox" name="competence_ids[]" value="${comp.id}" ${idx === 0 ? 'checked' : ''} style="margin-top: 0.2rem;">
                    <div>
                        <strong style="color: var(--primary-light);">[IMO ${imoCode}] ${comp.code}</strong> - ${comp.title}
                        <div style="font-size: 0.72rem; color: var(--gold-light);">STCW Ref: ${comp.stcw_reference || 'Reg. A-II/1'}</div>
                    </div>
                `;
                container.appendChild(label);
            });
        } else {
            container.innerHTML = '<p style="font-size: 0.82rem; color: #f87171; padding: 0.5rem;">⚠️ Mata kuliah ini belum memiliki pemetaan IMO Model Course! Hubungi Kaprodi terkait.</p>';
        }
    }

    // Auto trigger on load if subject was preselected
    document.addEventListener('DOMContentLoaded', () => {
        const subjSelect = document.getElementById('subject_select');
        if (subjSelect && subjSelect.value) {
            handleSubjectChange(subjSelect);
        }
    });
</script>
@endsection
