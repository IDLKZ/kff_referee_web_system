<?php

namespace Database\Seeders;

use App\Constants\RoleConstants;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolePermissionSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Назначить все разрешения роли Администратор.
     */
    public function run(): void
    {
        $adminRoleId = DB::table('roles')
            ->where('value', RoleConstants::ADMINISTRATOR)
            ->value('id');

        $permissionIds = DB::table('permissions')->pluck('id');

        $rows = $permissionIds->map(fn ($permissionId) => [
            'role_id' => $adminRoleId,
            'permission_id' => $permissionId,
        ])->all();

        DB::table('role_permissions')->insert($rows);
    }
}
