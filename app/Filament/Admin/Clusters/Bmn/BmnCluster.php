<?php

namespace App\Filament\Admin\Clusters\Bmn;

use BackedEnum;
use Filament\Clusters\Cluster;
use Filament\Support\Icons\Heroicon;

class BmnCluster extends Cluster
{
    protected static ?string $navigationLabel = 'BMN & Rumah Dinas';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingOffice2;

    protected static ?int $navigationSort = 2;
}
