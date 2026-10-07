<?php

namespace Tests\Feature;

use App\Filament\Admin\Widgets\Bmn\BmnConditionDistributionChart;
use App\Filament\Admin\Widgets\Bmn\BmnPendingDecreeDocumentsTable;
use App\Filament\Admin\Widgets\Bmn\BmnPerUnitChart;
use App\Filament\Admin\Widgets\Bmn\DamagedBmnItemsTable;
use App\Filament\Admin\Widgets\Bmn\PendingBmnSubmissionsTable;
use App\Filament\Admin\Widgets\Lab\ImoCompetenceCoverageChart;
use App\Filament\Admin\Widgets\Lab\LabUsageByCategoryChart;
use App\Filament\Admin\Widgets\Lab\LabUtilizationTrendChart;
use App\Filament\Admin\Widgets\Lab\LowStockMaterialsTable;
use App\Filament\Admin\Widgets\Lab\PendingLabBookingsTable;
use App\Filament\Admin\Widgets\Lab\TodayLabScheduleTable;
use App\Filament\Admin\Widgets\Lab\TopSubjectsLabUsageChart;
use App\Filament\Admin\Widgets\Library\BooksByCategoryChart;
use App\Filament\Admin\Widgets\Library\LibraryCirculationTrendChart;
use App\Filament\Admin\Widgets\Library\LowStockBooksTable;
use App\Filament\Admin\Widgets\Library\OverdueCirculationsTable;
use App\Filament\Admin\Widgets\Library\TodayLibraryCirculationsTable;
use App\Filament\Admin\Widgets\Library\TopBorrowedBooksChart;
use App\Filament\Admin\Widgets\Residence\ExpiringResidencePermitsTable;
use App\Filament\Admin\Widgets\Residence\PendingResidencePermitsTable;
use App\Filament\Admin\Widgets\Residence\ResidenceOccupancyChart;
use App\Filament\Admin\Widgets\Residence\ResidencePermitStatusChart;
use App\Filament\Student\Widgets\StudentActiveLoansTable;
use App\Filament\Student\Widgets\StudentMonthlyStudyActivityChart;
use App\Filament\Student\Widgets\StudentUpcomingLabSessionsTable;
use App\Models\Core\Employee;
use App\Models\Core\Student;
use Livewire\Livewire;
use Tests\TestCase;

class DashboardWidgetsTest extends TestCase
{
    public function test_admin_dashboard_renders_all_admin_widgets_successfully(): void
    {
        $admin = Employee::where('email', 'admin@stipjakarta.ac.id')->first();
        if (! $admin) {
            $this->markTestSkipped('Admin employee not found in database');
        }

        $response = $this->actingAs($admin, 'employee')->get('/admin');
        $response->assertStatus(200);

        $adminWidgets = [
            TodayLabScheduleTable::class,
            PendingLabBookingsTable::class,
            LowStockMaterialsTable::class,
            LabUtilizationTrendChart::class,
            LabUsageByCategoryChart::class,
            TopSubjectsLabUsageChart::class,
            ImoCompetenceCoverageChart::class,
            PendingBmnSubmissionsTable::class,
            DamagedBmnItemsTable::class,
            BmnPendingDecreeDocumentsTable::class,
            BmnConditionDistributionChart::class,
            BmnPerUnitChart::class,
            PendingResidencePermitsTable::class,
            ExpiringResidencePermitsTable::class,
            ResidenceOccupancyChart::class,
            ResidencePermitStatusChart::class,
            OverdueCirculationsTable::class,
            TodayLibraryCirculationsTable::class,
            LowStockBooksTable::class,
            LibraryCirculationTrendChart::class,
            TopBorrowedBooksChart::class,
            BooksByCategoryChart::class,
        ];

        foreach ($adminWidgets as $widgetClass) {
            Livewire::actingAs($admin, 'employee')
                ->test($widgetClass)
                ->assertSuccessful();
        }
    }

    public function test_student_dashboard_renders_all_student_widgets_successfully(): void
    {
        $student = Student::where('email', 'taruna.nautika1@student.stipjakarta.ac.id')->first();
        if (! $student) {
            $this->markTestSkipped('Student not found in database');
        }

        $response = $this->actingAs($student, 'student')->get('/student');
        $response->assertStatus(200);

        $studentWidgets = [
            StudentUpcomingLabSessionsTable::class,
            StudentActiveLoansTable::class,
            StudentMonthlyStudyActivityChart::class,
        ];

        foreach ($studentWidgets as $widgetClass) {
            Livewire::actingAs($student, 'student')
                ->test($widgetClass)
                ->assertSuccessful();
        }
    }
}
