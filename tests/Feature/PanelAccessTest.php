<?php

namespace Tests\Feature;

use App\Models\Core\Employee;
use App\Models\Core\Student;
use Tests\TestCase;

class PanelAccessTest extends TestCase
{
    public function test_login_pages_are_accessible(): void
    {
        $adminLogin = $this->get('/admin/login');
        $adminLogin->assertStatus(200);

        $studentLogin = $this->get('/student/login');
        $studentLogin->assertStatus(200);
    }

    public function test_unauthenticated_users_are_redirected_to_respective_login(): void
    {
        $this->get('/admin')->assertRedirect('/admin/login');
        $this->get('/student')->assertRedirect('/student/login');
    }

    public function test_employee_can_access_admin_panel_and_not_student_panel(): void
    {
        $employee = Employee::where('email', 'admin@stipjakarta.ac.id')->first();
        if (! $employee) {
            $this->markTestSkipped('Employee seeder not found');
        }

        $this->actingAs($employee, 'employee')
            ->get('/admin')
            ->assertStatus(200);

        $this->actingAs($employee, 'employee')
            ->get('/student')
            ->assertRedirect('/student/login');
    }

    public function test_student_can_access_student_panel_and_not_admin_panel(): void
    {
        $student = Student::where('email', 'taruna.teknika1@student.stipjakarta.ac.id')->first();
        if (! $student) {
            $this->markTestSkipped('Student seeder not found');
        }

        $this->actingAs($student, 'student')
            ->get('/student')
            ->assertStatus(200);

        $this->actingAs($student, 'student')
            ->get('/admin')
            ->assertRedirect('/admin/login');
    }
}
