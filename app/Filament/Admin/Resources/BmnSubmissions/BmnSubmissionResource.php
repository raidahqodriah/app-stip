<?php

namespace App\Filament\Admin\Resources\BmnSubmissions;

use App\Enums\BmnItemStatus;
use App\Enums\BmnMovementSource;
use App\Enums\ItemCondition;
use App\Enums\RequestStatus;
use App\Filament\Admin\Clusters\Bmn\BmnCluster;
use App\Filament\Admin\Resources\BmnSubmissions\Pages\CreateBmnSubmission;
use App\Filament\Admin\Resources\BmnSubmissions\Pages\EditBmnSubmission;
use App\Filament\Admin\Resources\BmnSubmissions\Pages\ListBmnSubmissions;
use App\Models\Bmn\BmnItem;
use App\Models\Bmn\BmnItemMovement;
use App\Models\Bmn\BmnSubmission;
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
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class BmnSubmissionResource extends Resource
{
    protected static ?string $model = BmnSubmission::class;

    protected static ?string $cluster = BmnCluster::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentPlus;

    protected static ?int $navigationSort = 2;

    public static function getModelLabel(): string
    {
        return __('filament.resources.bmn_submissions.label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament.resources.bmn_submissions.plural_label');
    }

    public static function getNavigationLabel(): string
    {
        return __('filament.resources.bmn_submissions.navigation_label');
    }

    protected static ?string $recordTitleAttribute = 'item_name';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('item_name')
                    ->label('Nama Barang')
                    ->required(),
                TextInput::make('bmn_code')
                    ->label('Kode Barang BMN (Opsional)')
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
                    ->label('Nomor Seri / Pabrik'),
                DatePicker::make('acquisition_date')
                    ->label('Tanggal Perolehan')
                    ->default(now())
                    ->required(),
                TextInput::make('acquisition_source')
                    ->label('Sumber Perolehan')
                    ->placeholder('Mis. DIPA BLU TA 2026 / APBN')
                    ->required(),
                Select::make('condition')
                    ->label('Kondisi Barang')
                    ->options(ItemCondition::class)
                    ->default(ItemCondition::Good)
                    ->required(),
                Select::make('unit_id')
                    ->label('Unit Kerja Pengusul')
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
                    ->label('Nama Penanggung Jawab Barang')
                    ->required(),
                Toggle::make('requires_decree')
                    ->label('Perlu Dokumen Penetapan Status Penggunaan (PSP)')
                    ->default(false),
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
            ->recordTitleAttribute('item_name')
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('item_name')
                    ->label('Nama Barang')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('bmn_code')
                    ->label('Kode BMN / NUP')
                    ->formatStateUsing(fn ($record) => ($record->bmn_code ?? '-').' / '.($record->register_number ?? '-'))
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
                SelectFilter::make('unit_id')
                    ->label('Unit')
                    ->relationship('unit', 'name'),
                SelectFilter::make('status')
                    ->label('Status')
                    ->options(RequestStatus::class),
            ])
            ->recordActions([
                // Verifikasi Petugas BMN -> Approve & buat master BMN
                Action::make('verify_and_approve')
                    ->label('Verifikasi & Terima')
                    ->icon('heroicon-o-check-badge')
                    ->color('success')
                    ->visible(fn (BmnSubmission $record) => in_array($record->status, [RequestStatus::Submitted, RequestStatus::RevisionRequested]))
                    ->requiresConfirmation()
                    ->modalHeading('Verifikasi dan Masukkan ke Rekap Inventaris BMN')
                    ->form([
                        Textarea::make('note')
                            ->label('Catatan Pemeriksaan')
                            ->placeholder('Barang dan dokumen pendukung sesuai.')
                            ->required(),
                    ])
                    ->action(function (BmnSubmission $record, array $data) {
                        // 1. Buat BmnItem resmi
                        $bmnItem = BmnItem::create([
                            'bmn_code' => $record->bmn_code,
                            'register_number' => $record->register_number,
                            'item_name' => $record->item_name,
                            'quantity' => $record->quantity,
                            'brand' => $record->brand,
                            'model' => $record->model,
                            'serial_number' => $record->serial_number,
                            'unit_id' => $record->unit_id,
                            'room_id' => $record->room_id,
                            'responsible_employee_id' => auth()->id() ?? 1,
                            'responsible_name' => $record->responsible_name,
                            'acquisition_date' => $record->acquisition_date,
                            'acquisition_source' => $record->acquisition_source,
                            'condition' => $record->condition,
                            'requires_decree' => $record->requires_decree,
                            'status' => BmnItemStatus::Active,
                        ]);

                        // 2. Catat perpindahan / penempatan awal
                        BmnItemMovement::create([
                            'bmn_item_id' => $bmnItem->id,
                            'from_room_id' => null,
                            'to_room_id' => $record->room_id,
                            'source' => BmnMovementSource::Submission,
                            'reason' => 'Penerimaan pengajuan BMN baru: '.$record->item_name,
                        ]);

                        // 3. Update status submission
                        $record->update([
                            'status' => RequestStatus::Approved,
                            'verified_by' => auth()->id() ?? 1,
                            'bmn_item_id' => $bmnItem->id,
                        ]);

                        // 4. Catat request log
                        RequestLog::create([
                            'loggable_type' => BmnSubmission::class,
                            'loggable_id' => $record->id,
                            'actor_type' => Employee::class,
                            'actor_id' => auth()->id() ?? 1,
                            'role' => 'officer',
                            'action' => 'approve_submission',
                            'from_status' => RequestStatus::Submitted->value,
                            'to_status' => RequestStatus::Approved->value,
                            'note' => $data['note'],
                        ]);

                        Notification::make()->title('BMN baru berhasil diverifikasi & masuk inventaris')->success()->send();
                    }),

                Action::make('request_revision')
                    ->label('Minta Revisi')
                    ->icon('heroicon-o-arrow-path')
                    ->color('orange')
                    ->visible(fn (BmnSubmission $record) => $record->status === RequestStatus::Submitted)
                    ->form([
                        Textarea::make('note')
                            ->label('Alasan Permintaan Revisi')
                            ->required(),
                    ])
                    ->action(function (BmnSubmission $record, array $data) {
                        $record->update(['status' => RequestStatus::RevisionRequested]);
                        RequestLog::create([
                            'loggable_type' => BmnSubmission::class,
                            'loggable_id' => $record->id,
                            'actor_type' => Employee::class,
                            'actor_id' => auth()->id() ?? 1,
                            'role' => 'officer',
                            'action' => 'request_revision',
                            'from_status' => RequestStatus::Submitted->value,
                            'to_status' => RequestStatus::RevisionRequested->value,
                            'note' => $data['note'],
                        ]);
                        Notification::make()->title('Pengajuan dikembalikan untuk revisi')->warning()->send();
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
            'index' => ListBmnSubmissions::route('/'),
            'create' => CreateBmnSubmission::route('/create'),
            'edit' => EditBmnSubmission::route('/{record}/edit'),
        ];
    }
}
