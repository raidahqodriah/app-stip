<?php

namespace App\Http\Controllers;

use App\Enums\CirculationStatus;
use App\Enums\ItemCondition;
use App\Models\Core\Employee;
use App\Models\Core\RequestLog;
use App\Models\Core\Student;
use App\Models\Library\Book;
use App\Models\Library\Circulation;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LibraryController extends Controller
{
    public function catalog(Request $request): View
    {
        $search = $request->query('search');
        $category = $request->query('category');

        $query = Book::query()->orderBy('title');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('author', 'like', "%{$search}%")
                    ->orWhere('book_code', 'like', "%{$search}%")
                    ->orWhere('isbn', 'like', "%{$search}%");
            });
        }

        if ($category) {
            $query->where('category', $category);
        }

        $books = $query->paginate(12)->withQueryString();
        $categories = Book::distinct()->pluck('category')->filter();

        $stats = [
            'total_titles' => Book::count(),
            'total_stock' => Book::sum('total_stock'),
            'available_stock' => Book::sum('available_stock'),
            'active_loans' => Circulation::where('status', CirculationStatus::Borrowed)->count(),
        ];

        return view('library.catalog', compact('books', 'categories', 'stats', 'search', 'category'));
    }

    public function circulation(Request $request): View
    {
        $tab = $request->query('tab', 'loans'); // 'loans' or 'returns'
        $search = $request->query('search');

        $activeLoans = Circulation::with(['book', 'borrower', 'loanOfficer'])
            ->where('status', CirculationStatus::Borrowed)
            ->latest('loan_date')
            ->get();

        $recentReturns = Circulation::with(['book', 'borrower', 'returnOfficer'])
            ->where('status', CirculationStatus::Returned)
            ->latest('return_date')
            ->limit(10)
            ->get();

        $books = Book::where('available_stock', '>', 0)->orderBy('title')->get();
        $students = Student::where('is_active', true)->orderBy('name')->get();
        $employees = Employee::where('is_active', true)->orderBy('name')->get();

        $stats = [
            'active_loans_count' => $activeLoans->count(),
            'overdue_count' => $activeLoans->filter(fn ($l) => $l->isOverdue())->count(),
            'total_fines' => Circulation::sum('fine_amount'),
            'returned_today' => Circulation::whereDate('return_date', Carbon::today())->count(),
        ];

        return view('library.circulation', compact(
            'tab',
            'activeLoans',
            'recentReturns',
            'books',
            'students',
            'employees',
            'stats',
            'search'
        ));
    }

    public function storeLoan(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'borrower_type' => 'required|in:student,employee',
            'borrower_id' => 'required|integer',
            'book_id' => 'required|exists:books,id',
            'notes' => 'nullable|string',
        ]);

        $book = Book::findOrFail($validated['book_id']);

        // Check stock
        if ($book->available_stock <= 0) {
            return back()->with('error', 'Stok buku ini sedang habis (0 eksemplar tersedia).');
        }

        // Check borrower active loan quota (Taruna max 3, Pegawai/Dosen max 5)
        $activeLoansCount = Circulation::where('borrower_type', $validated['borrower_type'])
            ->where('borrower_id', $validated['borrower_id'])
            ->where('status', CirculationStatus::Borrowed)
            ->count();

        $maxQuota = ($validated['borrower_type'] === 'student') ? 3 : 5;
        if ($activeLoansCount >= $maxQuota) {
            return back()->with('error', "Peminjam telah mencapai batas maksimal kuota peminjaman ({$maxQuota} buku sekaligus). Harap kembalikan buku sebelumnya.");
        }

        // Check overdue block rule (§6.3)
        $hasOverdue = Circulation::where('borrower_type', $validated['borrower_type'])
            ->where('borrower_id', $validated['borrower_id'])
            ->where('status', CirculationStatus::Borrowed)
            ->where('due_date', '<', Carbon::today())
            ->exists();

        if ($hasOverdue) {
            return back()->with('error', 'Peminjam memiliki tanggungan buku yang telah MELEWATI JATUH TEMPO! Peminjaman baru otomatis diblokir sampai buku dikembalikan.');
        }

        $admin = Employee::first();

        // Transaction code
        $code = 'TRX-LIB-'.date('Ym').'-'.str_pad((string) (Circulation::count() + 1), 3, '0', STR_PAD_LEFT);

        $circulation = Circulation::create([
            'transaction_code' => $code,
            'borrower_type' => $validated['borrower_type'],
            'borrower_id' => $validated['borrower_id'],
            'book_id' => $book->id,
            'loaned_by' => $admin->id,
            'loan_date' => Carbon::today(),
            'due_date' => Carbon::today()->addDays(7), // Default 7 calendar days (§6.3)
            'status' => CirculationStatus::Borrowed,
            'officer_note' => $validated['notes'],
        ]);

        // Decrement book available stock
        $book->decrement('available_stock');

        RequestLog::create([
            'loggable_type' => Circulation::class,
            'loggable_id' => $circulation->id,
            'actor_type' => 'employee',
            'actor_id' => $admin->id,
            'role' => 'officer',
            'action' => 'loan',
            'to_status' => CirculationStatus::Borrowed->value,
            'note' => "Peminjaman buku '{$book->title}' selama 7 hari kalender.",
        ]);

        return redirect()->route('library.circulation', ['tab' => 'loans'])
            ->with('success', "Peminjaman buku '{$book->title}' berhasil diproses! Jatuh tempo: ".$circulation->due_date->format('d/m/Y'));
    }

    public function storeReturn(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'circulation_id' => 'required|exists:circulations,id',
            'return_condition' => 'required|string',
            'fine_paid' => 'nullable|boolean',
            'officer_note' => 'nullable|string',
        ]);

        $circulation = Circulation::with('book')->findOrFail($validated['circulation_id']);

        if ($circulation->status === CirculationStatus::Returned) {
            return back()->with('error', 'Transaksi peminjaman ini sudah selesai dikembalikan sebelumnya.');
        }

        $admin = Employee::first();
        $returnDate = Carbon::today();

        // Calculate late days and fine (Rp 1.000 / day / book)
        $lateDays = 0;
        $fineAmount = 0;
        if ($returnDate->greaterThan($circulation->due_date)) {
            $lateDays = $returnDate->diffInDays($circulation->due_date);
            $fineAmount = $lateDays * 1000;
        }

        $circulation->update([
            'status' => CirculationStatus::Returned,
            'returned_by' => $admin->id,
            'return_date' => $returnDate,
            'return_condition' => ItemCondition::from($validated['return_condition']),
            'late_days' => $lateDays,
            'fine_amount' => $fineAmount,
            'fine_paid' => $request->has('fine_paid') || ($fineAmount === 0),
            'fine_paid_at' => ($request->has('fine_paid') || $fineAmount === 0) ? now() : null,
            'officer_note' => $validated['officer_note'],
        ]);

        // Restock available book
        $circulation->book->increment('available_stock');

        RequestLog::create([
            'loggable_type' => Circulation::class,
            'loggable_id' => $circulation->id,
            'actor_type' => 'employee',
            'actor_id' => $admin->id,
            'role' => 'officer',
            'action' => 'return',
            'from_status' => CirculationStatus::Borrowed->value,
            'to_status' => CirculationStatus::Returned->value,
            'note' => 'Buku dikembalikan dalam kondisi '.$circulation->return_condition->label().'. Denda: Rp '.number_format($fineAmount, 0, ',', '.'),
        ]);

        return redirect()->route('library.circulation', ['tab' => 'returns'])
            ->with('success', "Pengembalian buku '{$circulation->book->title}' berhasil diselesaikan!");
    }
}
