<?php

namespace App\Http\Controllers;

use App\Enums\RequestStatus;
use App\Models\Bmn\OfficialResidence;
use App\Models\Bmn\ResidencePermit;
use App\Models\Core\DocumentTemplate;
use App\Models\Core\Employee;
use App\Models\Core\GeneratedDocument;
use App\Models\Core\RequestLog;
use App\Models\Core\Unit;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ResidenceController extends Controller
{
    public function submissionForm(): View
    {
        $residences = OfficialResidence::where('is_active', true)->orderBy('house_number')->get();
        $employees = Employee::where('is_active', true)->orderBy('name')->get();
        $units = Unit::where('is_active', true)->orderBy('name')->get();
        $recentPermits = ResidencePermit::with(['employee', 'officialResidence', 'unit', 'approvedBy'])
            ->latest()
            ->limit(5)
            ->get();

        return view('residence.submission', compact('residences', 'employees', 'units', 'recentPermits'));
    }

    public function storeSubmission(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'unit_id' => 'required|exists:units,id',
            'official_residence_id' => 'required|exists:official_residences,id',
            'occupancy_start' => 'required|date',
            'occupancy_end' => 'required|date|after:occupancy_start',
            'occupancy_notes' => 'nullable|string',
        ]);

        $admin = Employee::first();

        $permit = ResidencePermit::create([
            'permit_number' => null, // Generated upon approval by Ketua STIP
            'employee_id' => $validated['employee_id'],
            'unit_id' => $validated['unit_id'],
            'official_residence_id' => $validated['official_residence_id'],
            'submitted_by' => $admin->id,
            'occupancy_start' => $validated['occupancy_start'],
            'occupancy_end' => $validated['occupancy_end'],
            'occupancy_notes' => $validated['occupancy_notes'],
            'status' => RequestStatus::Submitted,
        ]);

        RequestLog::create([
            'loggable_type' => ResidencePermit::class,
            'loggable_id' => $permit->id,
            'actor_type' => 'employee',
            'actor_id' => $admin->id,
            'role' => 'unit_admin',
            'action' => 'submit',
            'from_status' => 'draft',
            'to_status' => RequestStatus::Submitted->value,
            'note' => 'Permohonan izin penghuni rumah dinas diajukan oleh Unit Pengusul.',
        ]);

        return redirect()->route('residence.approval')
            ->with('success', 'Permohonan Surat Izin Penghuni Rumah Dinas berhasil diajukan! Menunggu pemeriksaan Petugas Rumah Tangga.');
    }

    public function approvalWorkflow(Request $request): View
    {
        $statusFilter = $request->query('status');

        $query = ResidencePermit::with([
            'employee',
            'unit',
            'officialResidence',
            'submittedBy',
            'verifiedBy',
            'approvedBy',
            'generatedDocument',
            'requestLogs' => fn ($q) => $q->latest(),
        ])->latest();

        if ($statusFilter) {
            $query->where('status', $statusFilter);
        }

        $permits = $query->paginate(10)->withQueryString();

        $stats = [
            'total' => ResidencePermit::count(),
            'submitted' => ResidencePermit::where('status', RequestStatus::Submitted)->count(),
            'verified' => ResidencePermit::where('status', RequestStatus::Verified)->count(),
            'approved' => ResidencePermit::where('status', RequestStatus::Approved)->count(),
        ];

        return view('residence.approval', compact('permits', 'stats', 'statusFilter'));
    }

    public function processStep(Request $request, int $id): RedirectResponse
    {
        $permit = ResidencePermit::findOrFail($id);
        $action = $request->input('action');
        $note = $request->input('note', 'Proses verifikasi izin rumah dinas.');
        $admin = Employee::first();

        $prevStatus = $permit->status->value;

        if ($action === 'verify' && $permit->status === RequestStatus::Submitted) {
            $permit->status = RequestStatus::Verified;
            $permit->verified_by = $admin->id;
            $role = 'officer';
        } elseif ($action === 'approve' && $permit->status === RequestStatus::Verified) {
            // Ketua STIP approves
            $permit->status = RequestStatus::Approved;
            $permit->approved_by = $admin->id;
            $role = 'leader';

            // Generate official SIP number
            $permitNumber = 'SIP/STIP/'.date('Y/m').'/'.str_pad((string) $permit->id, 3, '0', STR_PAD_LEFT);
            $permit->permit_number = $permitNumber;

            // Generate printable document
            $template = DocumentTemplate::where('type', 'residence_permit')->first();
            if ($template) {
                GeneratedDocument::updateOrCreate(
                    [
                        'documentable_type' => ResidencePermit::class,
                        'documentable_id' => $permit->id,
                    ],
                    [
                        'document_template_id' => $template->id,
                        'document_number' => 'DOC-SIP-'.date('Ym').'-'.str_pad((string) $permit->id, 3, '0', STR_PAD_LEFT),
                        'generator_type' => 'employee',
                        'generator_id' => $admin->id,
                        'print_count' => 1,
                    ]
                );
            }
        } elseif ($action === 'request_revision') {
            $permit->status = RequestStatus::RevisionRequested;
            $role = 'officer';
        } elseif ($action === 'reject') {
            $permit->status = RequestStatus::Rejected;
            $role = 'leader';
        } else {
            return back()->with('error', 'Aksi tidak valid untuk alur saat ini.');
        }

        $permit->save();

        RequestLog::create([
            'loggable_type' => ResidencePermit::class,
            'loggable_id' => $permit->id,
            'actor_type' => 'employee',
            'actor_id' => $admin->id,
            'role' => $role,
            'action' => $action,
            'from_status' => $prevStatus,
            'to_status' => $permit->status->value,
            'note' => $note,
        ]);

        return back()->with('success', "Permohonan Rumah Dinas berhasil diperbarui statusnya menjadi '{$permit->status->label()}'!");
    }
}
