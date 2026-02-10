<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CategoryOperation;
use App\Constants\CategoryOperationConstants;

class CategoryOperationSeeder extends Seeder
{
    /**
     * Категории бизнес-процесса (связный список).
     *
     * Назначение судей → Командировка → Протокол и результат → Завершение
     */
    public function run(): void
    {
        $categories = [
            [
                'value' => CategoryOperationConstants::REFEREE_ASSIGNMENT,
                'title_ru' => 'Назначение судей',
                'title_kk' => 'Төрешілерді тағайындау',
                'title_en' => 'Referee Assignment',
                'is_first' => true,
                'is_last' => false,
            ],
            [
                'value' => CategoryOperationConstants::BUSINESS_TRIP,
                'title_ru' => 'Командировка',
                'title_kk' => 'Іссапар',
                'title_en' => 'Business Trip',
                'is_first' => false,
                'is_last' => false,
            ],
            [
                'value' => CategoryOperationConstants::MATCH_PROTOCOL,
                'title_ru' => 'Ожидание протокола и результата матча',
                'title_kk' => 'Матч хаттамасы мен нәтижесін күту',
                'title_en' => 'Match Protocol and Result',
                'is_first' => false,
                'is_last' => false,
            ],
            [
                'value' => CategoryOperationConstants::FINAL_RESULT,
                'title_ru' => 'Завершение',
                'title_kk' => 'Аяқтау',
                'title_en' => 'Completion',
                'is_first' => false,
                'is_last' => true,
            ],
        ];

        // Первый проход: создаём категории
        $created = [];
        foreach ($categories as $data) {
            $created[] = CategoryOperation::updateOrCreate(
                ['value' => $data['value']],
                $data
            );
        }

        // Второй проход: связный список
        for ($i = 0; $i < count($created); $i++) {
            $update = [];
            if ($i > 0) {
                $update['previous_id'] = $created[$i - 1]->id;
            }
            if ($i < count($created) - 1) {
                $update['next_id'] = $created[$i + 1]->id;
            }
            if (!empty($update)) {
                $created[$i]->update($update);
            }
        }
    }
}
