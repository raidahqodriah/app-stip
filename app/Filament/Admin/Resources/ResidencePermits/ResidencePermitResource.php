<?php

namespace App\Filament\Admin\Resources\ResidencePermits;

use App\Enums\RequestStatus;
use App\Filament\Admin\Clusters\Bmn\BmnCluster;
use App\Filament\Admin\Resources\ResidencePermits\Pages\CreateResidencePermit;
use App\Filament\Admin\Resources\ResidencePermits\Pages\EditResidencePermit;
use App\Filament\Admin\Resources\ResidencePermits\Pages\ListResidencePermits;
use App\Models\Bmn\ResidencePermit;
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
use Filament\Infolists\Components\TextEntry;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ResidencePermitResource extends Resource
{
    protected static ?string $model = ResidencePermit::class;

    protected static ?string $cluster = BmnCluster::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedIdentification;

    protected static ?int $navigationSort = 5;

    public static function getModelLabel(): string
    {
        return __('filament.resources.residence_permits.label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament.resources.residence_permits.plural_label');
    }

    public static function getNavigationLabel(): string
    {
        return __('filament.resources.residence_permits.navigation_label');
    }

    protected static ?string $recordTitleAttribute = 'permit_number';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('permit_number')
                    ->label('Nomor Surat Izin Penghuni (SIP)')
                    ->placeholder('Terbit otomatis saat disetujui Ketua STIP')
                    ->readOnly(),
                Select::make('employee_id')
                    ->label('Pegawai Calon Penghuni')
                    ->relationship('employee', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('unit_id')
                    ->label('Unit Kerja Pengusul')
                    ->relationship('unit', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('official_residence_id')
                    ->label('Unit Rumah Dinas')
                    ->relationship('officialResidence', 'house_number')
                    ->searchable()
                    ->preload()
                    ->required(),
                DatePicker::make('occupancy_start')
                    ->label('Awal Masa Penghunian')
                    ->default(now())
                    ->required(),
                DatePicker::make('occupancy_end')
                    ->label('Akhir Masa Penghunian')
                    ->default(now()->addYears(2))
                    ->required(),
                Textarea::make('occupancy_notes')
                    ->label('Keterangan / Alasan Pengajuan')
                    ->columnSpanFull(),
                Select::make('status')
                    ->label('Status')
                    ->options(RequestStatus::class)
                    ->default(RequestStatus::Submitted)
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('permit_number')
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('permit_number')
                    ->label('No. SIP')
                    ->placeholder('(Belum Terbit)')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('employee.name')
                    ->label('Nama Pegawai')
                    ->searchable(),
                TextColumn::make('unit.name')
                    ->label('Unit Kerja')
                    ->searchable(),
                TextColumn::make('officialResidence.house_number')
                    ->label('Rumah Dinas')
                    ->searchable(),
                TextColumn::make('occupancy_start')
                    ->label('Periode Izin')
                    ->formatStateUsing(fn ($record) => $record->occupancy_start->format('d/m/Y').' - '.$record->occupancy_end->format('d/m/Y')),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (RequestStatus $state): string => match ($state) {
                        RequestStatus::Draft => 'gray',
                        RequestStatus::Submitted => 'warning',
                        RequestStatus::RevisionRequested => 'orange',
                        RequestStatus::Verified => 'info',
                        RequestStatus::Approved => 'success',
                        RequestStatus::Rejected => 'danger',
                        default => 'gray',
                    }),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options(RequestStatus::class),
            ])
            ->recordActions([
                // Verifikasi Petugas Rumah Tangga (Tahap 1)
                Action::make('verify')
                    ->label('Verifikasi')
                    ->icon('heroicon-o-check')
                    ->color('info')
                    ->visible(fn (ResidencePermit $record) => in_array($record->status, [RequestStatus::Submitted, RequestStatus::RevisionRequested]))
                    ->requiresConfirmation()
                    ->modalHeading('Verifikasi Kelayakan Penghunian Rumah Dinas')
                    ->action(function (ResidencePermit $record) {
                        $record->update([
                            'status' => RequestStatus::Verified,
                            'verified_by' => auth()->id() ?? 1,
                        ]);

                        RequestLog::create([
                            'loggable_type' => ResidencePermit::class,
                            'loggable_id' => $record->id,
                            'actor_type' => Employee::class,
                            'actor_id' => auth()->id() ?? 1,
                            'role' => 'officer',
                            'action' => 'verify_residence_permit',
                            'from_status' => RequestStatus::Submitted->value,
                            'to_status' => RequestStatus::Verified->value,
                            'note' => 'Dokumen dan kelayakan pegawai telah diverifikasi Petugas RT.',
                        ]);

                        Notification::make()->title('Pengajuan SIP berhasil diverifikasi')->success()->send();
                    }),

                // Persetujuan Ketua STIP (Tahap 2)
                Action::make('approve')
                    ->label('Setujui (Ketua STIP)')
                    ->icon('heroicon-o-check-badge')
                    ->color('success')
                    ->visible(fn (ResidencePermit $record) => $record->status === RequestStatus::Verified)
                    ->requiresConfirmation()
                    ->modalHeading('Persetujuan Akhir oleh Ketua STIP')
                    ->action(function (ResidencePermit $record) {
                        $sipNumber = 'SIP/'.date('Y').'/'.str_pad($record->id, 4, '0', STR_PAD_LEFT);
                        $record->update([
                            'status' => RequestStatus::Approved,
                            'approved_by' => auth()->id() ?? 1,
                            'permit_number' => $sipNumber,
                        ]);

                        RequestLog::create([
                            'loggable_type' => ResidencePermit::class,
                            'loggable_id' => $record->id,
                            'actor_type' => Employee::class,
                            'actor_id' => auth()->id() ?? 1,
                            'role' => 'leader',
                            'action' => 'approve_residence_permit',
                            'from_status' => RequestStatus::Verified->value,
                            'to_status' => RequestStatus::Approved->value,
                            'note' => 'Disetujui Ketua STIP Jakarta. Nomor izin terbit: '.$sipNumber,
                        ]);

                        Notification::make()->title('SIP disetujui & nomor surat diterbitkan: '.$sipNumber)->success()->send();
                    }),

                // Preview / Cetak SIP
                Action::make('print_sip')
                    ->label('Cetak SIP')
                    ->icon('heroicon-o-printer')
                    ->color('gray')
                    ->visible(fn (ResidencePermit $record) => $record->status === RequestStatus::Approved)
                    ->modalHeading('Surat Izin Penghuni (SIP) Rumah Dinas')
                    ->infolist([
                        TextEntry::make('permit_number')->label('Nomor Izin'),
                        TextEntry::make('employee.name')->label('Nama Penghuni'),
                        TextEntry::make('unit.name')->label('Unit Kerja'),
                        TextEntry::make('officialResidence.house_number')->label('Nomor Rumah'),
                        TextEntry::make('officialResidence.address')->label('Alamat Rumah Dinas'),
                        TextEntry::make('occupancy_start')->label('Mulai Berlaku')->date(),
                        TextEntry::make('occupancy_end')->label('Berakhir Pada')->date(),
                    ])
                    ->modalSubmitActionLabel('Unduh Dokumen Resmi')
                    ->action(fn () => Notification::make()->title('Surat Izin Penghuni siap dicetak')->success()->send()),

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
            'index' => ListResidencePermits::route('/'),
            'create' => CreateResidencePermit::route('/create'),
            'edit' => EditResidencePermit::route('/{record}/edit'),
        ];
    }
}
