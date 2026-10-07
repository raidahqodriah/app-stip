<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $guard = 'employee';

        $permissions = [
            // Core & Administrasi
            'core.employee.view',
            'core.employee.create',
            'core.employee.update',
            'core.employee.delete',
            'core.student.view',
            'core.student.create',
            'core.student.update',
            'core.student.delete',
            'core.role.manage',
            'core.unit.view',
            'core.unit.manage',
            'core.room.view',
            'core.room.manage',
            'core.template.manage',
            'core.setting.manage',
            'core.audit.view',

            // Lab & Simulator SPP
            'lab.schedule.view',
            'lab.booking.view-all',
            'lab.booking.view-own',
            'lab.booking.create',
            'lab.booking.create-on-behalf',
            'lab.booking.update',
            'lab.booking.cancel',
            'lab.booking.verify',
            'lab.booking.approve',
            'lab.booking.realize',
            'lab.curriculum.manage',
            'lab.material.manage',
            'lab.blackout.manage',
            'lab.document.print',
            'lab.report.view',

            // BMN
            'bmn.item.view-all',
            'bmn.item.view-unit',
            'bmn.item.manage',
            'bmn.submission.view-all',
            'bmn.submission.view-unit',
            'bmn.submission.create',
            'bmn.submission.process',
            'bmn.return.view-all',
            'bmn.return.view-unit',
            'bmn.return.create',
            'bmn.return.process',
            'bmn.movement.view',
            'bmn.document.print',
            'bmn.report.view',

            // Rumah Dinas
            'residence.master.manage',
            'residence.permit.view-all',
            'residence.permit.view-unit',
            'residence.permit.create',
            'residence.permit.verify',
            'residence.permit.approve',
            'residence.document.print',
            'residence.report.view',

            // Perpustakaan
            'library.book.view',
            'library.book.manage',
            'library.circulation.view-all',
            'library.circulation.view-own',
            'library.circulation.process',
            'library.fine.confirm',
            'library.report.view',
        ];

        foreach ($permissions as $permissionName) {
            Permission::firstOrCreate([
                'name' => $permissionName,
                'guard_name' => $guard,
            ]);
        }

        // 1. Role: admin
        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => $guard]);
        $adminRole->syncPermissions([
            // Core
            'core.employee.view',
            'core.employee.create',
            'core.employee.update',
            'core.employee.delete',
            'core.student.view',
            'core.student.create',
            'core.student.update',
            'core.student.delete',
            'core.role.manage',
            'core.unit.view',
            'core.unit.manage',
            'core.room.view',
            'core.room.manage',
            'core.template.manage',
            'core.setting.manage',
            'core.audit.view',

            // Lab
            'lab.schedule.view',
            'lab.booking.view-all',
            'lab.booking.view-own',
            'lab.curriculum.manage',
            'lab.material.manage',
            'lab.blackout.manage',
            'lab.document.print',
            'lab.report.view',

            // BMN
            'bmn.item.view-all',
            'bmn.item.manage',
            'bmn.submission.view-all',
            'bmn.return.view-all',
            'bmn.movement.view',
            'bmn.document.print',
            'bmn.report.view',

            // Rumah Dinas
            'residence.master.manage',
            'residence.permit.view-all',
            'residence.document.print',
            'residence.report.view',

            // Perpustakaan
            'library.book.view',
            'library.book.manage',
            'library.circulation.view-all',
            'library.report.view',
        ]);

        // 2. Role: leader
        $leaderRole = Role::firstOrCreate(['name' => 'leader', 'guard_name' => $guard]);
        $leaderRole->syncPermissions([
            'core.employee.view',
            'core.student.view',
            'core.unit.view',
            'core.room.view',
            'core.audit.view',

            'lab.schedule.view',
            'lab.booking.view-all',
            'lab.document.print',
            'lab.report.view',

            'bmn.item.view-all',
            'bmn.submission.view-all',
            'bmn.return.view-all',
            'bmn.movement.view',
            'bmn.report.view',

            'residence.permit.view-all',
            'residence.report.view',

            'library.book.view',
            'library.circulation.view-all',
            'library.report.view',
        ]);

        // 3. Role: officer
        $officerRole = Role::firstOrCreate(['name' => 'officer', 'guard_name' => $guard]);
        $officerRole->syncPermissions([
            'core.employee.view',
            'core.student.view',
            'core.unit.view',
            'core.room.view',
            'lab.schedule.view',
            'library.book.view',
        ]);

        // 4. Role: unit_admin
        $unitAdminRole = Role::firstOrCreate(['name' => 'unit_admin', 'guard_name' => $guard]);
        $unitAdminRole->syncPermissions([
            'core.employee.view',
            'core.student.view',
            'core.unit.view',
            'core.room.view',

            'lab.schedule.view',

            'bmn.item.view-unit',
            'bmn.submission.view-unit',
            'bmn.submission.create',
            'bmn.return.view-unit',
            'bmn.return.create',
            'bmn.movement.view',
            'bmn.document.print',
            'bmn.report.view',

            'residence.permit.view-unit',
            'residence.permit.create',
            'residence.document.print',
            'residence.report.view',

            'library.book.view',
        ]);

        // 5. Role: teacher
        $teacherRole = Role::firstOrCreate(['name' => 'teacher', 'guard_name' => $guard]);
        $teacherRole->syncPermissions([
            'core.employee.view',
            'core.student.view',
            'core.unit.view',
            'core.room.view',

            'lab.schedule.view',
            'lab.booking.view-own',
            'lab.booking.create',
            'lab.booking.update',
            'lab.booking.cancel',
            'lab.booking.realize',
            'lab.document.print',
            'lab.report.view',

            'library.book.view',
            'library.circulation.view-own',
        ]);
    }
}
