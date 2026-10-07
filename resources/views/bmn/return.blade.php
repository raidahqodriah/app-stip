@extends('layouts.app')

@section('title', 'Form Pengembalian BMN & Barang Rusak')

@section('content')
<div style="margin-bottom: 2rem;">
    <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 1rem;">
        <div>
            <div style="display: inline-flex; align-items: center; gap: 0.5rem; color: #fb7185; font-size: 0.8rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.5rem;">
                <span>↩️ MODUL B: PENGEMBALIAN BMN</span> &bull; <span>TERMASUK BARANG RUSAK</span>
            </div>
            <h1 style="font-size: 1.85rem; font-weight: 800; color: #fff;">
                Formulir Pengembalian Barang Milik Negara
            </h1>
            <p style="color: var(--text-muted); font-size: 0.92rem; max-width: 650px;">
                Pengembalian barang dari ruangan ke pengelola BMN. Untuk barang berkondisi rusak atau hilang, wajib melampirkan kronologi kerusakan dan bukti fisik (§4.2).
            </p>
        </div>

        <div>
            <a href="{{ route('bmn.inventory') }}" class="btn btn-outline">
                📦 Kembali ke Inventaris
            </a>
        </div>
    </div>
</div>

<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem; align-items: start;">
    <!-- Return Form -->
    <div class="glass-card">
        <h3 style="font-size: 1.15rem; font-weight: 700; color: #fff; margin-bottom: 1.25rem;">
            ↩️ Data Pengembalian Barang
        </h3>

        <form action="{{ route('bmn.return.store') }}" method="POST">
            @csrf

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1rem;">
                <div class="form-group" style="grid-column: 1 / -1;">
                    <label class="form-label">Pilih Barang dari Inventaris Ruangan *</label>
                    <select name="bmn_item_id" class="form-select" required onchange="handleItemSelect(this)">
                        <option value="">-- Pilih Barang BMN yang Akan Dikembalikan --</option>
                        @foreach($items as $it)
                            <option value="{{ $it->id }}" {{ (old('bmn_item_id') == $it->id || request('item_id') == $it->id) ? 'selected' : '' }}>
                                {{ $it->item_name }} ({{ $it->bmn_code ?? 'Tanpa Kode' }}) &bull; Ruang: {{ $it->room?->name }} ({{ $it->unit?->name }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Tanggal Pengembalian *</label>
                    <input type="date" name="return_date" class="form-control" value="{{ old('return_date', date('Y-m-d')) }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Ruangan Tujuan / Gudang Penampungan</label>
                    <select name="destination_room_id" class="form-select">
                        <option value="">-- Gudang Sentral BMN STIP --</option>
                        @foreach($rooms as $rm)
                            <option value="{{ $rm->id }}" {{ old('destination_room_id') == $rm->id ? 'selected' : '' }}>
                                {{ $rm->code }} - {{ $rm->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Condition -->
                <div class="form-group" style="grid-column: 1 / -1;">
                    <label class="form-label">Kondisi Barang Saat Ini *</label>
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(130px, 1fr)); gap: 0.75rem;">
                        <label style="background: rgba(255, 255, 255, 0.03); border: 1px solid var(--border-subtle); padding: 0.75rem; border-radius: 8px; cursor: pointer; display: flex; align-items: center; gap: 0.5rem;">
                            <input type="radio" name="condition" value="good" {{ old('condition', 'good') == 'good' ? 'checked' : '' }} onchange="toggleDamageField(this)">
                            <div>
                                <strong style="color: #34d399; font-size: 0.82rem;">Baik</strong>
                                <div style="font-size: 0.7rem; color: var(--text-dim);">Berfungsi normal</div>
                            </div>
                        </label>

                        <label style="background: rgba(255, 255, 255, 0.03); border: 1px solid var(--border-subtle); padding: 0.75rem; border-radius: 8px; cursor: pointer; display: flex; align-items: center; gap: 0.5rem;">
                            <input type="radio" name="condition" value="minor_damage" {{ old('condition') == 'minor_damage' ? 'checked' : '' }} onchange="toggleDamageField(this)">
                            <div>
                                <strong style="color: #fbbf24; font-size: 0.82rem;">Rusak Ringan</strong>
                                <div style="font-size: 0.7rem; color: var(--text-dim);">Cacat fisik ringan</div>
                            </div>
                        </label>

                        <label style="background: rgba(255, 255, 255, 0.03); border: 1px solid var(--border-subtle); padding: 0.75rem; border-radius: 8px; cursor: pointer; display: flex; align-items: center; gap: 0.5rem;">
                            <input type="radio" name="condition" value="major_damage" {{ old('condition') == 'major_damage' ? 'checked' : '' }} onchange="toggleDamageField(this)">
                            <div>
                                <strong style="color: #fb7185; font-size: 0.82rem;">Rusak Berat</strong>
                                <div style="font-size: 0.7rem; color: var(--text-dim);">Mati total / pecah</div>
                            </div>
                        </label>

                        <label style="background: rgba(255, 255, 255, 0.03); border: 1px solid var(--border-subtle); padding: 0.75rem; border-radius: 8px; cursor: pointer; display: flex; align-items: center; gap: 0.5rem;">
                            <input type="radio" name="condition" value="lost" {{ old('condition') == 'lost' ? 'checked' : '' }} onchange="toggleDamageField(this)">
                            <div>
                                <strong style="color: #fda4af; font-size: 0.82rem;">Hilang</strong>
                                <div style="font-size: 0.7rem; color: var(--text-dim);">Tidak ditemukan</div>
                            </div>
                        </label>
                    </div>
                </div>

                <div class="form-group" style="grid-column: 1 / -1;">
                    <label class="form-label">Alasan Pengembalian *</label>
                    <textarea name="reason" class="form-control" rows="2" placeholder="Sebutkan alasan pengembalian (Contoh: Selesai masa penugasan, penggantian unit baru, dll.)" required>{{ old('reason') }}</textarea>
                </div>

                <!-- Mandatory Damage Note when condition is minor/major damage or lost -->
                <div id="damage_box" class="form-group" style="grid-column: 1 / -1; background: rgba(244, 63, 94, 0.08); border: 1px solid rgba(244, 63, 94, 0.3); padding: 1rem; border-radius: 8px; display: none;">
                    <label class="form-label" style="color: #fca5a5;">
                        ⚠️ Keterangan Kerusakan / Kronologi & Foto Fisik (Wajib untuk Barang Rusak/Hilang) *
                    </label>
                    <textarea name="damage_note" id="damage_note_input" class="form-control" rows="3" placeholder="Jelaskan secara detail bagian yang rusak, penyebab kerusakan, atau kronologi kehilangan...">{{ old('damage_note') }}</textarea>
                    <div style="margin-top: 0.75rem;">
                        <label class="form-label" style="font-size: 0.75rem; color: #fca5a5;">Unggah Foto Bukti Fisik Kerusakan (Max 5 MB)</label>
                        <input type="file" class="form-control" style="font-size: 0.8rem; padding: 0.4rem;">
                    </div>
                </div>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 1rem; margin-top: 1.5rem;">
                <a href="{{ route('bmn.inventory') }}" class="btn btn-outline">Batal</a>
                <button type="submit" class="btn btn-gold" style="padding: 0.75rem 2rem;">
                    🚀 Kirim Pengembalian BMN
                </button>
            </div>
        </form>
    </div>

    <!-- Right: Pending Returns & Verification -->
    <div>
        <div class="glass-card" style="margin-bottom: 1.5rem;">
            <h4 style="font-size: 0.95rem; font-weight: 700; color: #fff; margin-bottom: 1rem;">
                Daftar Pengembalian Menunggu Pemeriksaan
            </h4>

            <div style="display: flex; flex-direction: column; gap: 0.85rem;">
                @forelse($recentReturns as $ret)
                    <div style="background: rgba(255, 255, 255, 0.03); border: 1px solid var(--border-subtle); border-radius: 8px; padding: 0.85rem;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.35rem;">
                            <span style="font-size: 0.75rem; color: var(--gold-light); font-weight: 700;">
                                {{ $ret->return_date?->format('d/m/Y') }}
                            </span>
                            <span class="badge {{ $ret->status->value === 'completed' ? 'badge-emerald' : 'badge-gold' }}" style="font-size: 0.65rem;">
                                {{ $ret->status->label() }}
                            </span>
                        </div>
                        <div style="font-size: 0.85rem; font-weight: 700; color: #fff;">
                            {{ $ret->bmnItem?->item_name ?? 'Barang BMN' }}
                        </div>
                        <div style="font-size: 0.75rem; color: var(--text-dim); margin-top: 0.2rem;">
                            Kondisi: <strong style="color: #fb7185;">{{ $ret->condition->label() }}</strong>
                        </div>
                        @if($ret->damage_note)
                            <div style="font-size: 0.72rem; color: var(--text-muted); background: rgba(0, 0, 0, 0.2); padding: 0.4rem; border-radius: 4px; margin-top: 0.35rem;">
                                "{{ Str::limit($ret->damage_note, 50) }}"
                            </div>
                        @endif

                        <div style="display: flex; gap: 0.35rem; margin-top: 0.75rem;">
                            @if($ret->status->value !== 'completed')
                                <form action="{{ route('bmn.return.process', ['id' => $ret->id]) }}" method="POST" style="width: 100%;">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-primary" style="width: 100%;">
                                        ✓ Verifikasi & Terbitkan Tanda Terima
                                    </button>
                                </form>
                            @else
                                <a href="{{ route('documents.bmn_return', ['number' => $ret->id]) }}" class="btn btn-sm btn-emerald" style="width: 100%;">
                                    🖨️ Cetak Bukti Kembali
                                </a>
                            @endif
                        </div>
                    </div>
                @empty
                    <div style="color: var(--text-dim); font-size: 0.8rem;">Belum ada data pengembalian baru.</div>
                @endforelse
            </div>
        </div>

        <div class="glass-card" style="font-size: 0.8rem; color: var(--text-dim); line-height: 1.6;">
            <div style="font-weight: 700; color: var(--text-muted); margin-bottom: 0.35rem;">Aturan Integritas BMN (§4.2):</div>
            Data rekap dan perpindahan lokasi fisik barang diperbarui secara atomik setelah Petugas BMN menekan tombol verifikasi. Bukti Tanda Terima pengembalian dapat langsung dicetak sebagai dokumen BAP.
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function toggleDamageField(radioEl) {
        const box = document.getElementById('damage_box');
        const input = document.getElementById('damage_note_input');
        if (radioEl.value === 'minor_damage' || radioEl.value === 'major_damage' || radioEl.value === 'lost') {
            box.style.display = 'block';
            input.setAttribute('required', 'required');
        } else {
            box.style.display = 'none';
            input.removeAttribute('required');
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        const checkedRadio = document.querySelector('input[name="condition"]:checked');
        if (checkedRadio) {
            toggleDamageField(checkedRadio);
        }
    });
</script>
@endsection
