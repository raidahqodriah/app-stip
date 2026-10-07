<?php

namespace App\Filament\Admin\Clusters\Bmn;

use BackedEnum;
use Filament\Clusters\Cluster;
use Filament\Support\Icons\Heroicon;

class BmnCluster extends Cluster
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingOffice2;

    protected static ?int $navigationSort = 2;

    public static function getNavigationLabel(): string
    {
        return __('filament.clusters.bmn.name');
    }

    public static function getClusterBreadcrumb(): ?string
    {
        return __('filament.clusters.bmn.breadcrumb');
    }
}
