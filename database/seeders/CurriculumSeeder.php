<?php

namespace Database\Seeders;

use App\Enums\SubjectCategory;
use App\Models\Core\Room;
use App\Models\Core\Unit;
use App\Models\Lab\Competence;
use App\Models\Lab\ImoModelCourse;
use App\Models\Lab\Subject;
use Illuminate\Database\Seeder;

class CurriculumSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $prodiTek = Unit::where('code', 'PRODI-TEK')->first();
        $prodiNau = Unit::where('code', 'PRODI-NAU')->first();
        $prodiKalk = Unit::where('code', 'PRODI-KALK')->first();

        // 1. IMO Model Courses (§Lampiran A)
        $imoCourses = [
            ['code' => '7.01', 'title' => 'Master and Chief Mate', 'edition_year' => 2014],
            ['code' => '7.02', 'title' => 'Chief Engineer Officer and Second Engineer Officer', 'edition_year' => 2014],
            ['code' => '7.03', 'title' => 'Officer in Charge of a Navigational Watch', 'edition_year' => 2014],
            ['code' => '7.04', 'title' => 'Officer in Charge of an Engineering Watch', 'edition_year' => 2014],
            ['code' => '7.08', 'title' => 'Electro-Technical Officer', 'edition_year' => 2014],
            ['code' => '2.07', 'title' => 'Engine-Room Simulator', 'edition_year' => 2017],
            ['code' => '1.10', 'title' => 'Dangerous, Hazardous and Harmful Cargoes', 'edition_year' => 2014],
            ['code' => '1.27', 'title' => 'Operational Use of ECDIS', 'edition_year' => 2012],
            ['code' => '3.17', 'title' => 'Maritime English', 'edition_year' => 2015],
            ['code' => '6.09', 'title' => 'Training Course for Instructors', 'edition_year' => 2017],
            ['code' => '3.12', 'title' => 'Assessment, Examination and Certification of Seafarers', 'edition_year' => 2017],
        ];

        foreach ($imoCourses as $course) {
            ImoModelCourse::updateOrCreate(['code' => $course['code']], $course);
        }

        // 2. Competences
        $course704 = ImoModelCourse::where('code', '7.04')->first();
        $course703 = ImoModelCourse::where('code', '7.03')->first();
        $course708 = ImoModelCourse::where('code', '7.08')->first();
        $course207 = ImoModelCourse::where('code', '2.07')->first();
        $course110 = ImoModelCourse::where('code', '1.10')->first();
        $course317 = ImoModelCourse::where('code', '3.17')->first();

        $competences = [
            // 7.04
            ['imo_model_course_id' => $course704->id, 'code' => '7.04-C1', 'title' => 'Operate Marine Main Propulsion and Auxiliary Machinery', 'stcw_reference' => 'STCW A-III/1.1'],
            ['imo_model_course_id' => $course704->id, 'code' => '7.04-C2', 'title' => 'Operate Electrical, Electronic and Control Systems', 'stcw_reference' => 'STCW A-III/1.2'],
            ['imo_model_course_id' => $course704->id, 'code' => '7.04-C3', 'title' => 'Maintenance and Repair of Shipboard Machinery', 'stcw_reference' => 'STCW A-III/1.3'],
            // 7.03
            ['imo_model_course_id' => $course703->id, 'code' => '7.03-C1', 'title' => 'Plan and Conduct a Passage and Determine Position', 'stcw_reference' => 'STCW A-II/1.1'],
            ['imo_model_course_id' => $course703->id, 'code' => '7.03-C2', 'title' => 'Monitor the Loading, Stowage, Securing and Care of Cargoes', 'stcw_reference' => 'STCW A-II/1.2'],
            // 7.08
            ['imo_model_course_id' => $course708->id, 'code' => '7.08-C1', 'title' => 'Monitor the Operation of Electrical and Electronic Systems', 'stcw_reference' => 'STCW A-III/6.1'],
            ['imo_model_course_id' => $course708->id, 'code' => '7.08-C2', 'title' => 'Automated Control and Marine Instrumentation', 'stcw_reference' => 'STCW A-III/6.2'],
            // 2.07
            ['imo_model_course_id' => $course207->id, 'code' => '2.07-C1', 'title' => 'Engine Room Operations and Plant Familiarization', 'stcw_reference' => 'STCW A-III/1 Simulator'],
            ['imo_model_course_id' => $course207->id, 'code' => '2.07-C2', 'title' => 'Emergency Procedures and Fault Finding in Propulsion Plant', 'stcw_reference' => 'STCW A-III/2 Simulator'],
            // 1.10
            ['imo_model_course_id' => $course110->id, 'code' => '1.10-C1', 'title' => 'Safe Handling of Dangerous Goods and Bulk Cargoes', 'stcw_reference' => 'STCW B-V/c'],
            // 3.17
            ['imo_model_course_id' => $course317->id, 'code' => '3.17-C1', 'title' => 'Effective Communication in Maritime English (SMCP)', 'stcw_reference' => 'STCW A-II/1.3 & A-III/1.4'],
        ];

        foreach ($competences as $comp) {
            Competence::updateOrCreate(
                ['imo_model_course_id' => $comp['imo_model_course_id'], 'code' => $comp['code']],
                $comp
            );
        }

        // 3. Subjects
        $subjects = [
            [
                'code' => 'TEK-201',
                'name' => 'Otomasi dan Sistem Kontrol Kapal',
                'category' => SubjectCategory::Teknika,
                'semester' => 3,
                'credits' => 3,
                'unit_id' => $prodiTek->id,
                'is_active' => true,
                'competences' => ['7.04-C2', '7.08-C2'],
                'rooms' => ['ACSL'],
            ],
            [
                'code' => 'TEK-202',
                'name' => 'Kelistrikan Kapal',
                'category' => SubjectCategory::Teknika,
                'semester' => 3,
                'credits' => 3,
                'unit_id' => $prodiTek->id,
                'is_active' => true,
                'competences' => ['7.04-C2', '7.08-C1'],
                'rooms' => ['EEL'],
            ],
            [
                'code' => 'TEK-301',
                'name' => 'Praktik Bengkel dan Permesinan',
                'category' => SubjectCategory::Teknika,
                'semester' => 4,
                'credits' => 4,
                'unit_id' => $prodiTek->id,
                'is_active' => true,
                'competences' => ['7.04-C3'],
                'rooms' => ['EWS'],
            ],
            [
                'code' => 'TEK-302',
                'name' => 'Motor Diesel dan Turbin Kapal',
                'category' => SubjectCategory::Teknika,
                'semester' => 5,
                'credits' => 4,
                'unit_id' => $prodiTek->id,
                'is_active' => true,
                'competences' => ['7.04-C1'],
                'rooms' => ['MEL'],
            ],
            [
                'code' => 'TEK-401',
                'name' => 'Simulator Kamar Mesin (ERCS)',
                'category' => SubjectCategory::Teknika,
                'semester' => 6,
                'credits' => 4,
                'unit_id' => $prodiTek->id,
                'is_active' => true,
                'competences' => ['2.07-C1', '2.07-C2'],
                'rooms' => ['ERCS'],
            ],
            [
                'code' => 'NAU-201',
                'name' => 'Penanganan dan Pengaturan Muatan',
                'category' => SubjectCategory::Nautika,
                'semester' => 3,
                'credits' => 3,
                'unit_id' => $prodiNau->id,
                'is_active' => true,
                'competences' => ['7.03-C2', '1.10-C1'],
                'rooms' => ['CHL'],
            ],
            [
                'code' => 'NAU-301',
                'name' => 'Bahasa Inggris Maritim dan Komunikasi SMCP',
                'category' => SubjectCategory::Nautika,
                'semester' => 4,
                'credits' => 2,
                'unit_id' => $prodiNau->id,
                'is_active' => true,
                'competences' => ['3.17-C1'],
                'rooms' => ['LTL'],
            ],
            [
                'code' => 'NAU-302',
                'name' => 'Simulasi Navigasi Berbasis Komputer (CBT)',
                'category' => SubjectCategory::Nautika,
                'semester' => 5,
                'credits' => 3,
                'unit_id' => $prodiNau->id,
                'is_active' => true,
                'competences' => ['7.03-C1'],
                'rooms' => ['CBT'],
            ],
            [
                'code' => 'KLK-201',
                'name' => 'Manajemen Muatan Berbahaya dan Logistik Pelabuhan',
                'category' => SubjectCategory::Kalk,
                'semester' => 3,
                'credits' => 3,
                'unit_id' => $prodiKalk->id,
                'is_active' => true,
                'competences' => ['1.10-C1'],
                'rooms' => ['CHL'],
            ],
        ];

        foreach ($subjects as $item) {
            $compCodes = $item['competences'];
            $roomCodes = $item['rooms'];
            unset($item['competences'], $item['rooms']);

            $subject = Subject::updateOrCreate(['code' => $item['code']], $item);

            // Sync competences
            $compIds = Competence::whereIn('code', $compCodes)->pluck('id');
            $subject->competences()->sync($compIds);

            // Sync rooms
            $roomIds = Room::whereIn('code', $roomCodes)->pluck('id');
            $subject->rooms()->sync($roomIds);
        }
    }
}
