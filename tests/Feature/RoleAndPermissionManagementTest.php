<?php

namespace Tests\Feature;

use App\Models\Core\Employee;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class RoleAndPermissionManagementTest extends TestCase
{
    public function test_admin_can_access_roles_and_permissions_index_pages(): void
    {
        $admin = Employee::where('email', 'admin@stipjakarta.ac.id')->first();
        $this->assertNotNull($admin);

        $responseRoles = $this->actingAs($admin, 'employee')->get('/admin/master/roles');
        $responseRoles->assertSuccessful();

        $responseRolesCreate = $this->actingAs($admin, 'employee')->get('/admin/master/roles/create');
        $responseRolesCreate->assertSuccessful();

        $role = Role::first();
        $this->assertNotNull($role);
        $responseRolesEdit = $this->actingAs($admin, 'employee')->get("/admin/master/roles/{$role->id}/edit");
        $responseRolesEdit->assertSuccessful();

        $responsePermissions = $this->actingAs($admin, 'employee')->get('/admin/master/permissions');
        $responsePermissions->assertSuccessful();

        $permission = Permission::first();
        $this->assertNotNull($permission);
        $responsePermissionsView = $this->actingAs($admin, 'employee')->get("/admin/master/permissions/{$permission->id}");
        $responsePermissionsView->assertSuccessful();
    }

    public function test_non_admin_cannot_access_roles_and_permissions(): void
    {
        $nonAdminEmails = [
            'ketua@stipjakarta.ac.id',
            'ka.spp@stipjakarta.ac.id',
            'petugas.spp@stipjakarta.ac.id',
            'petugas.bmn@stipjakarta.ac.id',
            'petugas.perpus@stipjakarta.ac.id',
            'admin.teknika@stipjakarta.ac.id',
            'dosen.teknika@stipjakarta.ac.id',
        ];

        foreach ($nonAdminEmails as $email) {
            $user = Employee::where('email', $email)->first();
            $this->assertNotNull($user, "Pegawai {$email} tidak ditemukan");

            $responseRoles = $this->actingAs($user, 'employee')->get('/admin/master/roles');
            $responseRoles->assertForbidden();

            $responsePermissions = $this->actingAs($user, 'employee')->get('/admin/master/permissions');
            $responsePermissions->assertForbidden();
        }
    }

    public function test_system_roles_cannot_be_deleted(): void
    {
        $admin = Employee::where('email', 'admin@stipjakarta.ac.id')->first();
        $this->assertNotNull($admin);

        $systemRoles = ['admin', 'leader', 'officer', 'unit_admin', 'teacher'];

        foreach ($systemRoles as $roleName) {
            $role = Role::where('name', $roleName)->first();
            $this->assertNotNull($role, "Role {$roleName} tidak ditemukan");

            $this->assertFalse(
                $admin->can('delete', $role),
                "Role sistem {$roleName} seharusnya tidak boleh dihapus"
            );
        }
    }

    public function test_admin_can_create_and_delete_custom_role(): void
    {
        $admin = Employee::where('email', 'admin@stipjakarta.ac.id')->first();
        $this->assertNotNull($admin);

        $customRole = Role::firstOrCreate([
            'name' => 'auditor_eksternal',
            'guard_name' => 'employee',
        ]);

        $this->assertTrue(
            $admin->can('delete', $customRole),
            'Custom role seharusnya diizinkan dihapus oleh admin'
        );

        $customRole->delete();
    }

    public function test_permissions_count_and_system_dictionary(): void
    {
        $this->assertSame(60, Permission::where('guard_name', 'employee')->count());

        $coreCount = Permission::where('name', 'like', 'core.%')->count();
        $labCount = Permission::where('name', 'like', 'lab.%')->count();
        $bmnCount = Permission::where('name', 'like', 'bmn.%')->count();
        $residenceCount = Permission::where('name', 'like', 'residence.%')->count();
        $libraryCount = Permission::where('name', 'like', 'library.%')->count();

        $this->assertSame(16, $coreCount);
        $this->assertSame(15, $labCount);
        $this->assertSame(14, $bmnCount);
        $this->assertSame(8, $residenceCount);
        $this->assertSame(7, $libraryCount);
    }
}
