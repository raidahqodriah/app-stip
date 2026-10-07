@extends('layouts.app')

@section('title', 'Katalog Buku Digital Perpustakaan STIP')

@section('content')
<div style="margin-bottom: 2rem;">
    <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 1rem;">
        <div>
            <div style="display: inline-flex; align-items: center; gap: 0.5rem; color: var(--primary-light); font-size: 0.8rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.5rem;">
                <span>📚 MODUL D: PERPUSTAKAAN DIGITAL</span> &bull; <span>KATALOG KOLEKSI</span>
            </div>
            <h1 style="font-size: 1.85rem; font-weight: 800; color: #fff;">
                Katalog Koleksi Buku & Modul Maritim
            </h1>
            <p style="color: var(--text-muted); font-size: 0.92rem; max-width: 650px;">
                Cari koleksi referensi nautika, teknika maritim, regulasi IMO/STCW, hukum laut, dan pengetahuan umum perpustakaan STIP Jakarta (§6).
            </p>
        </div>

        <div>
            <a href="{{ route('library.circulation') }}" class="btn btn-gold">
                🔄 Loket Sirkulasi & Denda
            </a>
        </div>
    </div>
</div>

<!-- Stats -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 1rem; margin-bottom: 2rem;">
    <div class="glass-card" style="padding: 1.25rem;">
        <div style="font-size: 0.75rem; color: var(--text-dim); text-transform: uppercase;">Total Judul Buku</div>
        <div style="font-size: 1.6rem; font-weight: 800; color: #fff; margin-top: 0.2rem;">{{ $stats['total_titles'] }}</div>
    </div>
    <div class="glass-card" style="padding: 1.25rem;">
        <div style="font-size: 0.75rem; color: var(--gold-light); text-transform: uppercase;">Total Eksemplar Fisik</div>
        <div style="font-size: 1.6rem; font-weight: 800; color: var(--gold-light); margin-top: 0.2rem;">{{ $stats['total_stock'] }}</div>
    </div>
    <div class="glass-card" style="padding: 1.25rem;">
        <div style="font-size: 0.75rem; color: #34d399; text-transform: uppercase;">Eksemplar Siap Dipinjam</div>
        <div style="font-size: 1.6rem; font-weight: 800; color: #34d399; margin-top: 0.2rem;">{{ $stats['available_stock'] }}</div>
    </div>
    <div class="glass-card" style="padding: 1.25rem;">
        <div style="font-size: 0.75rem; color: var(--primary-light); text-transform: uppercase;">Buku Sedang Dipinjam</div>
        <div style="font-size: 1.6rem; font-weight: 800; color: var(--primary-light); margin-top: 0.2rem;">{{ $stats['active_loans'] }}</div>
    </div>
</div>

<!-- Search & Filters -->
<div class="glass-card" style="margin-bottom: 2rem; padding: 1.25rem;">
    <form method="GET" action="{{ route('library.catalog') }}" style="display: grid; grid-template-columns: 2fr 1fr auto; gap: 1rem; align-items: flex-end;">
        <div>
            <label class="form-label">Cari Judul, Pengarang, ISBN, atau Kode Buku</label>
            <input type="text" name="search" class="form-control" placeholder="Ketik kata kunci pencarian..." value="{{ $search }}">
        </div>

        <div>
            <label class="form-label">Kategori Koleksi</label>
            <select name="category" class="form-select" onchange="this.form.submit()">
                <option value="">-- Semua Kategori --</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat }}" {{ $category == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <button type="submit" class="btn btn-primary" style="width: 100%;">
                Cari Buku
            </button>
        </div>
    </form>
</div>

<!-- Books Cards Grid -->
<div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
    @forelse($books as $b)
        <div class="glass-card" style="display: flex; flex-direction: column; justify-content: space-between; position: relative;">
            <div>
                <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 0.5rem;">
                    <span class="badge badge-cyan" style="font-size: 0.65rem;">{{ $b->category }}</span>
                    <span style="font-size: 0.72rem; color: var(--text-dim); font-family: 'JetBrains Mono', monospace;">
                        Rak: {{ $b->shelf ?? 'A-01' }}
                    </span>
                </div>

                <h3 style="font-size: 1.05rem; font-weight: 700; color: #fff; line-height: 1.35; margin-bottom: 0.35rem;">
                    {{ $b->title }}
                </h3>

                <p style="font-size: 0.8rem; color: var(--text-muted); margin-bottom: 0.5rem;">
                    Penulis: <strong>{{ $b->author }}</strong>
                </p>

                <div style="font-size: 0.75rem; color: var(--text-dim); line-height: 1.5;">
                    <div>Penerbit: {{ $b->publisher ?? 'STIP Press' }} ({{ $b->year ?? '2024' }})</div>
                    <div>Kode: <code style="color: var(--gold-light);">{{ $b->book_code }}</code> &bull; ISBN: {{ $b->isbn ?? '-' }}</div>
                </div>
            </div>

            <div style="margin-top: 1.25rem; padding-top: 1rem; border-top: 1px solid var(--border-subtle); display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <span style="font-size: 0.72rem; color: var(--text-dim); display: block;">Ketersediaan Stok</span>
                    @if($b->available_stock > 0)
                        <span style="font-size: 0.95rem; font-weight: 800; color: #34d399;">
                            {{ $b->available_stock }} / {{ $b->total_stock }} <span style="font-size: 0.72rem; font-weight: 500;">Tersedia</span>
                        </span>
                    @else
                        <span class="badge badge-rose" style="font-size: 0.65rem;">Stok Habis</span>
                    @endif
                </div>

                @if($b->available_stock > 0)
                    <a href="{{ route('library.circulation', ['book_id' => $b->id]) }}" class="btn btn-sm btn-gold">
                        Pinjam di Loket
                    </a>
                @else
                    <button class="btn btn-sm btn-outline" disabled style="opacity: 0.5; cursor: not-allowed;">
                        Tidak Tersedia
                    </button>
                @endif
            </div>
        </div>
    @empty
        <div style="grid-column: 1 / -1; text-align: center; padding: 4rem; color: var(--text-dim);" class="glass-card">
            <div style="font-size: 2.5rem; margin-bottom: 0.5rem;">📖</div>
            <p>Tidak ada koleksi buku yang cocok dengan pencarian Anda.</p>
        </div>
    @endforelse
</div>

<div style="margin-top: 1.5rem;">
    {{ $books->links() }}
</div>
@endsection
