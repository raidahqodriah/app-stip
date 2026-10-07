<?php

namespace App\Filament\Admin\Resources\BmnItems;

use App\Enums\BmnItemStatus;
use App\Enums\ItemCondition;
use App\Filament\Admin\Clusters\Bmn\BmnCluster;
use App\Filament\Admin\Resources\BmnItems\Pages\CreateBmnItem;
use App\Filament\Admin\Resources\BmnItems\Pages\EditBmnItem;
use App\Filament\Admin\Resources\BmnItems\Pages\ListBmnItems;
use App\Models\Bmn\BmnItem;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Infolists\Components\TextEntry;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class BmnItemResource extends Resource
{
    protected static ?string $model = BmnItem::class;

    protected static ?string $cluster = BmnCluster::class;

    protected static ?string $navigationLabel = 'Rekap Inventaris BMN';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCube;

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'item_name';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('item_name')
                    ->label('Nama Barang')
                    ->required(),
                TextInput::make('bmn_code')
                    ->label('Kode Barang BMN')
                    ->placeholder('Mis. 3050104001'),
                TextInput::make('register_number')
                    ->label('Nomor Urut Pendaftaran (NUP)')
                    ->placeholder('Mis. 000001'),
                TextInput::make('quantity')
                    ->label('Jumlah')
                    ->numeric()
                    ->default(1)
                    ->required(),
                TextInput::make('brand')
                    ->label('Merk'),
                TextInput::make('model')
                    ->label('Tipe / Model'),
                TextInput::make('serial_number')
                    ->label('Nomor Seri'),
                Select::make('unit_id')
                    ->label('Unit Kerja Pengguna')
                    ->relationship('unit', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('room_id')
                    ->label('Ruangan Penempatan')
                    ->relationship('room', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                TextInput::make('responsible_name')
                    ->label('Penanggung Jawab')
                    ->required(),
                DatePicker::make('acquisition_date')
                    ->label('Tanggal Perolehan')
                    ->required(),
                TextInput::make('acquisition_source')
                    ->label('Sumber Perolehan')
                    ->required(),
                Select::make('condition')
                    ->label('Kondisi Barang')
                    ->options(ItemCondition::class)
                    ->default(ItemCondition::Good)
                    ->required(),
                Toggle::make('requires_decree')
                    ->label('Memerlukan Dokumen Penetapan (PSP)')
                    ->default(false),
                Select::make('status')
                    ->label('Status Inventaris')
                    ->options(BmnItemStatus::class)
                    ->default(BmnItemStatus::Active)
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('item_name')
            ->defaultSort('item_name', 'asc')
            ->columns([
                TextColumn::make('item_name')
                    ->label('Nama Barang')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('bmn_code')
                    ->label('Kode / NUP')
                    ->formatStateUsing(fn ($record) => ($record->bmn_code ?? '-').' / '.($record->register_number ?? '-'))
                    ->searchable(),
                TextColumn::make('brand')
                    ->label('Merk / Model')
                    ->formatStateUsing(fn ($record) => trim(($record->brand ?? '').' '.($record->model ?? '')) ?: '-')
                    ->searchable(),
                TextColumn::make('unit.name')
                    ->label('Unit Kerja')
                    ->searchable(),
                TextColumn::make('room.name')
                    ->label('Ruangan')
                    ->searchable(),
                TextColumn::make('quantity')
                    ->label('Qty')
                    ->alignCenter(),
                TextColumn::make('condition')
                    ->label('Kondisi')
                    ->badge(),
                IconColumn::make('requires_decree')
                    ->label('Perlu PSP')
                    ->boolean(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge(),
            ])
            ->filters([
                SelectFilter::make('unit_id')
                    ->label('Unit')
                    ->relationship('unit', 'name'),
                SelectFilter::make('condition')
                    ->label('Kondisi')
                    ->options(ItemCondition::class),
                SelectFilter::make('status')
                    ->label('Status')
                    ->options(BmnItemStatus::class),
            ])
            ->recordActions([
                Action::make('print_decree')
                    ->label('Cetak PSP')
                    ->icon('heroicon-o-printer')
                    ->color('info')
                    ->visible(fn (BmnItem $record) => $record->requires_decree)
                    ->modalHeading('Dokumen Penetapan Status Penggunaan (PSP) BMN')
                    ->infolist([
                        TextEntry::make('item_name')->label('Nama Barang'),
                        TextEntry::make('bmn_code')->label('Kode Barang / NUP')->formatStateUsing(fn ($record) => $record->bmn_code.' / '.$record->register_number),
                        TextEntry::make('unit.name')->label('Unit Pengguna'),
                        TextEntry::make('room.name')->label('Ruangan'),
                        TextEntry::make('responsible_name')->label('Penanggung Jawab'),
                        TextEntry::make('condition')->label('Kondisi Fisik')->badge(),
                    ])
                    ->modalSubmitActionLabel('Unduh / Cetak Dokumen')
                    ->action(function () {
                        Notification::make()->title('Dokumen Penetapan siap dicetak')->success()->send();
                    }),

                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListBmnItems::route('/'),
            'create' => CreateBmnItem::route('/create'),
            'edit' => EditBmnItem::route('/{record}/edit'),
        ];
    }
}
