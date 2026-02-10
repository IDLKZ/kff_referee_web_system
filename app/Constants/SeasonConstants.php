<?php

namespace App\Constants;

class SeasonConstants
{
    // Сезоны
    const SEASON_2024_2025 = '2024-2025';
    const SEASON_2025_2026 = '2025-2026';

    /**
     * Получить все сезоны
     *
     * @return array
     */
    public static function all(): array
    {
        return [
            self::SEASON_2024_2025,
            self::SEASON_2025_2026,
        ];
    }
}
