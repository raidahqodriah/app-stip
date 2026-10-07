# Rencana Aksi Implementasi Fitur Multibahasa (i18n / Lokalisasi) SILT-STIP

Dokumen ini berisi rencana aksi komprehensif, arsitektur teknis, dan standar translasi untuk penerapan fitur **Multibahasa (Internasionalisasi / i18n)** pada Sistem Informasi Layanan Terpadu (SILT-STIP) Sekolah Tinggi Ilmu Pelayaran Jakarta, khususnya pada antarmuka admin dan taruna berbasis **Filament** (`app/Filament/`) dan manajemen bahasa di `lang/`.

---

## 1. Latar Belakang & Urgensi

Sekolah Tinggi Ilmu Pelayaran (STIP) Jakarta merupakan institusi pendidikan tinggi vokasi maritim di bawah Kementerian Perhubungan Republik Indonesia yang mengadopsi standar internasional **International Maritime Organization (IMO)** dan **STCW (Standards of Training, Certification and Watchkeeping for Seafarers)**.

Oleh karena itu, sistem operasional kampus memerlukan dukungan **Dual Bahasa (Bilingual)**:
1. **Bahasa Indonesia (`id`) [Default]**: Bahasa resmi operasional harian seluruh pegawai, dosen, instruktur, dan taruna STIP Jakarta.
2. **Bahasa Inggris (`en`)**: Bahasa maritim internasional resmi untuk audit IMO, pelaporan akreditasi internasional, instruktur/dosen tamu asing, dan pembiasaan maritim bagi taruna.

---

## 2. Cakupan Komponen yang Diterjemahkan

Fitur multibahasa mencakup seluruh lapisan antarmuka Filament:

| No | Cakupan Komponen | Lokasi Berkas | Keterangan Translasi |
|:---:|---|---|---|
| **1** | **Panel Providers & Brand** | `app/Providers/Filament/` | Brand name dinamis (`AdminPanelProvider` & `StudentPanelProvider`), judul login, menu pengguna. |
| **2** | **Navigasi Cluster (Admin)** | `app/Filament/Admin/Clusters/` | 4 Cluster utama: *Layanan Lab/SPP*, *BMN & Rumah Dinas*, *Perpustakaan*, *Master & Pengaturan*. |
| **3** | **Admin Resources (20)** | `app/Filament/Admin/Resources/` | Model label, plural label, navigasi label, breadcrumb, kolom tabel, dan komponen formulir. |
| **4** | **Student Resources (3)** | `app/Filament/Student/Resources/` | Modul mandiri taruna: *Booking Mandiri Lab*, *Katalog Perpustakaan*, *Pinjaman Buku Saya*. |
| **5** | **Widgets & Dashboard** | `app/Filament/Admin/Widgets/`, `app/Filament/Student/Widgets/` | Statistik ringkasan, label kartu metrik, deskripsi KPI, dan status operasional. |
| **6** | **Enums Operasional (9)** | `app/Enums/` | Implementasi `Filament\Support\Contracts\HasLabel` pada enum status pengajuan, kondisi fisik, dan kategori lab. |
| **7** | **Filament Core UI** | `vendor/filament/*/resources/lang/` | Validasi form, filter tabel, pagination, modal konfirmasi, pesan toast/notifikasi (sudah didukung native oleh Filament). |

---

## 3. Arsitektur Teknis Sistem Lokalisasi

### 3.1 Alur Kerja Penentuan Bahasa (Locale Flow)

```mermaid
graph TD
    A[Pengguna Akses Filament Panel] --> B[Middleware SetLocaleMiddleware]
    B --> C{Cek Session 'locale'?}
    C -->|Ada| D[Gunakan Bahasa dari Session]
    C -->|Tidak Ada| E{Cek Cookie 'app_locale'?}
    E -->|Ada| F[Gunakan Bahasa dari Cookie & Simpan ke Session]
    E -->|Tidak Ada| G[Gunakan Default config 'app.locale' -> 'id']
    D --> H[app()->setLocale(locale)]
    F --> H
    G --> H
    H --> I[Carbon::setLocale(locale)]
    I --> J[Render Panel Filament Sesuai Bahasa Aktif]
```

### 3.2 Penyimpanan State Bahasa (Persistence)
- **Session (`session('locale')`)**: Menyimpan preferensi bahasa selama sesi penjelajahan aktif.
- **Cookie (`cookie('app_locale')`)**: Cookie berjangka 1 tahun agar pilihan bahasa pengguna tetap tersimpan meskipun browser ditutup atau sesi login kedaluwarsa.
- **Dukungan Masa Depan (Opsional)**: Kolom `preferred_locale` pada tabel `employees` dan `students` jika ingin disinkronkan ke profil basis data.

### 3.3 Endpoint Penggantian Bahasa (Locale Switcher Route)
- **Route**: `GET /locale/{locale}`
- **Validasi**: Locale harus terdaftar dalam whitelist `['id', 'en']`.
- **Eksekusi**: Menyimpan ke session & cookie, lalu `redirect()->back()`.

### 3.4 Antarmuka Pemilih Bahasa (Language Switcher UI)
Diletakkan secara strategis pada **Topbar Filament** menggunakan render hook:
`Filament\View\PanelsRenderHook::USER_MENU_BEFORE`
- Komponen dropdown/toggle elegan yang menampilkan bendera & kode bahasa aktif (`🇮🇩 ID` / `🇬🇧 EN`).
- Responsif untuk tampilan desktop dan mobile.

---

## 4. Struktur Direktori & Format Kamus Bahasa (`lang/`)

Struktur kamus terjemahan diatur secara terstruktur agar mudah dikelola dan dimodifikasi:

```
lang/
├── id/
│   ├── filament.php       # Kamus navigasi, cluster, resource, widget, dan brand SILT-STIP
│   ├── enums.php          # Terjemahan label seluruh enum aplikasi
│   ├── actions.php
│   ├── auth.php
│   ├── pagination.php
│   ├── validation.php
│   └── ...
├── en/
│   ├── filament.php       # Versi bahasa Inggris untuk seluruh label SILT-STIP
│   ├── enums.php          # Versi bahasa Inggris untuk label enum
│   ├── actions.php
│   ├── auth.php
│   ├── pagination.php
│   ├── validation.php
│   └── ...
├── id.json                # Terjemahan string umum Laravel-Lang
└── en.json
```

---

## 5. Matriks Terjemahan Komprehensif

### 5.1 Matriks Navigasi Cluster (Admin Panel)

| Identifier Cluster | Bahasa Indonesia (`id`) | English (`en`) |
|---|---|---|
| `lab` | Layanan Lab / SPP | Lab & Simulator Services |
| `bmn` | BMN & Rumah Dinas | State Assets & Official Housing |
| `library` | Perpustakaan | Library & Archive |
| `master` | Master & Pengaturan | Master Data & Configuration |

### 5.2 Matriks Resources Panel Admin

| Resource | Navigation Label (`id`) | Navigation Label (`en`) | Model Label (`id` / `en`) |
|---|---|---|---|
| **Rooms** | Laboratorium & Ruangan | Labs & Simulator Rooms | Ruangan Lab / Laboratory Room |
| **BlackoutDates** | Jadwal Libur & Blackout | Blackout & Maintenance Dates | Jadwal Blackout / Blackout Date |
| **Bookings** | Booking Lab & Simulator | Lab & Simulator Bookings | Booking Lab / Lab Booking |
| **Subjects** | Mata Kuliah Diklat | Courses & Subjects | Mata Kuliah / Course |
| **Materials** | Bahan & Alat Praktik | Practice Materials & Tools | Bahan Praktik / Practice Material |
| **ImoModelCourses** | IMO Model Courses | IMO Model Courses | IMO Model Course / IMO Model Course |
| **Competences** | Kompetensi IMO / STCW | IMO / STCW Competences | Kompetensi / Competence |
| **BmnItems** | Rekap Inventaris BMN | BMN Inventory Records | Barang BMN / BMN Asset |
| **BmnSubmissions** | Pengajuan BMN Baru | New BMN Submissions | Pengajuan BMN / BMN Submission |
| **BmnReturns** | Pengembalian BMN | BMN Asset Returns | Pengembalian BMN / BMN Return |
| **OfficialResidences** | Rumah Dinas | Official Residences | Rumah Dinas / Official Residence |
| **ResidencePermits** | Surat Izin Penghuni (SIP) | Housing Permits (SIP) | Izin Penghuni / Residence Permit |
| **Books** | Katalog Buku Perpustakaan | Library Book Catalog | Buku Perpustakaan / Library Book |
| **Circulations** | Sirkulasi & Denda | Circulations & Fines | Transaksi Sirkulasi / Circulation |
| **Students** | Data Taruna | Cadets Directory | Taruna / Cadet |
| **Employees** | Data Pegawai & Dosen | Staff & Lecturers Directory | Pegawai / Employee |
| **Units** | Unit Kerja & Jurusan | Units & Departments | Unit Kerja / Department |
| **DocumentTemplates** | Template Dokumen Cetak | Document Print Templates | Template Dokumen / Document Template |
| **Settings** | Pengaturan Sistem | System Configurations | Pengaturan / Setting |
| **AuditLogs** | Log Audit & Jejak Sistem | System Audit Logs | Log Audit / Audit Log |

### 5.3 Matriks Resources Panel Taruna (Student Panel)

| Resource | Navigation Label (`id`) | Navigation Label (`en`) | Model Label (`id` / `en`) |
|---|---|---|---|
| **Bookings** | Booking Mandiri Lab | Independent Lab Booking | Pengajuan Booking / Lab Booking |
| **Books** | Katalog Perpustakaan | Library Catalog | Buku Perpustakaan / Library Book |
| **Circulations** | Pinjaman Buku Saya | My Borrowed Books | Riwayat Pinjaman / Loan Record |

### 5.4 Matriks Enum Operasional

| Enum | Key | Label Indonesia (`id`) | Label English (`en`) |
|---|---|---|---|
| `RequestStatus` | `draft` | Draf | Draft |
| `RequestStatus` | `submitted` | Menunggu Pemeriksaan | Pending Verification |
| `RequestStatus` | `revision_requested` | Perlu Perbaikan | Revision Requested |
| `RequestStatus` | `verified` | Terverifikasi | Verified |
| `RequestStatus` | `approved` | Disetujui | Approved |
| `RequestStatus` | `in_use` | Sedang Digunakan | In Use / Ongoing |
| `RequestStatus` | `completed` | Selesai | Completed |
| `RequestStatus` | `rejected` | Ditolak | Rejected |
| `RequestStatus` | `cancelled` | Dibatalkan | Cancelled |
| `RequestStatus` | `no_show` | Tidak Hadir | No Show |
| `RequestStatus` | `expired` | Kedaluwarsa | Expired |
| `CirculationStatus` | `borrowed` | Sedang Dipinjam | Borrowed |
| `CirculationStatus` | `returned` | Sudah Dikembalikan | Returned |
| `ItemCondition` | `good` | Kondisi Baik | Good Condition |
| `ItemCondition` | `minor_damage` | Rusak Ringan | Minor Damage |
| `ItemCondition` | `heavy_damage` | Rusak Berat | Heavily Damaged |
| `BmnItemStatus` | `active` | Aktif Terdaftar | Active |
| `BmnItemStatus` | `damaged` | Rusak | Damaged |
| `BmnItemStatus` | `disposed` | Dihapuskan / Afkir | Disposed / Written Off |

### 5.5 Matriks Kartu Metrik Widget

| Widget | Kartu Metrik | Label (`id`) | Label (`en`) |
|---|---|---|---|
| `AdminStatsOverview` | 1 | Booking Lab Menunggu Verifikasi | Lab Bookings Pending Verification |
| `AdminStatsOverview` | 2 | Pengajuan BMN Baru | New BMN Submissions |
| `AdminStatsOverview` | 3 | Buku Sedang Dipinjam | Books Currently Borrowed |
| `AdminStatsOverview` | 4 | Permohonan SIP Rumah Dinas | Housing Permit Applications |
| `StudentStatsOverview` | 1 | Pengajuan Booking Mandiri Saya | My Lab Booking Requests |
| `StudentStatsOverview` | 2 | Buku Sedang Dipinjam | Books Currently Borrowed |
| `StudentStatsOverview` | 3 | Tunggakan Denda | Outstanding Overdue Fines |

---

## 6. Rencana Tahapan Eksekusi (Implementation Steps)

### Fase 1: Fondasi Kamus Bahasa & Middleware
- [x] Membuat berkas kamus `lang/id/filament.php` dan `lang/en/filament.php`.
- [x] Membuat berkas kamus `lang/id/enums.php` dan `lang/en/enums.php`.
- [x] Membuat middleware `App\Http\Middleware\SetLocaleMiddleware`.
- [x] Mendaftarkan rute `GET /locale/{locale}` di `routes/web.php`.
- [x] Mendaftarkan middleware di `bootstrap/app.php`, `AdminPanelProvider.php`, dan `StudentPanelProvider.php`.

### Fase 2: Implementasi UI Language Switcher
- [x] Membuat komponen Blade `resources/views/filament/components/language-switcher.blade.php`.
- [x] Meregistrasikan render hook di `AdminPanelProvider.php` dan `StudentPanelProvider.php` pada `PanelsRenderHook::USER_MENU_BEFORE`.
- [x] Mengonfigurasi `brandName()` dinamis sesuai locale aktif.

### Fase 3: Refaktorisasi Enums & Helper Terjemahan
- [x] Memperbarui Enum `RequestStatus`, `CirculationStatus`, `ItemCondition`, `BmnItemStatus`, dll., agar mengimplementasikan `Filament\Support\Contracts\HasLabel` dan mereturn string terjemahan `__('enums...')`.

### Fase 4: Refaktorisasi Seluruh Cluster & Resource Filament
- [x] Mengganti deklarasi statis `$navigationLabel` dengan metode `getNavigationLabel()`, `getModelLabel()`, dan `getPluralModelLabel()` yang menggunakan fungsi `__('filament.resources...')`.
- [x] Mengubah 4 berkas Cluster di `app/Filament/Admin/Clusters/`.
- [x] Mengubah 20 berkas Resource di `app/Filament/Admin/Resources/`.
- [x] Mengubah 3 berkas Resource di `app/Filament/Student/Resources/`.
- [x] Mengubah widget statistik di `app/Filament/Admin/Widgets/` dan `app/Filament/Student/Widgets/`.

### Fase 5: Format Standar Kode & Pengujian (Quality Assurance)
- [x] Menjalankan Laravel Pint (`vendor/bin/pint --format agent`) untuk memastikan kode bersih sesuai standar proyek.
- [x] Verifikasi sintaks PHP tanpa error (`php -l`).
- [x] Validasi navigasi dan pergantian bahasa saat sesi berganti dari `id` ke `en` dan sebaliknya.

---

## 7. Standar Kode Penulisan di Resource Filament

Untuk menjamin konsistensi pada pembaruan di masa depan, setiap Resource Filament diwajibkan mengikuti konvensi berikut:

```php
namespace App\Filament\Admin\Resources\Books;

use Filament\Resources\Resource;

class BookResource extends Resource
{
    // Hindari hardcoded protected static ?string $navigationLabel = '...';
    // Gunakan metode dinamis di bawah ini:

    public static function getModelLabel(): string
    {
        return __('filament.resources.books.label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament.resources.books.plural_label');
    }

    public static function getNavigationLabel(): string
    {
        return __('filament.resources.books.navigation_label');
    }
}
```

Dengan pola di atas:
- Navigasi sidebar otomatis multibahasa.
- Judul halaman (*Create Book* / *Tambah Buku*), tombol breadcrumb, dan judul modal aksi otomatis terlokalisasi.
- Seluruh antarmuka tetap konsisten dan terintegrasi mulus dengan ekosistem Laravel & Filament.
