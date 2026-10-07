<?php

namespace App\Filament\Admin\Resources\Roles;

use App\Filament\Admin\Clusters\Master\MasterCluster;
use App\Filament\Admin\Resources\Roles\Pages\CreateRole;
use App\Filament\Admin\Resources\Roles\Pages\EditRole;
use App\Filament\Admin\Resources\Roles\Pages\ListRoles;
use App\Policies\RolePolicy;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role;

class RoleResource extends Resource
{
    protected static ?string $model = Role::class;

    protected static ?string $cluster = MasterCluster::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedShieldCheck;

    protected static ?int $navigationSort = 7;

    public static function getModelLabel(): string
    {
        return __('filament.resources.roles.label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament.resources.roles.plural_label');
    }

    public static function getNavigationLabel(): string
    {
        return __('filament.resources.roles.navigation_label');
    }

    /**
     * Deskripsi human-readable kamus izin SILT-STIP.
     *
     * @var array<string, string>
     */
    public const PERMISSION_DESCRIPTIONS = [
        // Core
        'core.employee.view' => 'Melihat data dan profil pegawai & dosen',
        'core.employee.create' => 'Mendaftarkan akun pegawai baru',
        'core.employee.update' => 'Memperbarui data akun pegawai',
        'core.employee.delete' => 'Menonaktifkan akun pegawai',
        'core.student.view' => 'Melihat data dan direktori taruna',
        'core.student.create' => 'Menambah taruna baru / impor batch',
        'core.student.update' => 'Memperbarui data akun taruna',
        'core.student.delete' => 'Menonaktifkan akun taruna',
        'core.role.manage' => 'Mengelola role & permission (Otorisasi Akses)',
        'core.unit.view' => 'Melihat master unit kerja & prodi',
        'core.unit.manage' => 'Mengelola unit kerja & prodi',
        'core.room.view' => 'Melihat daftar ruangan & laboratorium',
        'core.room.manage' => 'Mengelola ruangan, lab, dan fasilitas',
        'core.template.manage' => 'Mengelola template dokumen cetak dinamis',
        'core.setting.manage' => 'Mengelola konfigurasi sistem & kuota',
        'core.audit.view' => 'Melihat log audit trail perubahan sistem',

        // Lab
        'lab.schedule.view' => 'Melihat jadwal detail lab & slot waktu',
        'lab.booking.view-all' => 'Melihat seluruh booking lab institusi',
        'lab.booking.view-own' => 'Melihat booking milik sendiri / sebagai PJ',
        'lab.booking.create' => 'Mengajukan permohonan booking lab baru',
        'lab.booking.create-on-behalf' => 'Mengajukan booking lab atas nama pihak lain',
        'lab.booking.update' => 'Mengubah permohonan booking lab',
        'lab.booking.cancel' => 'Membatalkan booking lab',
        'lab.booking.verify' => 'Verifikasi Tahap 1 oleh Petugas SPP',
        'lab.booking.approve' => 'Persetujuan Tahap 2 oleh Kepala Unit SPP',
        'lab.booking.realize' => 'Mencatat realisasi sesi & insiden praktikum',
        'lab.curriculum.manage' => 'Mengelola kurikulum, silabus, & STCW',
        'lab.material.manage' => 'Mengelola bahan praktik, kit, & alat',
        'lab.blackout.manage' => 'Mengatur tanggal libur/blackout lab',
        'lab.document.print' => 'Mencetak konfirmasi booking lab',
        'lab.report.view' => 'Melihat analitik & ekspor laporan lab',

        // BMN
        'bmn.item.view-all' => 'Melihat seluruh rekapitulasi inventaris BMN',
        'bmn.item.view-unit' => 'Melihat inventaris BMN unit kerja sendiri',
        'bmn.item.manage' => 'Mengelola master data inventaris BMN (NUP)',
        'bmn.submission.view-all' => 'Melihat seluruh pengajuan BMN baru',
        'bmn.submission.view-unit' => 'Melihat pengajuan BMN unit sendiri',
        'bmn.submission.create' => 'Mengajukan permohonan penetapan BMN baru',
        'bmn.submission.process' => 'Pemeriksaan fisik & verifikasi pengajuan BMN',
        'bmn.return.view-all' => 'Melihat seluruh pengembalian BMN',
        'bmn.return.view-unit' => 'Melihat pengembalian BMN unit sendiri',
        'bmn.return.create' => 'Mengajukan pengembalian/mutasi BMN',
        'bmn.return.process' => 'Pemeriksaan fisik & validasi pengembalian BMN',
        'bmn.movement.view' => 'Melihat riwayat mutasi perpindahan BMN',
        'bmn.document.print' => 'Mencetak dokumen penetapan & bukti terima',
        'bmn.report.view' => 'Melihat dashboard & laporan mutasi BMN',

        // Rumah Dinas
        'residence.master.manage' => 'Mengelola master data fisik rumah dinas',
        'residence.permit.view-all' => 'Melihat seluruh permohonan izin (SIP)',
        'residence.permit.view-unit' => 'Melihat permohonan SIP unit sendiri',
        'residence.permit.create' => 'Mengajukan permohonan surat izin penghunian',
        'residence.permit.verify' => 'Pemeriksaan kelayakan oleh Petugas Rumah Tangga',
        'residence.permit.approve' => 'Persetujuan akhir SIP oleh Ketua STIP',
        'residence.document.print' => 'Mencetak surat izin penghuni resmi (SIP)',
        'residence.report.view' => 'Melihat statistik & rekap rumah dinas',

        // Perpustakaan
        'library.book.view' => 'Melihat katalog buku perpustakaan',
        'library.book.manage' => 'Mengelola katalog buku, stok, rak, & ISBN',
        'library.circulation.view-all' => 'Melihat seluruh riwayat sirkulasi peminjaman',
        'library.circulation.view-own' => 'Melihat riwayat peminjaman buku milik sendiri',
        'library.circulation.process' => 'Memproses transaksi sirkulasi di loket',
        'library.fine.confirm' => 'Mengonfirmasi pelunasan denda buku',
        'library.report.view' => 'Melihat laporan sirkulasi & denda perpus',
    ];

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Peran')
                    ->description('Tentukan nama peran dan batasan guard otentikasi.')
                    ->schema([
                        TextInput::make('name')
                            ->label('Kode / Nama Peran')
                            ->placeholder('Mis. officer_lab, reviewer, dll.')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true, modifyRuleUsing: fn ($rule) => $rule->where('guard_name', 'employee'))
                            ->disabled(fn (?Model $record) => $record && in_array($record->name, RolePolicy::SYSTEM_ROLES, true)),
                        TextInput::make('guard_name')
                            ->label('Guard Otentikasi')
                            ->default('employee')
                            ->readOnly()
                            ->dehydrated(),
                    ])
                    ->columns(2),

                Section::make('Matriks Otorisasi & Hak Akses')
                    ->description('Pilih daftar izin yang diberikan kepada peran ini. Gunakan tombol pilih semua atau kotak pencarian untuk kemudahan konfigurasi.')
                    ->schema([
                        CheckboxList::make('permissions')
                            ->label('Daftar Izin Sistem (Permissions)')
                            ->relationship(
                                name: 'permissions',
                                titleAttribute: 'name',
                                modifyQueryUsing: fn (Builder $query) => $query->where('guard_name', 'employee')->orderBy('name'),
                            )
                            ->getOptionDescriptionFromRecordUsing(fn (Model $record) => self::PERMISSION_DESCRIPTIONS[$record->name] ?? null)
                            ->searchable()
                            ->bulkToggleable()
                            ->columns(2)
                            ->gridDirection('row'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('name')
                    ->label('Nama Peran')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'admin' => 'danger',
                        'leader' => 'info',
                        'officer' => 'primary',
                        'unit_admin' => 'warning',
                        'teacher' => 'success',
                        default => 'gray',
                    })
                    ->searchable()
                    ->sortable(),

                TextColumn::make('guard_name')
                    ->label('Guard')
                    ->badge()
                    ->color('gray'),

                TextColumn::make('permissions_count')
                    ->label('Jumlah Izin')
                    ->counts('permissions')
                    ->badge()
                    ->color('info')
                    ->sortable(),

                TextColumn::make('users_count')
                    ->label('Jumlah Pegawai')
                    ->counts('users')
                    ->badge()
                    ->color('success')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Dibuat Pada')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->label('Diperbarui Pada')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make()
                    ->visible(fn (Role $record) => ! in_array($record->name, RolePolicy::SYSTEM_ROLES, true)),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->action(function ($records) {
                            $records->filter(fn (Role $record) => ! in_array($record->name, RolePolicy::SYSTEM_ROLES, true))->each->delete();
                        }),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListRoles::route('/'),
            'create' => CreateRole::route('/create'),
            'edit' => EditRole::route('/{record}/edit'),
        ];
    }
}
