<?php

namespace App\Constants;

class CountryConstants
{
    const KAZAKHSTAN = 'kazakhstan';

    /**
     * Получить все страны
     *
     * @return array
     */
    public static function all(): array
    {
        return [
            self::KAZAKHSTAN,
        ];
    }
}
