# Rencana Aksi Implementasi Frontend SILT-STIP Terpadu (Livewire 4)

Dokumen ini berisi rencana komprehensif implementasi antarmuka publik (Frontend) Sistem Informasi Layanan Terpadu (SILT-STIP) Sekolah Tinggi Ilmu Pelayaran Jakarta berdasarkan kumpulan template di `template_frontend/`, PRD `PRD_STIP_Terpadu.md`, dan standar arsitektur **Laravel 13 + Livewire v4.4 + Tailwind CSS v4**.

---

## 1. Analisis Kebutuhan & Pemetaan Template

Berdasarkan berkas template yang tersedia di folder `template_frontend/`, terdapat 5 halaman utama yang akan diubah menjadi komponen Livewire 4 reaktif:

| # | Halaman / Template Sumber | Target Route | Komponen Livewire 4 | Deskripsi & Fungsi Utama |
|---|---|---|---|---|
| **1** | `stip_landing_page/code.html` | `/` | `App\Livewire\Front\HomePage` | **Beranda Utama**: Hero maritim terpadu, live matrix operasional simulator, pengenalan 4 modul kampus (Lab SPP, BMN, Rumah Dinas, Perpustakaan), live KPI counter, alur booking, dan FAQ interaktif. |
| **2** | `stip_katalog_lab/code.html` | `/lab` | `App\Livewire\Front\LabCatalog` | **Katalog Lab & Simulator SPP**: Direktori 8 lab aktif, live search (nama/kode), filter prodi/jurusan (Teknika, Nautika, KALK, Umum), filter status operasional (tersedia/terisi/maintenance), dan sorting kapasitas/kode. |
| **3** | `stip_detail_lab_ercs_1/code.html` | `/lab/{code}` | `App\Livewire\Front\LabDetail` | **Detail Laboratorium & Simulator**: Rincian fasilitas dinamis (berdasarkan kode lab, misal ERCS, CHL, dsb.), galeri foto, spesifikasi konsol & software STCW, ketersediaan slot hari ini, kit bahan/peralatan praktikum, SOP keselamatan, dan CTA login SSO untuk pengajuan booking. |
| **4** | `stip_jadwal_lab/code.html` | `/jadwal` | `App\Livewire\Front\LabSchedule` | **Jadwal Ketersediaan Lab Real-Time**: Kalender matriks slot waktu operasional (07.30 - 16.00 WIB), navigasi mingguan/harian, filter jurusan & lab cepat, status ketersediaan slot anonim (bebas data pribadi sesuai PRD §2.3), dan modal quick view info sesi. |
| **5** | `stip_pengumuman/code.html` | `/pengumuman` | `App\Livewire\Front\AnnouncementIndex` | **Pusat Pengumuman & Operasional**: Siaran resmi kampus Marunda, banner pengumuman darurat/disematkan (pinned), filter kategori (Pemeliharaan/Blackout Lab, Kalender Akademik, UKP, Regulasi), dan modal rincian dampak operasional. |

---

## 2. Prinsip & Kepatuhan Terhadap PRD (`PRD_STIP_Terpadu.md`)

1. **8 Fasilitas Lab Aktif SPP (§3.1)**:
   - Menampilkan hanya lab berstatus aktif: `CHL`, `EEL`, `EWS`, `MEL`, `CBT`, `ERCS`, `LTL`, dan `ACSL`.
   - Lab yang dinonaktifkan per Mei 2026 (`BRF`, `LCHS`, `NAS`, `SOL`, `ERGL`) tidak ditampilkan di katalog publik.
2. **Kepatuhan Privasi Publik (PRD §2.3 & §8.1)**:
   - Tampilan kalender & jadwal publik **tidak menampilkan identitas pribadi pemohon / taruna**.
   - Slot jadwal hanya menampilkan: *Kode Lab, Nama Mata Kuliah / Diklat, Tingkat/Grup Kelas, Jam Sesi, dan Status Slot (Tersedia / Terisi / Pemeliharaan)*.
3. **Penyelarasan Slot Waktu Operasional (§3.3)**:
   - Rentang waktu operasional default: **07.30 – 16.00 WIB** (Senin–Jumat).
   - Menghormati penandaan pemeliharaan / blackout date dari tabel `blackout_dates`.
4. **Integrasi Portal Multi-Panel**:
   - Navbar menyediakan akses SSO cepat:
     - Tombol **"Masuk Pegawai"** mengarah ke panel Filament Pegawai/Dosen/Petugas: `/admin`.
     - Tombol **"Masuk Taruna"** mengarah ke panel Filament Taruna: `/student`.

---

## 3. Arsitektur Teknis Livewire 4 & Frontend

### 3.1 Pilihan Format Komponen
Komponen dibangun menggunakan pola **Class-based Livewire 4 Component** (`app/Livewire/Front/`) dengan view terpisah di `resources/views/livewire/front/`.
- **Alasan**: Memisahkan logika Eloquent query (search, filter, pagination, grouping jadwal) dari template Blade yang memiliki struktur markup visual kaya (maritime styling).

### 3.2 Fitur Unggulan Livewire 4 yang Digunakan
1. **SPA-Like Navigation (`wire:navigate`)**:
   - Semua tautan navigasi internal antar halaman publik (`/`, `/lab`, `/jadwal`, `/pengumuman`) menggunakan `wire:navigate` untuk pergantian halaman instan tanpa re-render aset CSS/JS.
2. **Reaktivitas Pencarian & Filter (`wire:model.live`)**:
   - Input pencarian menggunakan `wire:model.live.debounce.300ms="search"` agar responsif tanpa membebani server database.
   - Filter dropdown & tombol kategori menggunakan `wire:model.live` untuk update instan grid katalog & kalender jadwal.
3. **Islands & Isolated Updates (`@island`)**:
   - Komponen waktu (Live Clock WIB) dan navigasi minggu kalender menggunakan island atau sub-komponen terisolasi untuk meminimalkan transmisi payload DOM.
4. **Konsistensi Kunci DOM (`wire:key`)**:
   - Setiap kartu lab, baris slot waktu, dan kartu pengumuman dalam `@foreach` wajib memiliki `wire:key` unik guna mencegah rendering bugs pada Livewire 4.
5. **Indikator Loading Nyaman (`wire:loading`, `data-loading`)**:
   - Memberikan visual feedback berupa subtle spinner atau skeleton fade saat pengguna mengganti minggu kalender atau memfilter katalog lab.

---

## 4. Konfigurasi Desain Sistem & Styling (Tailwind CSS v4)

Template menggunakan sistem warna bertema maritim modern (Deep Oceanic & Technical Maritime). Konfigurasi tema akan disematkan ke dalam `resources/css/app.css` menggunakan sintaks Tailwind CSS v4 `@theme`:

```css
@import 'tailwindcss';

@theme {
    /* Maritime Color Palette */
    --color-primary: #00152d;
    --color-on-primary: #ffffff;
    --color-primary-container: #0b2a4a;
    --color-on-primary-container: #7892b7;
    --color-navy-light: #1B416B;

    --color-secondary: #006a6a;
    --color-on-secondary: #ffffff;
    --color-secondary-container: #8ff3f2;
    --color-secondary-fixed: #8ff3f2;
    --color-secondary-fixed-dim: #72d6d6;

    --color-tertiary-fixed: #ffdea8;
    --color-tertiary-fixed-dim: #fabc3e;
    --color-gold-hover: #C9911C;
    --color-gold-subtle: #FEF9EE;

    --color-canvas-bg: #F6F8FB;
    --color-surface-white: #FFFFFF;
    --color-surface-container: #e5eeff;
    --color-surface-container-high: #dce9ff;
    --color-surface-container-low: #eff4ff;

    --color-status-tersedia: #0E8A8A;
    --color-status-tersedia-bg: #E6F4F4;
    --color-status-terisi: #0B2A4A;
    --color-status-terisi-bg: #E7ECF1;
    --color-status-inoperative: #DC2626;
    --color-status-inoperative-bg: #FEF2F2;
    --color-status-libur: #64748B;
    --color-status-libur-bg: #F1F5F9;

    --color-text-primary: #0F172A;
    --color-text-muted: #64748B;
    --color-border-subtle: #E2E8F0;

    /* Typography */
    --font-sans: 'Plus Jakarta Sans', ui-sans-serif, system-ui, sans-serif;
}
```

Aset Font dan Ikon:
- **Font Utama**: *Plus Jakarta Sans* (Google Fonts).
- **Icon Set**: *Material Symbols Outlined* (Google Fonts).
Keduanya akan dimuat di layout utama `resources/views/layouts/front.blade.php`.

---

## 5. Rencana Struktur Berkas & Komponen

```
app/
├── Livewire/
│   └── Front/
│       ├── HomePage.php                  // Beranda publik & live overview
│       ├── LabCatalog.php                // Katalog & filter 8 lab
│       ├── LabDetail.php                 // Detail spesifik lab & info slot
│       ├── LabSchedule.php               // Kalender ketersediaan mingguan
│       ├── AnnouncementIndex.php         // Pusat pengumuman & status blackout
│       └── Components/
│           ├── Navbar.php                // Header navbar dengan navigasi & link auth
│           ├── Footer.php                // Footer maritim STIP Jakarta
│           ├── SlotQuickViewModal.php    // Modal dialog rincian slot jadwal
│           └── AnnouncementModal.php     // Modal dialog rincian dampak pengumuman
│
resources/
├── css/
│   └── app.css                           // Tailwind v4 theme tokens
├── views/
│   ├── layouts/
│   │   └── front.blade.php               // Layout induk frontend publik
│   └── livewire/
│       └── front/
│           ├── home-page.blade.php
│           ├── lab-catalog.blade.php
│           ├── lab-detail.blade.php
│           ├── lab-schedule.blade.php
│           ├── announcement-index.blade.php
│           └── components/
│               ├── navbar.blade.php
│               ├── footer.blade.php
│               ├── slot-quick-view-modal.blade.php
│               └── announcement-modal.blade.php
```

---

## 6. Tahapan Eksekusi Bertahap (Step-by-Step Execution Plan)

### Tahap 1: Setup Desain Sistem & Master Layout
1. Konfigurasi `resources/css/app.css` dengan token warna maritim Tailwind v4.
2. Buat master layout `resources/views/layouts/front.blade.php` lengkap dengan `<head>`, Google Fonts (*Plus Jakarta Sans*, *Material Symbols Outlined*), `@livewireStyles`, `@livewireScripts`, dan container utama.
3. Buat sub-komponen navbar `Navbar.php` + `navbar.blade.php` (mendukung active route indicator dan link SSO `/admin` & `/student`).
4. Buat sub-komponen footer `Footer.php` + `footer.blade.php` (identitas resmi STIP Jakarta Marunda, kontak, dan tautan regulasi).

### Tahap 2: Implementasi Halaman Beranda (`HomePage`)
1. Buat class `App\Livewire\Front\HomePage` dan view `home-page.blade.php`.
2. Query data live:
   - Hitungan statistik aktual dari database (`Room::labs()->count()`, `Booking::count()`, `Book::count()`, `BmnItem::count()`).
   - Sesi operasional simulator hari ini untuk mini-matrix preview.
3. Rancang section: Hero Maritim, Mini Preview Kalender ERCS/CHL/MEL/EEL, 4 Layanan Kampus Terpadu, Metrik Kampus, Alur Booking 3 Langkah, Accordion FAQ interaktif, dan CTA.

### Tahap 3: Implementasi Katalog Laboratorium (`LabCatalog`)
1. Buat class `App\Livewire\Front\LabCatalog` dan view `lab-catalog.blade.php`.
2. Properti interaktif:
   - `$search = ''` (live search kode/nama/deskripsi)
   - `$department = 'all'` (filter prodi: all, teknika, nautika, kalk, umum)
   - `$statusFilter = 'all'` (all, tersedia, terisi, maintenance)
   - `$sortOrder = 'code-asc'` (code-asc, code-desc, cap-desc, cap-asc)
3. Hitung status operasional masing-masing lab secara dinamis berdasarkan data `BlackoutDate` dan `BookingSlot` hari ini.
4. Render grid 8 lab sesuai layout kartu di `stip_katalog_lab/code.html` dengan tautan detail ke `route('lab.detail', $room->code)`.

### Tahap 4: Implementasi Detail Laboratorium (`LabDetail`)
1. Buat class `App\Livewire\Front\LabDetail` dan view `lab-detail.blade.php`.
2. Route binding dinamis: `Route::livewire('/lab/{code}', LabDetail::class)`.
3. Query relasi lab:
   - Data spesifikasi lab (`Room`), PIC Pegawai (`picEmployee`), Unit (`unit`).
   - Mata kuliah & kompetensi IMO terkait (`subjects.competences`).
   - Kit bahan & material pendukung (`materialKits`, `materials`).
   - Status slot ketersediaan hari ini (07.30 - 16.00).
4. Sediakan modal / tombol Call-to-Action untuk mengajukan booking (mengarahkan dosen ke `/admin` dan taruna ke `/student`).

### Tahap 5: Implementasi Jadwal Ketersediaan Lab (`LabSchedule`)
1. Buat class `App\Livewire\Front\LabSchedule` dan view `lab-schedule.blade.php`.
2. Properti interaktif:
   - `$selectedWeekOffset = 0` (navigasi: Minggu Sebelumnya, Minggu Ini, Minggu Berikutnya)
   - `$selectedDepartment = 'all'` (Teknika, Nautika, KALK, Semua)
   - `$selectedRoomCode = 'all'` (Pilih salah satu lab atau semua)
   - `$viewMode = 'week'` (hari, minggu)
3. Logika ketersediaan slot:
   - Memetakan hari kerja (Senin–Jumat) dan rentang jam operasional (07.30 – 16.00).
   - Menghubungkan booking yang telah disetujui (`status = approved`) dan penguncian slot (`booking_slots`).
   - Mengecek blackout dates (`blackout_dates`) per lab atau global.
   - Sesuai PRD §2.3: Nama pemohon disembunyikan, hanya menampilkan mata kuliah, kelas, dan status ketersediaan.
4. Buat modal detail cepat (`SlotQuickViewModal`) saat kartu sesi di-klik.

### Tahap 6: Implementasi Pusat Pengumuman & Blackout Operasional (`AnnouncementIndex`)
1. Buat class `App\Livewire\Front\AnnouncementIndex` dan view `announcement-index.blade.php`.
2. Properti interaktif:
   - `$search = ''`
   - `$category = 'all'` (pemeliharaan, kalender, ujian, regulasi)
   - `$selectedAnnouncementId = null` (untuk drawer/modal rincian dampak)
3. Sumber data dinamis:
   - Mengambil data blackout dates aktif dari database sebagai pengumuman pemeliharaan darurat / terjadwal.
   - Data pengumuman kalender akademik dan regulasi operasional.
4. Buat modal `AnnouncementModal` untuk menampilkan dampak operasional, lab terdampak, dan saran penjadwalan alternatif.

### Tahap 7: Definisi Route & Seeder Data Uji Coba
1. Perbarui `routes/web.php` menggunakan `Route::livewire()` untuk kelima halaman.
2. Pastikan database terisi data uji coba representatif menggunakan `php artisan db:seed` (`RoomSeeder`, `CurriculumSeeder`, `MaterialSeeder`, serta data booking & blackout dates contoh) agar seluruh filter dan kalender langsung dapat diuji dengan data nyata.

### Tahap 8: Verifikasi & Formatter Kode
1. Format seluruh kode PHP menggunakan Laravel Pint: `vendor/bin/pint --dirty --format agent`.
2. Verifikasi kompilasi frontend Vite: `npm run build`.
3. Verifikasi konsistensi rendering di browser.

---

## 7. Pertanyaan & Keputusan Desain untuk Pengguna

Sebelum eksekusi dimulai, berikut beberapa poin konfirmasi:
1. **Format Komponen**: Apakah Anda setuju menggunakan **Class-based Livewire 4** (`app/Livewire/Front/*`) agar logika query Eloquent bersih dan terstruktur?
2. **Data Awal**: Apakah kami diperkenankan menjalankan seeder bawaan (`php artisan db:seed`) agar 8 lab aktif, mata kuliah IMO, dan slot jadwal terisi data contoh yang realistis sesuai template?
3. **Penyimpanan Berkas Rencana**: File rencana ini telah disimpan di [RENCANA_AKSI_FRONTEND_LIVEWIRE4.md](file:///home/kurnia/HTDOCS/app-stip/RENCANA_AKSI_FRONTEND_LIVEWIRE4.md).

Silakan review rencana aksi ini dan berikan arahan atau konfirmasi untuk memulai langkah eksekusi.
