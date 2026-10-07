<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            SettingSeeder::class,
            DocumentTemplateSeeder::class,
            UnitSeeder::class,
            RoleAndPermissionSeeder::class,
            EmployeeSeeder::class,
            StudentSeeder::class,
            RoomSeeder::class,
            CurriculumSeeder::class,
            MaterialSeeder::class,
            ResidenceSeeder::class,
            BookSeeder::class,
            BmnSeeder::class,
            DashboardDemoSeeder::class,
        ]);
    }
}
