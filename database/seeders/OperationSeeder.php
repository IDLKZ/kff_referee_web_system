<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Operation;
use App\Models\CategoryOperation;
use App\Constants\CategoryOperationConstants;
use App\Constants\OperationConstants;

class OperationSeeder extends Seeder
{
    /**
     * Операции бизнес-процесса (сокращённая версия).
     *
     * 1. Назначение: создан → назначение → утверждение бригады (↩ переназначение)
     * 2. Командировка: выбор транспорта → оформление
     * 3. Протокол: ожидание → проверка (↩ переоформление)
     * 4. Завершение: успешно завершено
     */
    public function run(): void
    {
        $refCat = CategoryOperation::where('value', CategoryOperationConstants::REFEREE_ASSIGNMENT)->firstOrFail();
        $tripCat = CategoryOperation::where('value', CategoryOperationConstants::BUSINESS_TRIP)->firstOrFail();
        $protCat = CategoryOperation::where('value', CategoryOperationConstants::MATCH_PROTOCOL)->firstOrFail();
        $finalCat = CategoryOperation::where('value', CategoryOperationConstants::FINAL_RESULT)->firstOrFail();

        $ops = [];

        // ===== 1. Назначение судей =====

        $ops['ref_1'] = Operation::updateOrCreate(
            ['value' => OperationConstants::MATCH_CREATED_WAITING_REFEREES],
            [
                'category_id' => $refCat->id,
                'title_ru' => 'Матч создан, ожидает назначения судей',
                'title_kk' => 'Матч жасалды, төрешілерді тағайындауды күтеді',
                'title_en' => 'Match created, waiting for referee assignment',
                'is_first' => true, 'is_last' => false,
                'can_reject' => false, 'is_active' => true, 'result' => 0,
            ]
        );

        $ops['ref_2'] = Operation::updateOrCreate(
            ['value' => OperationConstants::REFEREE_ASSIGNMENT],
            [
                'category_id' => $refCat->id,
                'title_ru' => 'Назначение судей',
                'title_kk' => 'Төрешілерді тағайындау',
                'title_en' => 'Referee assignment',
                'is_first' => false, 'is_last' => false,
                'can_reject' => false, 'is_active' => true, 'result' => 0,
            ]
        );

        $ops['ref_3'] = Operation::updateOrCreate(
            ['value' => OperationConstants::REFEREE_TEAM_APPROVAL],
            [
                'category_id' => $refCat->id,
                'title_ru' => 'Утверждение финальной бригады',
                'title_kk' => 'Соңғы бригаданы бекіту',
                'title_en' => 'Final referee team approval',
                'is_first' => false, 'is_last' => false,
                'can_reject' => true, 'is_active' => true, 'result' => 0,
            ]
        );

        $ops['ref_4'] = Operation::updateOrCreate(
            ['value' => OperationConstants::REFEREE_REASSIGNMENT],
            [
                'category_id' => $refCat->id,
                'title_ru' => 'Переназначение судей',
                'title_kk' => 'Төрешілерді қайта тағайындау',
                'title_en' => 'Referee reassignment',
                'is_first' => false, 'is_last' => false,
                'can_reject' => false, 'is_active' => true, 'result' => 0,
            ]
        );

        // ===== 2. Командировка =====

        $ops['trip_1'] = Operation::updateOrCreate(
            ['value' => OperationConstants::SELECT_TRANSPORT_DEPARTURE],
            [
                'category_id' => $tripCat->id,
                'title_ru' => 'Выбор точки отправления и транспорта',
                'title_kk' => 'Жөнелту нүктесін және көлікті таңдау',
                'title_en' => 'Select departure point and transport',
                'is_first' => true, 'is_last' => false,
                'can_reject' => false, 'is_active' => true, 'result' => 0,
            ]
        );

        $ops['trip_2'] = Operation::updateOrCreate(
            ['value' => OperationConstants::TRIP_PROCESSING],
            [
                'category_id' => $tripCat->id,
                'title_ru' => 'Оформление командировки',
                'title_kk' => 'Іссапарды ресімдеу',
                'title_en' => 'Trip processing',
                'is_first' => false, 'is_last' => false,
                'can_reject' => false, 'is_active' => true, 'result' => 0,
            ]
        );

        // ===== 3. Ожидание протокола и результата матча =====

        $ops['prot_1'] = Operation::updateOrCreate(
            ['value' => OperationConstants::WAITING_FOR_PROTOCOL],
            [
                'category_id' => $protCat->id,
                'title_ru' => 'Ожидание протокола и результата матча',
                'title_kk' => 'Матч хаттамасы мен нәтижесін күту',
                'title_en' => 'Waiting for protocol and match result',
                'is_first' => true, 'is_last' => false,
                'can_reject' => false, 'is_active' => true, 'result' => 0,
            ]
        );

        $ops['prot_2'] = Operation::updateOrCreate(
            ['value' => OperationConstants::PROTOCOL_REVIEW],
            [
                'category_id' => $protCat->id,
                'title_ru' => 'Проверка протокола и результата',
                'title_kk' => 'Хаттама мен нәтижені тексеру',
                'title_en' => 'Protocol and result review',
                'is_first' => false, 'is_last' => false,
                'can_reject' => true, 'is_active' => true, 'result' => 0,
            ]
        );

        $ops['prot_3'] = Operation::updateOrCreate(
            ['value' => OperationConstants::PROTOCOL_REPROCESSING],
            [
                'category_id' => $protCat->id,
                'title_ru' => 'Переоформление протокола и результата матча',
                'title_kk' => 'Хаттама мен матч нәтижесін қайта ресімдеу',
                'title_en' => 'Protocol and result reprocessing',
                'is_first' => false, 'is_last' => false,
                'can_reject' => false, 'is_active' => true, 'result' => 0,
            ]
        );

        // ===== 4. Завершение =====

        $ops['final'] = Operation::updateOrCreate(
            ['value' => OperationConstants::SUCCESSFULLY_COMPLETED],
            [
                'category_id' => $finalCat->id,
                'title_ru' => 'Успешно завершено',
                'title_kk' => 'Сәтті аяқталды',
                'title_en' => 'Successfully completed',
                'is_first' => true, 'is_last' => true,
                'can_reject' => false, 'is_active' => true, 'result' => 1,
            ]
        );

        // ===== Связи =====

        // 1. Назначение судей: ref_1 → ref_2 → ref_3 (↩ ref_4 → ref_2)
        $ops['ref_1']->update(['next_id' => $ops['ref_2']->id]);
        $ops['ref_2']->update([
            'previous_id' => $ops['ref_1']->id,
            'next_id' => $ops['ref_3']->id,
        ]);
        $ops['ref_3']->update([
            'previous_id' => $ops['ref_2']->id,
            'next_id' => $ops['trip_1']->id,
            'on_reject_id' => $ops['ref_4']->id,
        ]);
        $ops['ref_4']->update(['next_id' => $ops['ref_2']->id]);

        // 2. Командировка: trip_1 → trip_2 → prot_1
        $ops['trip_1']->update(['next_id' => $ops['trip_2']->id]);
        $ops['trip_2']->update([
            'previous_id' => $ops['trip_1']->id,
            'next_id' => $ops['prot_1']->id,
        ]);

        // 3. Протокол: prot_1 → prot_2 (↩ prot_3 → prot_1) → final
        $ops['prot_1']->update(['next_id' => $ops['prot_2']->id]);
        $ops['prot_2']->update([
            'previous_id' => $ops['prot_1']->id,
            'next_id' => $ops['final']->id,
            'on_reject_id' => $ops['prot_3']->id,
        ]);
        $ops['prot_3']->update(['next_id' => $ops['prot_1']->id]);
    }
}
