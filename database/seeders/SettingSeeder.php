<?php

namespace Database\Seeders;

use App\Models\Core\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            // Perpustakaan (§6.3)
            'library_loan_period_days' => '7',
            'library_max_books_student' => '3',
            'library_max_books_employee' => '5',
            'library_fine_per_day' => '1000',

            // Lab / SPP (§3.3 & §12)
            'lab_slot_duration_minutes' => '30',
            'lab_operating_hours_start' => '07:30',
            'lab_operating_hours_end' => '16:00',
            'lab_min_lead_time_days' => '3',
            'lab_max_lead_time_days' => '60',
            'lab_sla_verify_days' => '1',
            'lab_sla_approve_days' => '1',

            // Informasi Institusi
            'institution_name' => 'Sekolah Tinggi Ilmu Pelayaran (STIP) Jakarta',
            'institution_address' => 'Jl. Marunda Makmur No. 1, Cilincing, Jakarta Utara 14150',
            'institution_phone' => '(021) 8899000',
            'institution_email' => 'info@stipjakarta.ac.id',
        ];

        foreach ($settings as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }
    }
}
