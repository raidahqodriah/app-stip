<?php

namespace App\Livewire\Front;

use App\Models\Lab\BlackoutDate;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;

#[Layout('layouts.front')]
#[Title('Pusat Pengumuman & Operasional')]
class AnnouncementIndex extends Component
{
    #[Url(as: 'q')]
    public string $search = '';

    #[Url(as: 'kategori')]
    public string $category = 'all';

    public ?array $selectedAnnouncement = null;

    public bool $showModal = false;

    public function selectAnnouncement(string $id): void
    {
        $all = $this->getAnnouncements();
        $this->selectedAnnouncement = collect($all)->firstWhere('id', $id);
        $this->showModal = true;
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->selectedAnnouncement = null;
    }

    /**
     * Get combined announcement list from database blackout dates and official bulletins.
     *
     * @return array<int, array<string, mixed>>
     */
    protected function getAnnouncements(): array
    {
        $items = [];

        // 1. Fetch blackout dates from database
        $blackouts = BlackoutDate::with('room')->orderBy('start_at', 'desc')->get();
        foreach ($blackouts as $idx => $bo) {
            $isAcsl = $bo->room?->code === 'ACSL';
            $items[] = [
                'id' => 'bo-'.$bo->id,
                'is_pinned' => $isAcsl,
                'category' => 'pemeliharaan',
                'category_label' => 'Pemeliharaan Lab',
                'category_color' => 'status-inoperative',
                'category_bg' => 'status-inoperative-bg',
                'date' => Carbon::parse($bo->start_at)->translatedFormat('d F Y'),
                'room_tag' => ($bo->room?->code ?? 'Semua Lab').' • '.Carbon::parse($bo->start_at)->format('d').'–'.Carbon::parse($bo->end_at)->format('d M Y'),
                'title' => $bo->reason,
                'summary' => 'Pemberitahuan resmi penghentian sementara operasional praktikum laboratorium sehubungan dengan kalibrasi dan perawatan rutin tahunan simulator.',
                'impact' => 'Booking lab dinonaktifkan untuk tanggal tersebut. Dosen pengampu disarankan memilih slot alternatif pada minggu berikutnya.',
                'room_code' => $bo->room?->code ?? 'ALL',
            ];
        }

        // 2. Official static maritime announcements
        $items[] = [
            'id' => 'ann-ukp',
            'is_pinned' => false,
            'category' => 'ukp',
            'category_label' => 'Jadwal UKP',
            'category_color' => 'secondary',
            'category_bg' => 'status-tersedia-bg',
            'date' => Carbon::now()->addDays(5)->translatedFormat('d F Y'),
            'room_tag' => 'CBT • LTL',
            'title' => 'Prioritas Ruang CBT untuk Simulasi Ujian Keahlian Pelaut (UKP)',
            'summary' => 'Laboratorium CBT 1 & 2 serta LTL diprioritaskan bagi agenda simulasi UKP Pra-Prala dan Pasca Prala jurusan Teknika dan Nautika.',
            'impact' => 'Peminjaman mandiri taruna ditutup pukul 07.30 - 15.00 WIB. Peminjaman kelompok studi dibuka kembali setelah pukul 15.30 WIB.',
            'room_code' => 'CBT',
        ];

        $items[] = [
            'id' => 'ann-kalender',
            'is_pinned' => false,
            'category' => 'kalender',
            'category_label' => 'Kalender Akademik',
            'category_color' => 'status-libur',
            'category_bg' => 'status-libur-bg',
            'date' => Carbon::now()->subDays(2)->translatedFormat('d F Y'),
            'room_tag' => 'Semua Lab',
            'title' => 'Sinkronisasi Kalender Akademik Semester Genap 2025/2026',
            'summary' => 'Seluruh master jadwal praktikum semester genap telah diperbarui sesuai RPS program studi Teknika, Nautika, dan KALK.',
            'impact' => 'Dosen koordinator mata kuliah telah dapat mengajukan peminjaman simulator melalui SSO Pegawai dengan lead time maksimal 60 hari.',
            'room_code' => 'ALL',
        ];

        $items[] = [
            'id' => 'ann-regulasi',
            'is_pinned' => false,
            'category' => 'regulasi',
            'category_label' => 'Regulasi Maritim',
            'category_color' => 'status-terisi',
            'category_bg' => 'status-terisi-bg',
            'date' => Carbon::now()->subDays(7)->translatedFormat('d F Y'),
            'room_tag' => 'Standar STCW',
            'title' => 'Kewajiban Pencatatan Kompetensi IMO Model Course pada Formulir Booking',
            'summary' => 'Mulai semester ini, seluruh sesi praktikum wajib terhubung dengan minimal 1 kompetensi IMO STCW guna pelaporan utilisasi BPSDM Perhubungan.',
            'impact' => 'Formulir pengajuan tanpa kompetensi IMO yang terverifikasi akan otomatis ditolak oleh sistem verifikasi Petugas SPP.',
            'room_code' => 'ALL',
        ];

        return $items;
    }

    public function render(): View
    {
        $all = $this->getAnnouncements();

        // Separate pinned
        $pinned = collect($all)->firstWhere('is_pinned', true) ?? collect($all)->first();

        // Filter search & category
        $filtered = collect($all)->filter(function ($item) {
            $matchCat = ($this->category === 'all') || ($item['category'] === $this->category);

            $matchSearch = true;
            if (! empty(trim($this->search))) {
                $term = strtolower(trim($this->search));
                $matchSearch = str_contains(strtolower($item['title']), $term)
                    || str_contains(strtolower($item['summary']), $term)
                    || str_contains(strtolower($item['room_tag']), $term);
            }

            return $matchCat && $matchSearch;
        });

        // Category counts
        $counts = [
            'all' => count($all),
            'pemeliharaan' => collect($all)->where('category', 'pemeliharaan')->count(),
            'kalender' => collect($all)->where('category', 'kalender')->count(),
            'ukp' => collect($all)->where('category', 'ukp')->count(),
            'regulasi' => collect($all)->where('category', 'regulasi')->count(),
        ];

        return view('livewire.front.announcement-index', [
            'pinned' => $pinned,
            'announcements' => $filtered,
            'counts' => $counts,
        ]);
    }
}
