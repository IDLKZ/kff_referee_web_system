<?php

namespace Database\Seeders;

use App\Constants\SeasonConstants;
use App\Models\Season;
use Illuminate\Database\Seeder;

class SeasonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $seasons = [
            [
                'title_ru' => 'Сезон 2024-2025',
                'title_kk' => '2024-2025 маусымы',
                'title_en' => 'Season 2024-2025',
                'value' => SeasonConstants::SEASON_2024_2025,
                'start_at' => '2024-07-01',
                'end_at' => '2025-06-30',
            ],
            [
                'title_ru' => 'Сезон 2025-2026',
                'title_kk' => '2025-2026 маусымы',
                'title_en' => 'Season 2025-2026',
                'value' => SeasonConstants::SEASON_2025_2026,
                'start_at' => '2025-07-01',
                'end_at' => '2026-06-30',
            ],
        ];

        foreach ($seasons as $season) {
            Season::updateOrCreate(
                ['value' => $season['value']],
                $season
            );
        }
    }
}
