<?php

namespace Database\Seeders;

use App\Constants\ClubTypeConstants;
use App\Models\ClubType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ClubTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $clubTypes = [
            [
                'title_ru' => 'Основная взрослая мужская команда',
                'title_kk' => 'Негізгі ересек ерлер командасы',
                'title_en' => 'Main Men\'s Team',
                'value' => ClubTypeConstants::MAIN_MEN,
                'level' => 100,
                'is_active' => true,
            ],
            [
                'title_ru' => 'Основная взрослая женская команда',
                'title_kk' => 'Негізгі ересек әйелдер командасы',
                'title_en' => 'Main Women\'s Team',
                'value' => ClubTypeConstants::MAIN_WOMEN,
                'level' => 100,
                'is_active' => true,
            ],
            [
                'title_ru' => 'U-21 - Молодежная мужская команда до 21 года',
                'title_kk' => 'U-21 - 21 жасқа дейінгі жастар ерлер командасы',
                'title_en' => 'U-21 Men\'s Youth Team',
                'value' => ClubTypeConstants::U21_MEN,
                'level' => 80,
                'is_active' => true,
            ],
            [
                'title_ru' => 'U-19 - Молодежная мужская команда до 19 лет',
                'title_kk' => 'U-19 - 19 жасқа дейінгі жастар ерлер командасы',
                'title_en' => 'U-19 Men\'s Youth Team',
                'value' => ClubTypeConstants::U19_MEN,
                'level' => 70,
                'is_active' => true,
            ],
            [
                'title_ru' => 'WU-19 - Молодежная женская команда до 19 лет',
                'title_kk' => 'WU-19 - 19 жасқа дейінгі жастар әйелдер командасы',
                'title_en' => 'WU-19 Women\'s Youth Team',
                'value' => ClubTypeConstants::U19_WOMEN,
                'level' => 70,
                'is_active' => true,
            ],
            [
                'title_ru' => 'U-17 - Молодежная мужская команда до 17 лет',
                'title_kk' => 'U-17 - 17 жасқа дейінгі жастар ерлер командасы',
                'title_en' => 'U-17 Men\'s Youth Team',
                'value' => ClubTypeConstants::U17_MEN,
                'level' => 60,
                'is_active' => true,
            ],
            [
                'title_ru' => 'U-16 - Молодежная мужская до 16 лет',
                'title_kk' => 'U-16 - 16 жасқа дейінгі жастар ерлер командасы',
                'title_en' => 'U-16 Men\'s Youth Team',
                'value' => ClubTypeConstants::U16_MEN,
                'level' => 50,
                'is_active' => true,
            ],
            [
                'title_ru' => 'U-15 - Молодежная мужская до 15 лет',
                'title_kk' => 'U-15 - 15 жасқа дейінгі жастар ерлер командасы',
                'title_en' => 'U-15 Men\'s Youth Team',
                'value' => ClubTypeConstants::U15_MEN,
                'level' => 40,
                'is_active' => true,
            ],
        ];

        foreach ($clubTypes as $clubType) {
            ClubType::updateOrCreate(
                ['value' => $clubType['value']],
                $clubType
            );
        }
    }
}
