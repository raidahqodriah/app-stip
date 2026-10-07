<?php

namespace Database\Seeders;

use App\Models\Core\Student;
use App\Models\Core\Unit;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $password = Hash::make('password');

        $prodiNau = Unit::where('code', 'PRODI-NAU')->first();
        $prodiTek = Unit::where('code', 'PRODI-TEK')->first();
        $prodiKalk = Unit::where('code', 'PRODI-KALK')->first();

        $students = [
            [
                'email' => 'taruna.nautika1@student.stipjakarta.ac.id',
                'name' => 'Ahmad Fauzi',
                'student_number' => '2201001',
                'batch_year' => 2022,
                'class_group' => 'N-IV-A',
                'unit_id' => $prodiNau->id,
                'phone' => '082111222001',
                'password' => $password,
                'is_active' => true,
            ],
            [
                'email' => 'taruna.teknika1@student.stipjakarta.ac.id',
                'name' => 'Dimas Arya Pratama',
                'student_number' => '2202001',
                'batch_year' => 2022,
                'class_group' => 'T-IV-A',
                'unit_id' => $prodiTek->id,
                'phone' => '082111222002',
                'password' => $password,
                'is_active' => true,
            ],
            [
                'email' => 'taruna.kalk1@student.stipjakarta.ac.id',
                'name' => 'Siti Nurhaliza',
                'student_number' => '2203001',
                'batch_year' => 2022,
                'class_group' => 'K-IV-A',
                'unit_id' => $prodiKalk->id,
                'phone' => '082111222003',
                'password' => $password,
                'is_active' => true,
            ],
            [
                'email' => 'taruna.teknika2@student.stipjakarta.ac.id',
                'name' => 'Rian Hidayat',
                'student_number' => '2402015',
                'batch_year' => 2024,
                'class_group' => 'T-II-A',
                'unit_id' => $prodiTek->id,
                'phone' => '082111222004',
                'password' => $password,
                'is_active' => true,
            ],
            [
                'email' => 'taruna.nautika2@student.stipjakarta.ac.id',
                'name' => 'Bagas Maulana',
                'student_number' => '2401020',
                'batch_year' => 2024,
                'class_group' => 'N-II-B',
                'unit_id' => $prodiNau->id,
                'phone' => '082111222005',
                'password' => $password,
                'is_active' => true,
            ],
        ];

        foreach ($students as $data) {
            Student::updateOrCreate(
                ['student_number' => $data['student_number']],
                $data
            );
        }
    }
}
