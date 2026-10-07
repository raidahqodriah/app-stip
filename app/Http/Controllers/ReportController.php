<?php

namespace App\Http\Controllers;

use App\Enums\CirculationStatus;
use App\Enums\ItemCondition;
use App\Enums\RequestStatus;
use App\Models\Bmn\BmnItem;
use App\Models\Bmn\BmnReturn;
use App\Models\Bmn\BmnSubmission;
use App\Models\Bmn\OfficialResidence;
use App\Models\Bmn\ResidencePermit;
use App\Models\Lab\Booking;
use App\Models\Lab\Competence;
use App\Models\Library\Book;
use App\Models\Library\Circulation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function dashboard(Request $request): View
    {
        $period = $request->query('period', 'month'); // 'day', 'month', 'year'

        // Lab SPP Metrics (FR, JLH, DRS, Utilisasi)
        $completedBookings = Booking::with('realization')
            ->where('status', RequestStatus::Completed)
            ->get();

        $frCount = $completedBookings->count(); // Sesi per hari/bulan
        $jlhTotal = $completedBookings->sum(fn ($b) => $b->realization?->actual_participant_count ?? $b->participant_count);

        $totalMinutes = 0;
        foreach ($completedBookings as $b) {
            if ($b->realization?->actual_start_at && $b->realization?->actual_end_at) {
                $totalMinutes += $b->realization->actual_start_at->diffInMinutes($b->realization->actual_end_at);
            } else {
                $totalMinutes += $b->start_at->diffInMinutes($b->end_at);
            }
        }
        $drsHours = round($totalMinutes / 60, 1);

        // Operating hours assumption: 8 labs * 8.5 hours/day * 22 days/month = 1,496 hours
        $operationalCapacity = 8 * 8.5 * 22;
        $utilizationRate = min(100, round(($drsHours / max(1, $operationalCapacity)) * 100, 1));

        $totalCompetences = Competence::count();
        $coveredCompetences = Booking::with('competences')
            ->get()
            ->pluck('competences')
            ->flatten()
            ->unique('id')
            ->count();
        $imoCoverageRate = round(($coveredCompetences / max(1, $totalCompetences)) * 100, 1);

        // BMN Metrics
        $totalBmn = BmnItem::count();
        $activeBmn = BmnItem::where('status', 'active')->count();
        $damagedBmn = BmnItem::whereIn('condition', [ItemCondition::MinorDamage, ItemCondition::MajorDamage])->count();
        $pendingSubmissions = BmnSubmission::where('status', RequestStatus::Submitted)->count();
        $pendingReturns = BmnReturn::where('status', RequestStatus::Submitted)->count();

        // Official Residence Metrics
        $totalHouses = OfficialResidence::count();
        $occupiedHouses = ResidencePermit::where('status', RequestStatus::Approved)
            ->where('occupancy_start', '<=', Carbon::today())
            ->where('occupancy_end', '>=', Carbon::today())
            ->distinct('official_residence_id')
            ->count();
        $occupancyRate = round(($occupiedHouses / max(1, $totalHouses)) * 100, 1);
        $pendingPermits = ResidencePermit::where('status', RequestStatus::Submitted)->count();
        $verifiedPermits = ResidencePermit::where('status', RequestStatus::Verified)->count();

        // Library Metrics
        $totalTitles = Book::count();
        $totalStock = Book::sum('total_stock');
        $availableStock = Book::sum('available_stock');
        $activeLoans = Circulation::where('status', CirculationStatus::Borrowed)->count();
        $overdueLoans = Circulation::where('status', CirculationStatus::Borrowed)
            ->where('due_date', '<', Carbon::today())
            ->count();
        $totalFineCollected = Circulation::where('fine_paid', true)->sum('fine_amount');
        $totalFinePending = Circulation::where('fine_paid', false)->sum('fine_amount');

        // Recent Module Activity
        $recentBookings = Booking::with(['room', 'subject'])->latest()->limit(5)->get();
        $recentSubmissions = BmnSubmission::with(['unit', 'room'])->latest()->limit(5)->get();
        $recentCirculations = Circulation::with(['book', 'borrower'])->latest()->limit(5)->get();

        return view('reports.dashboard', compact(
            'period',
            'frCount',
            'jlhTotal',
            'drsHours',
            'utilizationRate',
            'imoCoverageRate',
            'totalBmn',
            'activeBmn',
            'damagedBmn',
            'pendingSubmissions',
            'pendingReturns',
            'totalHouses',
            'occupiedHouses',
            'occupancyRate',
            'pendingPermits',
            'verifiedPermits',
            'totalTitles',
            'totalStock',
            'availableStock',
            'activeLoans',
            'overdueLoans',
            'totalFineCollected',
            'totalFinePending',
            'recentBookings',
            'recentSubmissions',
            'recentCirculations'
        ));
    }
}
