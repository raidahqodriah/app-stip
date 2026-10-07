<?php

namespace App\Http\Controllers;

use App\Models\Bmn\BmnReturn;
use App\Models\Bmn\BmnSubmission;
use App\Models\Bmn\ResidencePermit;
use App\Models\Core\DocumentTemplate;
use App\Models\Core\Employee;
use App\Models\Core\GeneratedDocument;
use App\Models\Lab\Booking;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DocumentController extends Controller
{
    public function index(Request $request): View
    {
        $type = $request->query('type');

        $query = GeneratedDocument::with(['template', 'generator'])->latest();

        if ($type) {
            $query->whereHas('template', fn ($q) => $q->where('type', $type));
        }

        $documents = $query->paginate(10)->withQueryString();
        $templates = DocumentTemplate::all();

        return view('documents.index', compact('documents', 'templates', 'type'));
    }

    public function bookingConfirmation(string $number): View
    {
        $booking = Booking::with([
            'room',
            'subject',
            'competences.imoModelCourse',
            'responsibleLecturer',
            'requester',
            'materials.material',
            'unit',
        ])->where('booking_number', $number)
            ->orWhereHas('generatedDocument', fn ($q) => $q->where('document_number', $number))
            ->firstOrFail();

        $document = GeneratedDocument::firstOrCreate(
            [
                'documentable_type' => Booking::class,
                'documentable_id' => $booking->id,
            ],
            [
                'document_template_id' => DocumentTemplate::where('type', 'booking_confirmation')->first()?->id ?? 1,
                'document_number' => 'DOC-LAB-'.date('Ym').'-'.str_pad((string) $booking->id, 3, '0', STR_PAD_LEFT),
                'generator_type' => 'employee',
                'generator_id' => Employee::first()?->id ?? 1,
                'print_count' => 1,
            ]
        );

        $document->increment('print_count');

        $headSpp = Employee::where('position', 'like', '%Kepala Unit%')->first()
            ?? Employee::where('position', 'like', '%Pimpinan%')->first()
            ?? Employee::first();

        return view('documents.booking-confirmation', compact('booking', 'document', 'headSpp'));
    }

    public function bmnDecree(string $number): View
    {
        $submission = BmnSubmission::with(['unit', 'room', 'submittedBy', 'verifiedBy'])
            ->where('bmn_code', $number)
            ->orWhere('register_number', $number)
            ->orWhereHas('bmnItem', fn ($q) => $q->where('bmn_code', $number))
            ->orWhere('id', is_numeric($number) ? $number : 0)
            ->firstOrFail();

        $document = GeneratedDocument::firstOrCreate(
            [
                'documentable_type' => BmnSubmission::class,
                'documentable_id' => $submission->id,
            ],
            [
                'document_template_id' => DocumentTemplate::where('type', 'bmn_decree')->first()?->id ?? 2,
                'document_number' => 'DOC-BMN-'.date('Ym').'-'.str_pad((string) $submission->id, 3, '0', STR_PAD_LEFT),
                'generator_type' => 'employee',
                'generator_id' => Employee::first()?->id ?? 1,
                'print_count' => 1,
            ]
        );

        $document->increment('print_count');

        $bmnOfficer = $submission->verifiedBy ?? Employee::first();

        return view('documents.bmn-decree', compact('submission', 'document', 'bmnOfficer'));
    }

    public function bmnReturnReceipt(string $number): View
    {
        $return = BmnReturn::with(['bmnItem', 'fromRoom', 'destinationRoom', 'requestedBy', 'verifiedBy'])
            ->where('id', is_numeric($number) ? $number : 0)
            ->orWhereHas('bmnItem', fn ($q) => $q->where('bmn_code', $number))
            ->firstOrFail();

        $document = GeneratedDocument::firstOrCreate(
            [
                'documentable_type' => BmnReturn::class,
                'documentable_id' => $return->id,
            ],
            [
                'document_template_id' => DocumentTemplate::where('type', 'bmn_return_receipt')->first()?->id ?? 3,
                'document_number' => 'DOC-RET-'.date('Ym').'-'.str_pad((string) $return->id, 3, '0', STR_PAD_LEFT),
                'generator_type' => 'employee',
                'generator_id' => Employee::first()?->id ?? 1,
                'print_count' => 1,
            ]
        );

        $document->increment('print_count');

        $officer = $return->verifiedBy ?? Employee::first();

        return view('documents.bmn-return-receipt', compact('return', 'document', 'officer'));
    }

    public function residencePermit(string $number): View
    {
        $permit = ResidencePermit::with([
            'employee',
            'unit',
            'officialResidence',
            'submittedBy',
            'verifiedBy',
            'approvedBy',
        ])->where('permit_number', $number)
            ->orWhere('id', is_numeric($number) ? $number : 0)
            ->firstOrFail();

        $document = GeneratedDocument::firstOrCreate(
            [
                'documentable_type' => ResidencePermit::class,
                'documentable_id' => $permit->id,
            ],
            [
                'document_template_id' => DocumentTemplate::where('type', 'residence_permit')->first()?->id ?? 4,
                'document_number' => 'DOC-SIP-'.date('Ym').'-'.str_pad((string) $permit->id, 3, '0', STR_PAD_LEFT),
                'generator_type' => 'employee',
                'generator_id' => Employee::first()?->id ?? 1,
                'print_count' => 1,
            ]
        );

        $document->increment('print_count');

        $ketuaStip = Employee::where('position', 'like', '%Ketua%')->first() ?? Employee::first();

        return view('documents.residence-permit', compact('permit', 'document', 'ketuaStip'));
    }
}
