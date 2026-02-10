<?php

namespace Database\Seeders;

use App\Constants\JudgeTypeConstants;
use App\Models\JudgeType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JudgeTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $judgeTypes = [
            [
                'title_ru' => 'Главный арбитр',
                'title_kk' => 'Бас әдейші',
                'title_en' => 'Referee',
                'value' => JudgeTypeConstants::REFEREE,
                'is_active' => true,
            ],
            [
                'title_ru' => 'Помощник арбитра',
                'title_kk' => 'Әдейші көмекшісі',
                'title_en' => 'Assistant Referee',
                'value' => JudgeTypeConstants::ASSISTANT_REFEREE,
                'is_active' => true,
            ],
            [
                'title_ru' => 'Четвёртый арбитр',
                'title_kk' => 'Төртінші әдейші',
                'title_en' => 'Fourth Official',
                'value' => JudgeTypeConstants::FOURTH_OFFICIAL,
                'is_active' => true,
            ],
            [
                'title_ru' => 'Видео-помощник арбитра (VAR)',
                'title_kk' => 'Бейне әдейші көмекшісі (VAR)',
                'title_en' => 'Video Assistant Referee (VAR)',
                'value' => JudgeTypeConstants::VAR,
                'is_active' => true,
            ],
            [
                'title_ru' => 'Ассистент VAR (AVAR)',
                'title_kk' => 'VAR көмекшісі (AVAR)',
                'title_en' => 'Assistant VAR (AVAR)',
                'value' => JudgeTypeConstants::AVAR,
                'is_active' => true,
            ],
            [
                'title_ru' => 'Офсайдный VAR',
                'title_kk' => 'Офсайд VAR',
                'title_en' => 'Offside VAR',
                'value' => JudgeTypeConstants::OFFSIDE_VAR,
                'is_active' => true,
            ],
            [
                'title_ru' => 'Оператор повторов',
                'title_kk' => 'Қайталау операторы',
                'title_en' => 'Replay Operator',
                'value' => JudgeTypeConstants::REPLAY_OPERATOR,
                'is_active' => true,
            ],
            [
                'title_ru' => 'Резервный помощник арбитра',
                'title_kk' => 'Резервтік әдейші көмекшісі',
                'title_en' => 'Reserve Assistant Referee',
                'value' => JudgeTypeConstants::RESERVE_ASSISTANT_REFEREE,
                'is_active' => true,
            ],
        ];

        foreach ($judgeTypes as $judgeType) {
            JudgeType::updateOrCreate(
                ['value' => $judgeType['value']],
                $judgeType
            );
        }
    }
}
