<?php

namespace App\Filament\Admin\Clusters\Lab;

use BackedEnum;
use Filament\Clusters\Cluster;
use Filament\Support\Icons\Heroicon;

class LabCluster extends Cluster
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedComputerDesktop;

    protected static ?int $navigationSort = 1;

    public static function getNavigationLabel(): string
    {
        return __('filament.clusters.lab.name');
    }

    public static function getClusterBreadcrumb(): ?string
    {
        return __('filament.clusters.lab.breadcrumb');
    }
}
