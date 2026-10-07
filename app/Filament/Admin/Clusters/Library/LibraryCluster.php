<?php

namespace App\Filament\Admin\Clusters\Library;

use BackedEnum;
use Filament\Clusters\Cluster;
use Filament\Support\Icons\Heroicon;

class LibraryCluster extends Cluster
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBookOpen;

    protected static ?int $navigationSort = 3;

    public static function getNavigationLabel(): string
    {
        return __('filament.clusters.library.name');
    }

    public static function getClusterBreadcrumb(): ?string
    {
        return __('filament.clusters.library.breadcrumb');
    }
}
