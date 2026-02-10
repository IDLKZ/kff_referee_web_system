<?php

namespace App\Constants;

class ClubTypeConstants
{
    // Типы команд клубов
    const MAIN_MEN = 'main_men';
    const MAIN_WOMEN = 'main_women';
    const U21_MEN = 'u21_men';
    const U19_MEN = 'u19_men';
    const U19_WOMEN = 'u19_women';
    const U17_MEN = 'u17_men';
    const U16_MEN = 'u16_men';
    const U15_MEN = 'u15_men';

    /**
     * Получить все типы команд
     *
     * @return array
     */
    public static function all(): array
    {
        return [
            self::MAIN_MEN,
            self::MAIN_WOMEN,
            self::U21_MEN,
            self::U19_MEN,
            self::U19_WOMEN,
            self::U17_MEN,
            self::U16_MEN,
            self::U15_MEN,
        ];
    }
}
