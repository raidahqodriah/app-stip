<?php

namespace Database\Seeders;

use App\Models\Core\Employee;
use App\Models\Core\Unit;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $password = Hash::make('password');

        $pimpinan = Unit::where('code', 'PIMPINAN')->first();
        $prodiNau = Unit::where('code', 'PRODI-NAU')->first();
        $prodiTek = Unit::where('code', 'PRODI-TEK')->first();
        $unitSpp = Unit::where('code', 'UNIT-SPP')->first();
        $unitBmn = Unit::where('code', 'UNIT-BMN')->first();
        $unitPerpus = Unit::where('code', 'UNIT-PERPUS')->first();

        $employees = [
            [
                'email' => 'admin@stipjakarta.ac.id',
                'name' => 'Administrator Sistem',
                'employee_number' => '198501012010011001',
                'position' => 'Admin Sistem',
                'unit_id' => $pimpinan->id,
                'phone' => '081234567890',
                'password' => $password,
                'is_active' => true,
            ],
            [
                'email' => 'ketua@stipjakarta.ac.id',
                'name' => 'Capt. H. Sudirman, M.Mar.',
                'employee_number' => '197001011995031001',
                'position' => 'Ketua STIP Jakarta',
                'unit_id' => $pimpinan->id,
                'phone' => '081234567891',
                'password' => $password,
                'is_active' => true,
            ],
            [
                'email' => 'ka.spp@stipjakarta.ac.id',
                'name' => 'Fauzan Afif, S.Si., M.T.',
                'employee_number' => '197805122003121002',
                'position' => 'Kepala Unit Sarana Praktik Pelaut (SPP)',
                'unit_id' => $unitSpp->id,
                'phone' => '081234567892',
                'password' => $password,
                'is_active' => true,
            ],
            [
                'email' => 'petugas.spp@stipjakarta.ac.id',
                'name' => 'Petugas SPP Laboratorium',
                'employee_number' => '199002152015021003',
                'position' => 'Petugas Verifikasi Lab SPP',
                'unit_id' => $unitSpp->id,
                'phone' => '081234567893',
                'password' => $password,
                'is_active' => true,
            ],
            [
                'email' => 'petugas.bmn@stipjakarta.ac.id',
                'name' => 'Raidah Qodriah, S.E.',
                'employee_number' => '199203182016012002',
                'position' => 'Petugas Pengelola BMN dan Rumah Tangga',
                'unit_id' => $unitBmn->id,
                'phone' => '081234567894',
                'password' => $password,
                'is_active' => true,
            ],
            [
                'email' => 'petugas.perpus@stipjakarta.ac.id',
                'name' => 'Staf Pengelola Perpustakaan',
                'employee_number' => '198807122012012001',
                'position' => 'Petugas Layanan Perpustakaan',
                'unit_id' => $unitPerpus->id,
                'phone' => '081234567895',
                'password' => $password,
                'is_active' => true,
            ],
            [
                'email' => 'admin.teknika@stipjakarta.ac.id',
                'name' => 'Admin Unit Kerja Teknika',
                'employee_number' => '199406102019031005',
                'position' => 'Admin Unit Kerja Teknika',
                'unit_id' => $prodiTek->id,
                'phone' => '081234567896',
                'password' => $password,
                'is_active' => true,
            ],
            [
                'email' => 'dosen.teknika@stipjakarta.ac.id',
                'name' => 'Ir. Bambang Wijaya, M.T.',
                'employee_number' => '198208172008011005',
                'position' => 'Dosen Prodi Teknika',
                'unit_id' => $prodiTek->id,
                'phone' => '081234567897',
                'password' => $password,
                'is_active' => true,
            ],
            [
                'email' => 'dosen.nautika@stipjakarta.ac.id',
                'name' => 'Capt. Hendra Gunawan, M.Mar.',
                'employee_number' => '198309202009021006',
                'position' => 'Dosen Prodi Nautika',
                'unit_id' => $prodiNau->id,
                'phone' => '081234567898',
                'password' => $password,
                'is_active' => true,
            ],
            [
                'email' => 'joko.prasetyo@stipjakarta.ac.id',
                'name' => 'Joko Prasetyo, S.Kom.',
                'employee_number' => '198711112011011007',
                'position' => 'Staf Pengelola TI',
                'unit_id' => $pimpinan->id,
                'phone' => '081234567899',
                'password' => $password,
                'is_active' => true,
            ],
        ];

        foreach ($employees as $data) {
            Employee::updateOrCreate(
                ['employee_number' => $data['employee_number']],
                $data
            );
        }

        // Tautkan kepala unit
        $ketua = Employee::where('email', 'ketua@stipjakarta.ac.id')->first();
        if ($ketua && $pimpinan) {
            $pimpinan->update(['head_employee_id' => $ketua->id]);
        }

        $kaSpp = Employee::where('email', 'ka.spp@stipjakarta.ac.id')->first();
        if ($kaSpp && $unitSpp) {
            $unitSpp->update(['head_employee_id' => $kaSpp->id]);
        }
    }
}
