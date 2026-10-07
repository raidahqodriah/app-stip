<?php

namespace App\Filament\Admin\Resources\BmnReturns;

use App\Enums\BmnItemStatus;
use App\Enums\BmnMovementSource;
use App\Enums\ItemCondition;
use App\Enums\RequestStatus;
use App\Filament\Admin\Clusters\Bmn\BmnCluster;
use App\Filament\Admin\Resources\BmnReturns\Pages\CreateBmnReturn;
use App\Filament\Admin\Resources\BmnReturns\Pages\EditBmnReturn;
use App\Filament\Admin\Resources\BmnReturns\Pages\ListBmnReturns;
use App\Models\Bmn\BmnItemMovement;
use App\Models\Bmn\BmnReturn;
use App\Models\Core\Employee;
use App\Models\Core\RequestLog;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class BmnReturnResource extends Resource
{
    protected static ?string $model = BmnReturn::class;

    protected static ?string $cluster = BmnCluster::class;

    protected static ?string $navigationLabel = 'Pengembalian BMN';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedArrowUturnLeft;

    protected static ?int $navigationSort = 3;

    protected static ?string $recordTitleAttribute = 'reason';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('bmn_item_id')
                    ->label('Barang BMN yang Dikembalikan')
                    ->relationship('bmnItem', 'item_name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('from_room_id')
                    ->label('Dari Ruangan Asal')
                    ->relationship('fromRoom', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('destination_room_id')
                    ->label('Ruangan Tujuan / Gudang Aset')
                    ->relationship('destinationRoom', 'name')
                    ->searchable()
                    ->preload(),
                DatePicker::make('return_date')
                    ->label('Tanggal Pengembalian')
                    ->default(now())
                    ->required(),
                Select::make('condition')
                    ->label('Kondisi Saat Dikembalikan')
                    ->options(ItemCondition::class)
                    ->default(ItemCondition::Good)
                    ->reactive()
                    ->required(),
                Textarea::make('damage_note')
                    ->label('Keterangan Kerusakan (Wajib jika kondisi rusak)')
                    ->required(fn ($get) => in_array($get('condition'), [ItemCondition::MinorDamage->value, ItemCondition::MajorDamage->value]))
                    ->columnSpanFull(),
                Textarea::make('reason')
                    ->label('Alasan Pengembalian')
                    ->required()
                    ->columnSpanFull(),
                Select::make('status')
                    ->label('Status Pengajuan')
                    ->options(RequestStatus::class)
                    ->default(RequestStatus::Submitted)
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('reason')
            ->defaultSort('return_date', 'desc')
            ->columns([
                TextColumn::make('bmnItem.item_name')
                    ->label('Nama Barang')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('fromRoom.name')
                    ->label('Dari Ruangan')
                    ->searchable(),
                TextColumn::make('destinationRoom.name')
                    ->label('Ke Ruangan / Gudang')
                    ->searchable(),
                TextColumn::make('return_date')
                    ->label('Tgl Kembali')
                    ->date()
                    ->sortable(),
                TextColumn::make('condition')
                    ->label('Kondisi')
                    ->badge(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (RequestStatus $state): string => match ($state) {
                        RequestStatus::Draft => 'gray',
                        RequestStatus::Submitted => 'warning',
                        RequestStatus::RevisionRequested => 'orange',
                        RequestStatus::Verified, RequestStatus::Approved => 'success',
                        RequestStatus::Rejected => 'danger',
                        default => 'gray',
                    }),
            ])
            ->filters([
                SelectFilter::make('condition')
                    ->label('Kondisi')
                    ->options(ItemCondition::class),
                SelectFilter::make('status')
                    ->label('Status')
                    ->options(RequestStatus::class),
            ])
            ->recordActions([
                Action::make('verify_return')
                    ->label('Verifikasi Terima')
                    ->icon('heroicon-o-check-badge')
                    ->color('success')
                    ->visible(fn (BmnReturn $record) => in_array($record->status, [RequestStatus::Submitted, RequestStatus::RevisionRequested]))
                    ->requiresConfirmation()
                    ->modalHeading('Verifikasi Fisik & Terima Pengembalian BMN')
                    ->action(function (BmnReturn $record) {
                        // Update status barang
                        $record->bmnItem->update([
                            'status' => BmnItemStatus::Returned,
                            'condition' => $record->condition,
                            'room_id' => $record->destination_room_id ?? $record->from_room_id,
                        ]);

                        // Catat perpindahan
                        BmnItemMovement::create([
                            'bmn_item_id' => $record->bmn_item_id,
                            'from_room_id' => $record->from_room_id,
                            'to_room_id' => $record->destination_room_id,
                            'source' => BmnMovementSource::Return,
                            'reason' => 'Pengembalian barang: '.$record->reason,
                        ]);

                        $record->update([
                            'status' => RequestStatus::Approved,
                            'verified_by' => auth()->id() ?? 1,
                        ]);

                        RequestLog::create([
                            'loggable_type' => BmnReturn::class,
                            'loggable_id' => $record->id,
                            'actor_type' => Employee::class,
                            'actor_id' => auth()->id() ?? 1,
                            'role' => 'officer',
                            'action' => 'approve_return',
                            'from_status' => RequestStatus::Submitted->value,
                            'to_status' => RequestStatus::Approved->value,
                            'note' => 'Pengembalian BMN telah diverifikasi dan diterima petugas.',
                        ]);

                        Notification::make()->title('Pengembalian BMN berhasil diverifikasi')->success()->send();
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
            'index' => ListBmnReturns::route('/'),
            'create' => CreateBmnReturn::route('/create'),
            'edit' => EditBmnReturn::route('/{record}/edit'),
        ];
    }
}
