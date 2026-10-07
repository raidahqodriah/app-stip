@extends('layouts.app')

@section('title', 'Loket Sirkulasi Peminjaman & Pengembalian Buku')

@section('content')
<div style="margin-bottom: 2rem;">
    <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 1rem;">
        <div>
            <div style="display: inline-flex; align-items: center; gap: 0.5rem; color: var(--gold-light); font-size: 0.8rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.5rem;">
                <span>📚 MODUL D: LOKET SIRKULASI</span> &bull; <span>OPERASIONAL PERPUSTAKAAN</span>
            </div>
            <h1 style="font-size: 1.85rem; font-weight: 800; color: #fff;">
                Loket Peminjaman, Pengembalian & Denda
            </h1>
            <p style="color: var(--text-muted); font-size: 0.92rem; max-width: 650px;">
                Masa pinjam standar 7 hari kalender. Kuota: 3 buku (taruna), 5 buku (dosen/pegawai). Denda otomatis Rp 1.000 / hari / buku terlambat (§6.3).
            </p>
        </div>

        <div>
            <a href="{{ route('library.catalog') }}" class="btn btn-outline">
                📖 Katalog Koleksi Buku
            </a>
        </div>
    </div>
</div>

<!-- Stats Bar -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 1rem; margin-bottom: 2rem;">
    <div class="glass-card" style="padding: 1.25rem;">
        <div style="font-size: 0.75rem; color: var(--text-dim); text-transform: uppercase;">Peminjaman Aktif</div>
        <div style="font-size: 1.6rem; font-weight: 800; color: #fff; margin-top: 0.2rem;">{{ $stats['active_loans_count'] }}</div>
    </div>
    <div class="glass-card" style="padding: 1.25rem;">
        <div style="font-size: 0.75rem; color: #fb7185; text-transform: uppercase;">Melewati Jatuh Tempo</div>
        <div style="font-size: 1.6rem; font-weight: 800; color: #fb7185; margin-top: 0.2rem;">{{ $stats['overdue_count'] }}</div>
    </div>
    <div class="glass-card" style="padding: 1.25rem;">
        <div style="font-size: 0.75rem; color: var(--gold-light); text-transform: uppercase;">Total Denda Terhitung</div>
        <div style="font-size: 1.6rem; font-weight: 800; color: var(--gold-light); margin-top: 0.2rem;">
            Rp {{ number_format($stats['total_fines'], 0, ',', '.') }}
        </div>
    </div>
    <div class="glass-card" style="padding: 1.25rem;">
        <div style="font-size: 0.75rem; color: #34d399; text-transform: uppercase;">Kembali Hari Ini</div>
        <div style="font-size: 1.6rem; font-weight: 800; color: #34d399; margin-top: 0.2rem;">{{ $stats['returned_today'] }}</div>
    </div>
</div>

<!-- Tab Navigation -->
<div style="display: flex; gap: 0.5rem; margin-bottom: 1.5rem; border-bottom: 1px solid var(--border-subtle); padding-bottom: 0.5rem;">
    <a href="{{ route('library.circulation', ['tab' => 'loans']) }}" 
       class="btn {{ $tab === 'loans' ? 'btn-primary' : 'btn-outline' }}" style="border-radius: 8px;">
        ➕ Form Peminjaman Baru
    </a>
    <a href="{{ route('library.circulation', ['tab' => 'returns']) }}" 
       class="btn {{ $tab === 'returns' ? 'btn-gold' : 'btn-outline' }}" style="border-radius: 8px;">
        🔄 Loket Pengembalian & Denda ({{ $activeLoans->count() }})
    </a>
    <a href="{{ route('library.circulation', ['tab' => 'history']) }}" 
       class="btn {{ $tab === 'history' ? 'btn-primary' : 'btn-outline' }}" style="border-radius: 8px;">
        📜 Riwayat Transaksi Selesai
    </a>
</div>

@if($tab === 'loans')
    <!-- Tab 1: New Loan Form -->
    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem; align-items: start;">
        <div class="glass-card">
            <h3 style="font-size: 1.15rem; font-weight: 700; color: #fff; margin-bottom: 1.25rem;">
                📝 Proses Peminjaman Buku di Loket
            </h3>

            <form action="{{ route('library.loan.store') }}" method="POST">
                @csrf

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1rem;">
                    <!-- Borrower Type -->
                    <div class="form-group" style="grid-column: 1 / -1;">
                        <label class="form-label">Tipe Anggota Peminjam *</label>
                        <div style="display: flex; gap: 1.5rem;">
                            <label style="display: flex; align-items: center; gap: 0.5rem; color: #fff; cursor: pointer;">
                                <input type="radio" name="borrower_type" value="student" checked onchange="toggleBorrowerList(this)">
                                🎓 Taruna / Peserta Diklat (Maks. 3 Buku)
                            </label>
                            <label style="display: flex; align-items: center; gap: 0.5rem; color: #fff; cursor: pointer;">
                                <input type="radio" name="borrower_type" value="employee" onchange="toggleBorrowerList(this)">
                                👨‍🏫 Dosen / Pegawai STIP (Maks. 5 Buku)
                            </label>
                        </div>
                    </div>

                    <!-- Borrower Selection -->
                    <div class="form-group" id="student_select_group" style="grid-column: 1 / -1;">
                        <label class="form-label">Cari & Pilih Taruna (Nama / NIT) *</label>
                        <select name="borrower_id" id="student_borrower_id" class="form-select" required>
                            <option value="">-- Pilih Taruna --</option>
                            @foreach($students as $st)
                                <option value="{{ $st->id }}">{{ $st->name }} (NIT: {{ $st->student_number }}) - Peleton {{ $st->class_group ?? '-' }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group" id="employee_select_group" style="grid-column: 1 / -1; display: none;">
                        <label class="form-label">Cari & Pilih Pegawai / Dosen (Nama / NIP) *</label>
                        <select id="employee_borrower_id" class="form-select">
                            <option value="">-- Pilih Pegawai / Dosen --</option>
                            @foreach($employees as $emp)
                                <option value="{{ $emp->id }}">{{ $emp->name }} (NIP: {{ $emp->employee_number }}) - {{ $emp->position ?? 'Dosen' }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Book Selection -->
                    <div class="form-group" style="grid-column: 1 / -1;">
                        <label class="form-label">Pilih Buku (Stok Tersedia) *</label>
                        <select name="book_id" class="form-select" required>
                            <option value="">-- Pilih Judul Buku dari Rak --</option>
                            @foreach($books as $bk)
                                <option value="{{ $bk->id }}" {{ request('book_id') == $bk->id ? 'selected' : '' }}>
                                    [{{ $bk->book_code }}] {{ $bk->title }} (Sisa Stok: {{ $bk->available_stock }} eks) - Rak {{ $bk->shelf }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group" style="grid-column: 1 / -1;">
                        <label class="form-label">Catatan Tambahan Petugas (Opsional)</label>
                        <input type="text" name="notes" class="form-control" placeholder="Catatan kondisi buku saat keluar loket...">
                    </div>
                </div>

                <div style="background: rgba(2, 132, 199, 0.08); padding: 1rem; border-radius: 8px; border: 1px solid rgba(2, 132, 199, 0.25); margin-top: 1rem; font-size: 0.8rem; color: var(--text-muted);">
                    📅 <strong>Tanggal Pinjam:</strong> {{ date('d/m/Y') }} &bull; <strong>Jatuh Tempo:</strong> {{ date('d/m/Y', strtotime('+7 days')) }} (7 hari kalender)
                </div>

                <div style="display: flex; justify-content: flex-end; gap: 1rem; margin-top: 1.5rem;">
                    <button type="submit" class="btn btn-primary" style="padding: 0.75rem 2rem;">
                        🚀 Simpan Peminjaman & Kurangi Stok
                    </button>
                </div>
            </form>
        </div>

        <!-- Right: Quota & Blocking Rules -->
        <div>
            <div class="glass-card" style="border-color: rgba(245, 158, 11, 0.3);">
                <h4 style="font-size: 0.95rem; font-weight: 700; color: var(--gold-light); margin-bottom: 0.75rem;">
                    🛡️ Aturan Sirkulasi (§6.3)
                </h4>
                <ul style="font-size: 0.8rem; color: var(--text-muted); padding-left: 1.2rem; line-height: 1.7;">
                    <li><strong>Masa Pinjam:</strong> Tepat 7 hari kalender terhitung hari ini.</li>
                    <li><strong>Kuota Taruna:</strong> Maksimal 3 buku sekaligus.</li>
                    <li><strong>Kuota Dosen:</strong> Maksimal 5 buku sekaligus.</li>
                    <li><strong>Aturan Blokir:</strong> Peminjam yang memiliki buku melewati jatuh tempo <em>otomatis diblokir</em> meminjam buku baru sampai seluruh tunggakan dikembalikan.</li>
                    <li><strong>Denda:</strong> Rp 1.000 / hari keterlambatan per buku.</li>
                </ul>
            </div>
        </div>
    </div>

@elseif($tab === 'returns')
    <!-- Tab 2: Active Loans & Return Processing -->
    <div class="glass-card">
        <h3 style="font-size: 1.15rem; font-weight: 700; color: #fff; margin-bottom: 1.25rem;">
            🔄 Daftar Peminjaman Aktif yang Belum Dikembalikan
        </h3>

        <div class="table-responsive">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th>Kode Transaksi</th>
                        <th>Identitas Peminjam</th>
                        <th>Buku Dipinjam</th>
                        <th>Tgl Pinjam & Jatuh Tempo</th>
                        <th>Keterlambatan & Denda</th>
                        <th>Proses Pengembalian</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($activeLoans as $loan)
                        @php
                            $isOverdue = $loan->isOverdue();
                            $daysLate = $isOverdue ? \Carbon\Carbon::today()->diffInDays($loan->due_date) : 0;
                            $calculatedFine = $daysLate * 1000;
                        @endphp
                        <tr style="{{ $isOverdue ? 'background: rgba(244, 63, 94, 0.06);' : '' }}">
                            <td>
                                <strong style="color: var(--primary-light); font-family: 'JetBrains Mono', monospace; font-size: 0.82rem;">
                                    {{ $loan->transaction_code }}
                                </strong>
                            </td>

                            <td>
                                <div style="font-weight: 700; color: #fff;">
                                    {{ $loan->borrower?->name }}
                                </div>
                                <div style="font-size: 0.72rem; color: var(--text-muted);">
                                    {{ $loan->borrower_type === 'student' ? 'Taruna NIT: ' . $loan->borrower?->student_number : 'Pegawai NIP: ' . $loan->borrower?->employee_number }}
                                </div>
                            </td>

                            <td>
                                <div style="font-weight: 600; color: #fff;">
                                    {{ $loan->book?->title }}
                                </div>
                                <div style="font-size: 0.72rem; color: var(--text-dim);">
                                    Kode: {{ $loan->book?->book_code }} &bull; Rak: {{ $loan->book?->shelf }}
                                </div>
                            </td>

                            <td>
                                <div style="font-size: 0.82rem; color: #fff;">
                                    {{ $loan->loan_date->format('d/m/Y') }} &rarr; <strong>{{ $loan->due_date->format('d/m/Y') }}</strong>
                                </div>
                                @if($isOverdue)
                                    <span class="badge badge-rose" style="font-size: 0.65rem; margin-top: 0.2rem;">
                                        Telat {{ $daysLate }} Hari
                                    </span>
                                @else
                                    <span class="badge badge-emerald" style="font-size: 0.65rem; margin-top: 0.2rem;">
                                        Aktif
                                    </span>
                                @endif
                            </td>

                            <td>
                                @if($isOverdue)
                                    <div style="font-weight: 800; color: #fb7185; font-size: 0.95rem;">
                                        Rp {{ number_format($calculatedFine, 0, ',', '.') }}
                                    </div>
                                    <div style="font-size: 0.7rem; color: var(--text-dim);">Rp 1.000 / hari</div>
                                @else
                                    <span style="color: #34d399; font-size: 0.82rem;">Rp 0 (Tepat Waktu)</span>
                                @endif
                            </td>

                            <td>
                                <form action="{{ route('library.return.store') }}" method="POST" style="display: flex; flex-direction: column; gap: 0.4rem;">
                                    @csrf
                                    <input type="hidden" name="circulation_id" value="{{ $loan->id }}">

                                    <div style="display: flex; gap: 0.35rem;">
                                        <select name="return_condition" class="form-select" style="padding: 0.3rem 0.5rem; font-size: 0.75rem;">
                                            <option value="good">Kondisi Baik</option>
                                            <option value="minor_damage">Rusak Ringan</option>
                                            <option value="major_damage">Rusak Berat</option>
                                            <option value="lost">Buku Hilang</option>
                                        </select>

                                        @if($isOverdue)
                                            <label style="display: flex; align-items: center; gap: 0.25rem; font-size: 0.72rem; color: var(--gold-light); white-space: nowrap;">
                                                <input type="checkbox" name="fine_paid" value="1" checked> Lunas
                                            </label>
                                        @endif
                                    </div>

                                    <button type="submit" class="btn btn-sm btn-emerald" style="width: 100%;">
                                        ✓ Proses Kembali (+1 Stok)
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 3rem; color: var(--text-dim);">
                                Tidak ada transaksi peminjaman aktif. Seluruh buku telah dikembalikan!
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@elseif($tab === 'history')
    <!-- Tab 3: Completed History -->
    <div class="glass-card">
        <h3 style="font-size: 1.15rem; font-weight: 700; color: #fff; margin-bottom: 1.25rem;">
            📜 Riwayat Pengembalian & Catatan Denda Terakhir
        </h3>

        <div class="table-responsive">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th>Kode Transaksi</th>
                        <th>Peminjam</th>
                        <th>Buku</th>
                        <th>Tgl Kembali</th>
                        <th>Kondisi Kembali</th>
                        <th>Denda Keterlambatan</th>
                        <th>Status Denda</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentReturns as $ret)
                        <tr>
                            <td><code style="color: var(--primary-light);">{{ $ret->transaction_code }}</code></td>
                            <td><strong>{{ $ret->borrower?->name }}</strong></td>
                            <td>{{ $ret->book?->title }}</td>
                            <td>{{ $ret->return_date?->format('d/m/Y') }}</td>
                            <td>
                                <span class="badge badge-cyan">{{ $ret->return_condition?->label() ?? 'Baik' }}</span>
                            </td>
                            <td>
                                @if($ret->fine_amount > 0)
                                    <strong style="color: #fbbf24;">Rp {{ number_format($ret->fine_amount, 0, ',', '.') }}</strong>
                                    <span style="font-size: 0.7rem; color: var(--text-dim);">({{ $ret->late_days }} hari)</span>
                                @else
                                    <span style="color: #34d399;">Rp 0</span>
                                @endif
                            </td>
                            <td>
                                @if($ret->fine_paid)
                                    <span class="badge badge-emerald">Lunas</span>
                                @elseif($ret->fine_amount > 0)
                                    <span class="badge badge-rose">Belum Lunas</span>
                                @else
                                    <span class="badge badge-slate">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 3rem; color: var(--text-dim);">
                                Belum ada riwayat pengembalian tercatat.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endif
@endsection

@section('scripts')
<script>
    function toggleBorrowerList(radioEl) {
        const studentGroup = document.getElementById('student_select_group');
        const employeeGroup = document.getElementById('employee_select_group');
        const studentSelect = document.getElementById('student_borrower_id');
        const employeeSelect = document.getElementById('employee_borrower_id');

        if (radioEl.value === 'student') {
            studentGroup.style.display = 'block';
            employeeGroup.style.display = 'none';
            studentSelect.setAttribute('name', 'borrower_id');
            studentSelect.setAttribute('required', 'required');
            employeeSelect.removeAttribute('name');
            employeeSelect.removeAttribute('required');
        } else {
            studentGroup.style.display = 'none';
            employeeGroup.style.display = 'block';
            employeeSelect.setAttribute('name', 'borrower_id');
            employeeSelect.setAttribute('required', 'required');
            studentSelect.removeAttribute('name');
            studentSelect.removeAttribute('required');
        }
    }
</script>
@endsection
