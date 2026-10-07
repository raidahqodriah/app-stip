<?php

namespace App\Filament\Admin\Clusters\Lab;

use BackedEnum;
use Filament\Clusters\Cluster;
use Filament\Support\Icons\Heroicon;

class LabCluster extends Cluster
{
    protected static ?string $navigationLabel = 'Layanan Lab / SPP';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedComputerDesktop;

    protected static ?int $navigationSort = 1;
}
