<?php

namespace App\Filament\Admin\Clusters\Library;

use BackedEnum;
use Filament\Clusters\Cluster;
use Filament\Support\Icons\Heroicon;

class LibraryCluster extends Cluster
{
    protected static ?string $navigationLabel = 'Perpustakaan';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBookOpen;

    protected static ?int $navigationSort = 3;
}
