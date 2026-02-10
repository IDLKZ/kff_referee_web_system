<?php

namespace Database\Seeders;

use App\Models\Club;
use App\Models\City;
use App\Models\Stadium;
use App\Models\ClubType;
use App\Constants\ClubTypeConstants;
use Illuminate\Database\Seeder;

class ClubSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $clubs = [
            [
                'short_name_ru' => 'Кайрат',
                'short_name_kk' => 'Қайрат',
                'short_name_en' => 'Kairat',
                'full_name_ru' => 'ФК Кайрат',
                'full_name_kk' => 'Қайрат ФК',
                'full_name_en' => 'FC Kairat',
                'city_value' => 'almaty',
                'stadium_name_ru' => 'Центральный стадион',
            ],
            [
                'short_name_ru' => 'Астана',
                'short_name_kk' => 'Астана',
                'short_name_en' => 'Astana',
                'full_name_ru' => 'ФК Астана',
                'full_name_kk' => 'Астана ФК',
                'full_name_en' => 'FC Astana',
                'city_value' => 'astana',
                'stadium_name_ru' => 'Астана Арена',
            ],
            [
                'short_name_ru' => 'Тобол',
                'short_name_kk' => 'Тобыл',
                'short_name_en' => 'Tobol',
                'full_name_ru' => 'ФК Тобол',
                'full_name_kk' => 'Тобыл ФК',
                'full_name_en' => 'FC Tobol',
                'city_value' => 'kostanay',
                'stadium_name_ru' => 'Тобыл Арена',
            ],
            [
                'short_name_ru' => 'Актобе',
                'short_name_kk' => 'Ақтөбе',
                'short_name_en' => 'Aktobe',
                'full_name_ru' => 'ФК Актобе',
                'full_name_kk' => 'Ақтөбе ФК',
                'full_name_en' => 'FC Aktobe',
                'city_value' => 'aktobe',
                'stadium_name_ru' => 'Центральный им. Кобланды батыра',
            ],
            [
                'short_name_ru' => 'Елимай',
                'short_name_kk' => 'Елімай',
                'short_name_en' => 'Elimay',
                'full_name_ru' => 'ФК Елимай',
                'full_name_kk' => 'Елімай ФК',
                'full_name_en' => 'FC Elimay',
                'city_value' => 'semey',
                'stadium_name_ru' => 'Спартак',
            ],
            [
                'short_name_ru' => 'Женис',
                'short_name_kk' => 'Жеңіс',
                'short_name_en' => 'Zhenis',
                'full_name_ru' => 'ФК Женис',
                'full_name_kk' => 'Жеңіс ФК',
                'full_name_en' => 'FC Zhenis',
                'city_value' => 'astana',
                'stadium_name_ru' => 'Астана Арена',
            ],
            [
                'short_name_ru' => 'Окжетпес',
                'short_name_kk' => 'Оқжетпес',
                'short_name_en' => 'Okzhetpes',
                'full_name_ru' => 'ФК Окжетпес',
                'full_name_kk' => 'Оқжетпес ФК',
                'full_name_en' => 'FC Okzhetpes',
                'city_value' => 'kokshetau',
                'stadium_name_ru' => 'Окжетпес',
            ],
            [
                'short_name_ru' => 'Ордабасы',
                'short_name_kk' => 'Ордабасы',
                'short_name_en' => 'Ordabasy',
                'full_name_ru' => 'ФК Ордабасы',
                'full_name_kk' => 'Ордабасы ФК',
                'full_name_en' => 'FC Ordabasy',
                'city_value' => 'shymkent',
                'stadium_name_ru' => 'Стадион им. Хаджимукана Мунайтпасова',
            ],
            [
                'short_name_ru' => 'Кызылжар',
                'short_name_kk' => 'Қызылжар',
                'short_name_en' => 'Kyzylzhar',
                'full_name_ru' => 'ФК Кызылжар',
                'full_name_kk' => 'Қызылжар ФК',
                'full_name_en' => 'FC Kyzylzhar',
                'city_value' => 'petropavlovsk',
                'stadium_name_ru' => 'Карасай',
            ],
            [
                'short_name_ru' => 'Улытау',
                'short_name_kk' => 'Ұлытау',
                'short_name_en' => 'Ulytau',
                'full_name_ru' => 'ФК Улытау',
                'full_name_kk' => 'Ұлытау ФК',
                'full_name_en' => 'FC Ulytau',
                'city_value' => 'karaganda',
                'stadium_name_ru' => 'Шахтёр',
            ],
            [
                'short_name_ru' => 'Жетысу',
                'short_name_kk' => 'Жетісу',
                'short_name_en' => 'Zhetysu',
                'full_name_ru' => 'ФК Жетысу',
                'full_name_kk' => 'Жетісу ФК',
                'full_name_en' => 'FC Zhetysu',
                'city_value' => 'taldykorgan',
                'stadium_name_ru' => 'Жетысу',
            ],
            [
                'short_name_ru' => 'Кайсар',
                'short_name_kk' => 'Қайсар',
                'short_name_en' => 'Kaisar',
                'full_name_ru' => 'ФК Кайсар',
                'full_name_kk' => 'Қайсар ФК',
                'full_name_en' => 'FC Kaisar',
                'city_value' => 'kyzylorda',
                'stadium_name_ru' => 'Стадион им. Гани Муратбаева',
            ],
            [
                'short_name_ru' => 'Атырау',
                'short_name_kk' => 'Атырау',
                'short_name_en' => 'Atyrau',
                'full_name_ru' => 'ФК Атырау',
                'full_name_kk' => 'Атырау ФК',
                'full_name_en' => 'FC Atyrau',
                'city_value' => 'atyrau',
                'stadium_name_ru' => 'Мунайши',
            ],
            [
                'short_name_ru' => 'Туран',
                'short_name_kk' => 'Тұран',
                'short_name_en' => 'Turan',
                'full_name_ru' => 'ФК Туран',
                'full_name_kk' => 'Тұран ФК',
                'full_name_en' => 'FC Turan',
                'city_value' => 'turkestan',
                'stadium_name_ru' => 'Туркестан-Арена',
            ],
        ];

        $mainMenType = ClubType::where('value', ClubTypeConstants::MAIN_MEN)->first();

        foreach ($clubs as $clubData) {
            $city = City::where('value', $clubData['city_value'])->first();
            if (!$city) continue;

            $club = Club::updateOrCreate(
                ['short_name_ru' => $clubData['short_name_ru'], 'city_id' => $city->id],
                [
                    'short_name_kk' => $clubData['short_name_kk'],
                    'short_name_en' => $clubData['short_name_en'],
                    'full_name_ru' => $clubData['full_name_ru'],
                    'full_name_kk' => $clubData['full_name_kk'],
                    'full_name_en' => $clubData['full_name_en'],
                    'type_id' => $mainMenType?->id,
                    'is_active' => true,
                ]
            );

            // Привязка стадиона к клубу
            $stadium = Stadium::where('title_ru', $clubData['stadium_name_ru'])
                ->where('city_id', $city->id)
                ->first();
            if ($stadium) {
                $club->stadiums()->syncWithoutDetaching([$stadium->id]);
            }
        }
    }
}
