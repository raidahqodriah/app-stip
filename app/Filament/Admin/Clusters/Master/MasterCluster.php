<?php

namespace App\Filament\Admin\Clusters\Master;

use BackedEnum;
use Filament\Clusters\Cluster;
use Filament\Support\Icons\Heroicon;

class MasterCluster extends Cluster
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;

    protected static ?int $navigationSort = 4;

    public static function getNavigationLabel(): string
    {
        return __('filament.clusters.master.name');
    }

    public static function getClusterBreadcrumb(): ?string
    {
        return __('filament.clusters.master.breadcrumb');
    }
}
