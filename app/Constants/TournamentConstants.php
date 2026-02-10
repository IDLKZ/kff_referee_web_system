<?php

namespace App\Constants;

class TournamentConstants
{
    // Лиги Казахстана
    const PREMIER_LEAGUE = 'premier_league';
    const FIRST_LEAGUE = 'first_league';
    const SECOND_LEAGUE = 'second_league';
    const WOMENS_LEAGUE = 'womens_league';

    /**
     * Получить все лиги
     *
     * @return array
     */
    public static function all(): array
    {
        return [
            self::PREMIER_LEAGUE,
            self::FIRST_LEAGUE,
            self::SECOND_LEAGUE,
            self::WOMENS_LEAGUE,
        ];
    }
}
