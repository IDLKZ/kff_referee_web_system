<?php

namespace Database\Seeders;

use App\Constants\PermissionConstants;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $tables = PermissionConstants::tables();
        $actions = PermissionConstants::actions();
        $actionTranslations = PermissionConstants::actionTranslations();
        $now = now();

        $permissions = [];

        foreach ($tables as $table => $tableNames) {
            foreach ($actions as $action) {
                $actionNames = $actionTranslations[$action];

                $permissions[] = [
                    'title_ru' => "{$actionNames['ru']}: {$tableNames['ru']}",
                    'title_kk' => "{$actionNames['kk']}: {$tableNames['kk']}",
                    'title_en' => "{$actionNames['en']}: {$tableNames['en']}",
                    'value' => "{$table}.{$action}",
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
        }

        DB::table('permissions')->insert($permissions);
    }
}
