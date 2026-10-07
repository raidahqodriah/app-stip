<?php

namespace Tests\Unit;

use App\Enums\BmnItemStatus;
use App\Enums\CirculationStatus;
use App\Enums\ItemCondition;
use App\Enums\RequestStatus;
use App\Filament\Admin\Clusters\Bmn\BmnCluster;
use App\Filament\Admin\Clusters\Lab\LabCluster;
use App\Filament\Admin\Clusters\Library\LibraryCluster;
use App\Filament\Admin\Clusters\Master\MasterCluster;
use App\Filament\Admin\Resources\Books\BookResource as AdminBookResource;
use App\Filament\Student\Resources\Bookings\BookingResource as StudentBookingResource;
use App\Filament\Student\Resources\Books\BookResource as StudentBookResource;
use Filament\Support\Contracts\HasLabel;
use Tests\TestCase;

class FilamentLocalizationTest extends TestCase
{
    public function test_clusters_return_localized_navigation_labels(): void
    {
        app()->setLocale('id');
        $this->assertSame('Layanan Lab / SPP', LabCluster::getNavigationLabel());
        $this->assertSame('BMN & Rumah Dinas', BmnCluster::getNavigationLabel());
        $this->assertSame('Perpustakaan', LibraryCluster::getNavigationLabel());
        $this->assertSame('Master & Pengaturan', MasterCluster::getNavigationLabel());

        app()->setLocale('en');
        $this->assertSame('Lab & Simulator Services', LabCluster::getNavigationLabel());
        $this->assertSame('State Assets & Housing', BmnCluster::getNavigationLabel());
        $this->assertSame('Library', LibraryCluster::getNavigationLabel());
        $this->assertSame('Master & Settings', MasterCluster::getNavigationLabel());
    }

    public function test_resources_return_localized_labels(): void
    {
        app()->setLocale('id');
        $this->assertSame('Katalog Buku Perpustakaan', AdminBookResource::getNavigationLabel());
        $this->assertSame('Buku Perpustakaan', AdminBookResource::getModelLabel());
        $this->assertSame('Booking Mandiri Lab', StudentBookingResource::getNavigationLabel());
        $this->assertSame('Katalog Perpustakaan', StudentBookResource::getNavigationLabel());

        app()->setLocale('en');
        $this->assertSame('Library Book Catalog', AdminBookResource::getNavigationLabel());
        $this->assertSame('Library Book', AdminBookResource::getModelLabel());
        $this->assertSame('Independent Lab Booking', StudentBookingResource::getNavigationLabel());
        $this->assertSame('Library Catalog', StudentBookResource::getNavigationLabel());
    }

    public function test_enums_implement_has_label_and_provide_localized_translations(): void
    {
        $this->assertInstanceOf(HasLabel::class, RequestStatus::Submitted);
        $this->assertInstanceOf(HasLabel::class, CirculationStatus::Borrowed);
        $this->assertInstanceOf(HasLabel::class, ItemCondition::Good);
        $this->assertInstanceOf(HasLabel::class, BmnItemStatus::Active);

        app()->setLocale('id');
        $this->assertSame('Menunggu Pemeriksaan', RequestStatus::Submitted->getLabel());
        $this->assertSame('Dipinjam', CirculationStatus::Borrowed->getLabel());
        $this->assertSame('Baik', ItemCondition::Good->getLabel());
        $this->assertSame('Aktif', BmnItemStatus::Active->getLabel());

        app()->setLocale('en');
        $this->assertSame('Pending Verification', RequestStatus::Submitted->getLabel());
        $this->assertSame('Borrowed', CirculationStatus::Borrowed->getLabel());
        $this->assertSame('Good Condition', ItemCondition::Good->getLabel());
        $this->assertSame('Active', BmnItemStatus::Active->getLabel());
    }
}
