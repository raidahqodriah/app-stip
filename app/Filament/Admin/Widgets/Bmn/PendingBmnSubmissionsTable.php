<?php

namespace App\Filament\Admin\Widgets\Bmn;

use App\Enums\BmnItemStatus;
use App\Enums\ItemCondition;
use App\Enums\RequestStatus;
use App\Models\Bmn\BmnItem;
use App\Models\Bmn\BmnSubmission;
use App\Models\Core\Employee;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class PendingBmnSubmissionsTable extends BaseWidget
{
    protected static ?int $sort = 9;

    protected int|string|array $columnSpan = 'full';

    public function getHeading(): ?string
    {
        return __('filament.widgets.titles.pending_bmn_submissions');
    }

    public static function canView(): bool
    {
        /** @var Employee|null $user */
        $user = auth()->user();

        return $user?->hasAnyRole(['admin', 'leader', 'officer', 'unit_admin']) ?? false;
    }

    public function table(Table $table): Table
    {
        /** @var Employee|null $user */
        $user = auth()->user();

        $query = BmnSubmission::query()
            ->with(['unit', 'room', 'submittedBy'])
            ->where('status', RequestStatus::Submitted)
            ->latest('created_at');

        if ($user && $user->hasRole('unit_admin') && ! $user->hasAnyRole(['admin', 'leader', 'officer'])) {
            $query->where('unit_id', $user->unit_id);
        }

        return $table
            ->query($query)
            ->columns([
                TextColumn::make('created_at')
                    ->label('Tgl Pengajuan')
                    ->date('d M Y')
                    ->icon('heroicon-m-calendar'),

                TextColumn::make('item_name')
                    ->label('Nama Barang')
                    ->description(fn (BmnSubmission $record): ?string => $record->brand.' '.$record->model)
                    ->weight('bold')
                    ->searchable(),

                TextColumn::make('unit.name')
                    ->label('Unit Kerja Pengusul')
                    ->icon('heroicon-m-building-office'),

                TextColumn::make('quantity')
                    ->label('Jumlah')
                    ->suffix(' Unit')
                    ->alignCenter(),

                TextColumn::make('condition')
                    ->label('Kondisi')
                    ->badge(),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge(),
            ])
            ->actions([
                Action::make('verify')
                    ->label('Verifikasi & Masukkan Rekap')
                    ->icon('heroicon-m-check-badge')
                    ->color('success')
                    ->visible(fn (): bool => auth()->user()?->hasAnyRole(['admin', 'officer']) ?? false)
                    ->requiresConfirmation()
                    ->modalHeading('Verifikasi Pengajuan BMN')
                    ->modalDescription('Apakah data barang valid dan siap dimasukkan ke master rekap inventaris BMN?')
                    ->action(function (BmnSubmission $record): void {
                        $item = BmnItem::create([
                            'bmn_code' => $record->bmn_code ?? 'BMN-'.time(),
                            'register_number' => $record->register_number ?? '000001',
                            'item_name' => $record->item_name,
                            'quantity' => $record->quantity,
                            'brand' => $record->brand,
                            'model' => $record->model,
                            'serial_number' => $record->serial_number,
                            'unit_id' => $record->unit_id,
                            'room_id' => $record->room_id,
                            'responsible_employee_id' => $record->submitted_by,
                            'responsible_name' => $record->responsible_name,
                            'acquisition_date' => $record->acquisition_date,
                            'acquisition_source' => $record->acquisition_source,
                            'condition' => ItemCondition::tryFrom($record->condition->value) ?? ItemCondition::Good,
                            'requires_decree' => $record->requires_decree,
                            'status' => BmnItemStatus::Active,
                        ]);

                        $record->update([
                            'status' => RequestStatus::Approved,
                            'verified_by' => auth()->id() ?? 1,
                            'bmn_item_id' => $item->id,
                        ]);

                        Notification::make()
                            ->title('Pengajuan BMN Berhasil Diverifikasi')
                            ->body('Barang telah ditambahkan ke master inventaris BMN.')
                            ->success()
                            ->send();
                    }),
            ])
            ->paginated([5]);
    }
}
