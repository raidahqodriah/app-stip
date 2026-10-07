<?php

namespace Tests\Feature;

use App\Livewire\Front\AnnouncementIndex;
use App\Livewire\Front\HomePage;
use App\Livewire\Front\LabCatalog;
use App\Livewire\Front\LabDetail;
use App\Livewire\Front\LabSchedule;
use App\Models\Core\Room;
use Livewire\Livewire;
use Tests\TestCase;

class FrontEndPagesTest extends TestCase
{
    public function test_home_page_can_be_rendered(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);

        Livewire::test(HomePage::class)
            ->assertStatus(200)
            ->assertSee('SILT-STIP')
            ->assertSee('Satu Aplikasi untuk Seluruh Layanan Kampus STIP Jakarta');
    }

    public function test_lab_catalog_can_be_rendered_and_filtered(): void
    {
        $response = $this->get('/lab');
        $response->assertStatus(200);

        Livewire::test(LabCatalog::class)
            ->assertStatus(200)
            ->assertSee('Katalog Laboratorium')
            ->assertSee('Simulator SPP')
            ->assertSee('ERCS')
            ->assertSee('CHL')
            ->set('search', 'Engine Room')
            ->assertSee('ERCS')
            ->set('department', 'nautika')
            ->assertSee('CHL');
    }

    public function test_lab_detail_page_can_be_rendered_for_ercs(): void
    {
        $room = Room::where('code', 'ERCS')->first();
        $this->assertNotNull($room);

        $response = $this->get('/lab/ERCS');
        $response->assertStatus(200);

        Livewire::test(LabDetail::class, ['code' => 'ERCS'])
            ->assertStatus(200)
            ->assertSee('Engine Room Certification Simulator')
            ->assertSee('ERCS')
            ->assertSee('Matriks Slot Ketersediaan Hari Ini');
    }

    public function test_lab_schedule_page_can_be_rendered(): void
    {
        $response = $this->get('/jadwal');
        $response->assertStatus(200);

        Livewire::test(LabSchedule::class)
            ->assertStatus(200)
            ->assertSee('Jadwal Ketersediaan Lab')
            ->assertSee('Simulator SPP')
            ->call('nextWeek')
            ->assertSet('weekOffset', 1)
            ->call('prevWeek')
            ->assertSet('weekOffset', 0);
    }

    public function test_announcements_page_can_be_rendered(): void
    {
        $response = $this->get('/pengumuman');
        $response->assertStatus(200);

        Livewire::test(AnnouncementIndex::class)
            ->assertStatus(200)
            ->assertSee('Pusat Pengumuman')
            ->assertSee('Informasi Operasional');
    }
}
