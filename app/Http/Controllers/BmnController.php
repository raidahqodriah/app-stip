<?php

namespace App\Http\Controllers;

use App\Enums\ItemCondition;
use App\Enums\RequestStatus;
use App\Models\Bmn\BmnItem;
use App\Models\Bmn\BmnItemMovement;
use App\Models\Bmn\BmnReturn;
use App\Models\Bmn\BmnSubmission;
use App\Models\Core\DocumentTemplate;
use App\Models\Core\Employee;
use App\Models\Core\GeneratedDocument;
use App\Models\Core\RequestLog;
use App\Models\Core\Room;
use App\Models\Core\Unit;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BmnController extends Controller
{
    public function inventory(Request $request): View
    {
        $unitFilter = $request->query('unit_id');
        $roomFilter = $request->query('room_id');
        $conditionFilter = $request->query('condition');
        $statusFilter = $request->query('status');
        $search = $request->query('search');

        $query = BmnItem::with(['unit', 'room', 'responsibleEmployee', 'movements.fromRoom', 'movements.toRoom'])
            ->orderBy('item_name');

        if ($unitFilter) {
            $query->where('unit_id', $unitFilter);
        }
        if ($roomFilter) {
            $query->where('room_id', $roomFilter);
        }
        if ($conditionFilter) {
            $query->where('condition', $conditionFilter);
        }
        if ($statusFilter) {
            $query->where('status', $statusFilter);
        }
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('item_name', 'like', "%{$search}%")
                    ->orWhere('bmn_code', 'like', "%{$search}%")
                    ->orWhere('brand', 'like', "%{$search}%")
                    ->orWhere('serial_number', 'like', "%{$search}%");
            });
        }

        $items = $query->paginate(12)->withQueryString();
        $units = Unit::orderBy('name')->get();
        $rooms = Room::orderBy('name')->get();

        $stats = [
            'total_items' => BmnItem::count(),
            'active_items' => BmnItem::where('status', 'active')->count(),
            'damaged_items' => BmnItem::whereIn('condition', [ItemCondition::MinorDamage, ItemCondition::MajorDamage])->count(),
            'pending_submissions' => BmnSubmission::where('status', RequestStatus::Submitted)->count(),
            'pending_returns' => BmnReturn::where('status', RequestStatus::Submitted)->count(),
        ];

        return view('bmn.inventory', compact(
            'items',
            'units',
            'rooms',
            'stats',
            'unitFilter',
            'roomFilter',
            'conditionFilter',
            'statusFilter',
            'search'
        ));
    }

    public function submissionForm(): View
    {
        $units = Unit::orderBy('name')->get();
        $rooms = Room::orderBy('name')->get();
        $employees = Employee::orderBy('name')->get();
        $recentSubmissions = BmnSubmission::with(['unit', 'room', 'submittedBy'])
            ->latest()
            ->limit(5)
            ->get();

        return view('bmn.submission', compact('units', 'rooms', 'employees', 'recentSubmissions'));
    }

    public function storeSubmission(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'unit_id' => 'required|exists:units,id',
            'room_id' => 'required|exists:rooms,id',
            'item_name' => 'required|string|max:255',
            'bmn_code' => 'nullable|string|max:100',
            'register_number' => 'nullable|string|max:100',
            'quantity' => 'required|integer|min:1',
            'brand' => 'nullable|string|max:100',
            'model' => 'nullable|string|max:100',
            'serial_number' => 'nullable|string|max:100',
            'acquisition_date' => 'required|date',
            'acquisition_source' => 'required|string|max:150',
            'condition' => 'required|string',
            'responsible_name' => 'required|string|max:150',
            'requires_decree' => 'nullable|boolean',
        ]);

        $admin = Employee::first();

        $submission = BmnSubmission::create([
            'unit_id' => $validated['unit_id'],
            'room_id' => $validated['room_id'],
            'submitted_by' => $admin->id,
            'item_name' => $validated['item_name'],
            'bmn_code' => $validated['bmn_code'] ?? ('BMN-'.strtoupper(Str::random(6))),
            'register_number' => $validated['register_number'] ?? ('NUP-'.rand(1000, 9999)),
            'quantity' => $validated['quantity'],
            'brand' => $validated['brand'],
            'model' => $validated['model'],
            'serial_number' => $validated['serial_number'],
            'acquisition_date' => $validated['acquisition_date'],
            'acquisition_source' => $validated['acquisition_source'],
            'condition' => ItemCondition::from($validated['condition']),
            'responsible_name' => $validated['responsible_name'],
            'requires_decree' => $request->has('requires_decree'),
            'status' => RequestStatus::Submitted,
        ]);

        RequestLog::create([
            'loggable_type' => BmnSubmission::class,
            'loggable_id' => $submission->id,
            'actor_type' => 'employee',
            'actor_id' => $admin->id,
            'role' => 'unit_admin',
            'action' => 'submit',
            'from_status' => 'draft',
            'to_status' => RequestStatus::Submitted->value,
            'note' => "Pengajuan BMN baru '{$submission->item_name}' oleh Unit Kerja.",
        ]);

        return redirect()->route('bmn.submission')
            ->with('success', "Pengajuan BMN baru '{$submission->item_name}' berhasil diajukan dan menunggu verifikasi Petugas BMN!");
    }

    public function returnForm(): View
    {
        $items = BmnItem::where('status', 'active')->with(['room', 'unit'])->orderBy('item_name')->get();
        $rooms = Room::orderBy('name')->get();
        $recentReturns = BmnReturn::with(['bmnItem', 'fromRoom', 'destinationRoom', 'requestedBy'])
            ->latest()
            ->limit(5)
            ->get();

        return view('bmn.return', compact('items', 'rooms', 'recentReturns'));
    }

    public function storeReturn(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'bmn_item_id' => 'required|exists:bmn_items,id',
            'return_date' => 'required|date',
            'reason' => 'required|string',
            'condition' => 'required|string',
            'destination_room_id' => 'nullable|exists:rooms,id',
            'damage_note' => 'nullable|string',
        ]);

        // CRITICAL PRD REQUIREMENT: If condition is minor/major damage or lost, damage_note is MANDATORY!
        $conditionEnum = ItemCondition::from($validated['condition']);
        if (in_array($conditionEnum, [ItemCondition::MinorDamage, ItemCondition::MajorDamage, ItemCondition::Lost])) {
            if (empty($validated['damage_note'])) {
                return back()->withInput()->with('error', 'Untuk barang berkondisi RUSAK atau HILANG, wajib menyertakan keterangan kerusakan atau kronologi secara detail!');
            }
        }

        $admin = Employee::first();
        $bmnItem = BmnItem::findOrFail($validated['bmn_item_id']);

        $bmnReturn = BmnReturn::create([
            'bmn_item_id' => $bmnItem->id,
            'requested_by' => $admin->id,
            'from_room_id' => $bmnItem->room_id,
            'destination_room_id' => $validated['destination_room_id'],
            'return_date' => $validated['return_date'],
            'reason' => $validated['reason'],
            'condition' => $conditionEnum,
            'damage_note' => $validated['damage_note'],
            'status' => RequestStatus::Submitted,
        ]);

        RequestLog::create([
            'loggable_type' => BmnReturn::class,
            'loggable_id' => $bmnReturn->id,
            'actor_type' => 'employee',
            'actor_id' => $admin->id,
            'role' => 'unit_admin',
            'action' => 'submit_return',
            'from_status' => 'draft',
            'to_status' => RequestStatus::Submitted->value,
            'note' => "Pengajuan pengembalian BMN '{$bmnItem->item_name}' dengan kondisi {$conditionEnum->label()}.",
        ]);

        return redirect()->route('bmn.return')
            ->with('success', "Pengajuan pengembalian '{$bmnItem->item_name}' berhasil dikirim ke Petugas BMN!");
    }

    public function processReturn(Request $request, int $id): RedirectResponse
    {
        $bmnReturn = BmnReturn::with('bmnItem')->findOrFail($id);
        $admin = Employee::first();

        $bmnReturn->status = RequestStatus::Completed;
        $bmnReturn->verified_by = $admin->id;
        $bmnReturn->save();

        // Update BmnItem
        $item = $bmnReturn->bmnItem;
        $prevRoomId = $item->room_id;
        $item->condition = $bmnReturn->condition;
        $item->status = 'returned';
        if ($bmnReturn->destination_room_id) {
            $item->room_id = $bmnReturn->destination_room_id;
        }
        $item->save();

        // Movement record
        BmnItemMovement::create([
            'bmn_item_id' => $item->id,
            'from_room_id' => $prevRoomId,
            'to_room_id' => $bmnReturn->destination_room_id,
            'source' => 'return',
            'reason' => 'Pengembalian barang: '.$bmnReturn->reason,
        ]);

        // Auto generate return receipt document
        $template = DocumentTemplate::where('type', 'bmn_return_receipt')->first();
        if ($template) {
            GeneratedDocument::updateOrCreate(
                [
                    'documentable_type' => BmnReturn::class,
                    'documentable_id' => $bmnReturn->id,
                ],
                [
                    'document_template_id' => $template->id,
                    'document_number' => 'DOC-RET-'.date('Ym').'-'.str_pad((string) $bmnReturn->id, 3, '0', STR_PAD_LEFT),
                    'generator_type' => 'employee',
                    'generator_id' => $admin->id,
                    'print_count' => 1,
                ]
            );
        }

        return back()->with('success', 'Pengembalian BMN telah diverifikasi dan Bukti Pengembalian siap dicetak!');
    }
}
