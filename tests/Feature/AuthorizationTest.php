<?php

namespace Tests\Feature;

use App\Enums\RequestStatus;
use App\Models\Bmn\BmnSubmission;
use App\Models\Bmn\ResidencePermit;
use App\Models\Core\Employee;
use App\Models\Core\Unit;
use App\Models\Lab\Booking;
use App\Models\Library\Circulation;
use Tests\TestCase;

class AuthorizationTest extends TestCase
{
    public function test_admin_cannot_approve_or_verify_operational_transactions(): void
    {
        $admin = Employee::where('email', 'admin@stipjakarta.ac.id')->first();
        $this->assertNotNull($admin);

        // Pastikan role admin terpasang
        $this->assertTrue($admin->hasRole('admin'));

        // Admin dilarang memiliki izin verifikasi atau persetujuan transaksi operasional (Segregation of Duties)
        $this->assertFalse($admin->can('lab.booking.verify'));
        $this->assertFalse($admin->can('lab.booking.approve'));
        $this->assertFalse($admin->can('bmn.submission.process'));
        $this->assertFalse($admin->can('residence.permit.approve'));

        // Policy checks
        $booking = new Booking(['status' => RequestStatus::Submitted]);
        $this->assertFalse($admin->can('verify', $booking));
        $this->assertFalse($admin->can('approve', $booking));
    }

    public function test_petugas_spp_can_verify_submitted_booking_but_cannot_approve(): void
    {
        $petugasSpp = Employee::where('email', 'petugas.spp@stipjakarta.ac.id')->first();
        $this->assertNotNull($petugasSpp);

        $this->assertTrue($petugasSpp->hasRole('officer'));
        $this->assertTrue($petugasSpp->can('lab.booking.verify'));
        $this->assertFalse($petugasSpp->can('lab.booking.approve'));

        $submittedBooking = new Booking(['status' => RequestStatus::Submitted]);
        $verifiedBooking = new Booking(['status' => RequestStatus::Verified]);

        $this->assertTrue($petugasSpp->can('verify', $submittedBooking));
        $this->assertFalse($petugasSpp->can('approve', $verifiedBooking));
    }

    public function test_kepala_spp_can_approve_verified_booking_but_not_unverified(): void
    {
        $kaSpp = Employee::where('email', 'ka.spp@stipjakarta.ac.id')->first();
        $this->assertNotNull($kaSpp);

        $this->assertTrue($kaSpp->hasRole('leader'));
        $this->assertTrue($kaSpp->can('lab.booking.approve'));

        $submittedBooking = new Booking(['status' => RequestStatus::Submitted]);
        $verifiedBooking = new Booking(['status' => RequestStatus::Verified]);

        // Kepala Unit SPP dilarang menyetujui jika belum lolos verifikasi (PRD §3.2 & §11)
        $this->assertFalse($kaSpp->can('approve', $submittedBooking));
        $this->assertTrue($kaSpp->can('approve', $verifiedBooking));
    }

    public function test_ketua_stip_can_approve_verified_residence_permit(): void
    {
        $ketua = Employee::where('email', 'ketua@stipjakarta.ac.id')->first();
        $this->assertNotNull($ketua);

        $this->assertTrue($ketua->hasRole('leader'));
        $this->assertTrue($ketua->can('residence.permit.approve'));

        $submittedPermit = new ResidencePermit(['status' => RequestStatus::Submitted]);
        $verifiedPermit = new ResidencePermit(['status' => RequestStatus::Verified]);

        // Ketua STIP hanya menyetujui yang sudah diverifikasi oleh Petugas Rumah Tangga (PRD §5)
        $this->assertFalse($ketua->can('approve', $submittedPermit));
        $this->assertTrue($ketua->can('approve', $verifiedPermit));
    }

    public function test_unit_admin_can_only_view_own_unit_bmn_submissions(): void
    {
        $adminTeknika = Employee::where('email', 'admin.teknika@stipjakarta.ac.id')->first();
        $this->assertNotNull($adminTeknika);

        $prodiTeknika = Unit::where('code', 'PRODI-TEK')->first();
        $prodiNautika = Unit::where('code', 'PRODI-NAU')->first();

        $this->assertNotNull($prodiTeknika);
        $this->assertNotNull($prodiNautika);

        $submissionTeknika = new BmnSubmission([
            'unit_id' => $prodiTeknika->id,
            'status' => RequestStatus::Submitted,
        ]);

        $submissionNautika = new BmnSubmission([
            'unit_id' => $prodiNautika->id,
            'status' => RequestStatus::Submitted,
        ]);

        // Admin Teknika boleh melihat unitnya sendiri
        $this->assertTrue($adminTeknika->can('view', $submissionTeknika));

        // Admin Teknika dilarang melihat unit Nautika (Isolasi multi-tenant)
        $this->assertFalse($adminTeknika->can('view', $submissionNautika));
    }

    public function test_circulation_returned_is_locked_from_updates(): void
    {
        $petugasPerpus = Employee::where('email', 'petugas.perpus@stipjakarta.ac.id')->first();
        $this->assertNotNull($petugasPerpus);

        $borrowedCirculation = new Circulation(['status' => 'borrowed']);
        $returnedCirculation = new Circulation(['status' => 'returned']);

        // Transaksi aktif masih bisa diubah/diproses
        $this->assertTrue($petugasPerpus->can('update', $borrowedCirculation));

        // Transaksi returned terkunci permanen sesuai PRD §6.3
        $this->assertFalse($petugasPerpus->can('update', $returnedCirculation));
        $this->assertFalse($petugasPerpus->can('delete', $returnedCirculation));
    }
}
