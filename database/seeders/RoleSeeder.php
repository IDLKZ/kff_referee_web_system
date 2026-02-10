<?php

namespace Database\Seeders;

use App\Constants\RoleConstants;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'title_ru' => 'Администратор',
                'title_kk' => 'Әкімші',
                'title_en' => 'Administrator',
                'value' => RoleConstants::ADMINISTRATOR,
                'group' => RoleConstants::ADMINISTRATOR_GROUP,
                'is_active' => true,
                'can_register' => false,
            ],
            [
                'title_ru' => 'Сотрудник департамента футбольного судейства',
                'title_kk' => 'Футбол төрешілігі департаментінің қызметкері',
                'title_en' => 'Refereeing Department Employee',
                'value' => RoleConstants::REFEREEING_DEPARTMENT_EMPLOYEE,
                'group' => RoleConstants::KFF_PFLK_GROUP,
                'is_active' => true,
                'can_register' => false,
            ],
            [
                'title_ru' => 'Руководитель департамента футбольного судейства',
                'title_kk' => 'Футбол төрешілігі департаментінің басшысы',
                'title_en' => 'Refereeing Department Head',
                'value' => RoleConstants::REFEREEING_DEPARTMENT_HEAD,
                'group' => RoleConstants::KFF_PFLK_GROUP,
                'is_active' => true,
                'can_register' => false,
            ],
            [
                'title_ru' => 'Футбольный судья',
                'title_kk' => 'Футбол төрешісі',
                'title_en' => 'Soccer Referee',
                'value' => RoleConstants::SOCCER_REFEREE,
                'group' => RoleConstants::REFEREE_GROUP,
                'is_active' => true,
                'can_register' => true,
            ],
            [
                'title_ru' => 'Логист департамента футбольного судейства',
                'title_kk' => 'Футбол төрешілігі департаментінің логисті',
                'title_en' => 'Refereeing Department Logistician',
                'value' => RoleConstants::REFEREEING_DEPARTMENT_LOGISTICIAN,
                'group' => RoleConstants::KFF_PFLK_GROUP,
                'is_active' => true,
                'can_register' => false,
            ],

        ];

        foreach ($roles as $roleData) {
            Role::updateOrCreate(
                ['value' => $roleData['value']],
                $roleData
            );
        }
    }
}
