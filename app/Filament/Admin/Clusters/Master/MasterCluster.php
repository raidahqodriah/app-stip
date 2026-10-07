<?php

namespace App\Filament\Admin\Clusters\Master;

use BackedEnum;
use Filament\Clusters\Cluster;
use Filament\Support\Icons\Heroicon;

class MasterCluster extends Cluster
{
    protected static ?string $navigationLabel = 'Master & Pengaturan';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;

    protected static ?int $navigationSort = 4;
}
