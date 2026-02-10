<?php

namespace App\Constants;

class JudgeTypeConstants
{
    // Типы судей
    const REFEREE = 'referee';
    const ASSISTANT_REFEREE = 'assistant_referee';
    const FOURTH_OFFICIAL = 'fourth_official';
    const VAR = 'var';
    const AVAR = 'avar';
    const OFFSIDE_VAR = 'offside_var';
    const REPLAY_OPERATOR = 'replay_operator';
    const RESERVE_ASSISTANT_REFEREE = 'reserve_assistant_referee';

    /**
     * Получить все типы судей
     *
     * @return array
     */
    public static function all(): array
    {
        return [
            self::REFEREE,
            self::ASSISTANT_REFEREE,
            self::FOURTH_OFFICIAL,
            self::VAR,
            self::AVAR,
            self::OFFSIDE_VAR,
            self::REPLAY_OPERATOR,
            self::RESERVE_ASSISTANT_REFEREE,
        ];
    }
}
