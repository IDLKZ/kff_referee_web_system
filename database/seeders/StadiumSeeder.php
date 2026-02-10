<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\Stadium;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StadiumSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $stadiums = [
            [
                'title_ru' => 'Центральный стадион',
                'title_kk' => 'Орталық стадион',
                'title_en' => 'Central Stadium',
                'city_value' => 'almaty',
                'capacity' => 23804,
            ],
            [
                'title_ru' => 'Астана Арена',
                'title_kk' => 'Астана Арена',
                'title_en' => 'Astana Arena',
                'city_value' => 'astana',
                'capacity' => 30000,
            ],
            [
                'title_ru' => 'Тобыл Арена',
                'title_kk' => 'Тобыл Арена',
                'title_en' => 'Tobyl Arena',
                'city_value' => 'kostanay',
                'capacity' => 10500,
            ],
            [
                'title_ru' => 'Центральный им. Кобланды батыра',
                'title_kk' => 'Қобланды батыр атындағы Орталық',
                'title_en' => 'Central Koblandy Batyr Stadium',
                'city_value' => 'aktobe',
                'capacity' => 12800,
            ],
            [
                'title_ru' => 'Спартак',
                'title_kk' => 'Спартак',
                'title_en' => 'Spartak',
                'city_value' => 'semey',
                'capacity' => 8000,
            ],
            [
                'title_ru' => 'Окжетпес',
                'title_kk' => 'Оқжетпес',
                'title_en' => 'Okzhetpes',
                'city_value' => 'kokshetau',
                'capacity' => 4500,
            ],
            [
                'title_ru' => 'Стадион им. Хаджимукана Мунайтпасова',
                'title_kk' => 'Хаджымұқан Мұнайтпасов атындағы стадион',
                'title_en' => 'Kazhymukan Munaitpasov Stadium',
                'city_value' => 'shymkent',
                'capacity' => 20000,
            ],
            [
                'title_ru' => 'Карасай',
                'title_kk' => 'Қарасай',
                'title_en' => 'Karasay',
                'city_value' => 'petropavlovsk',
                'capacity' => 11000,
            ],
            [
                'title_ru' => 'Шахтёр',
                'title_kk' => 'Шахтёр',
                'title_en' => 'Shakhter',
                'city_value' => 'karaganda',
                'capacity' => 19000,
            ],
            [
                'title_ru' => 'Жетысу',
                'title_kk' => 'Жетісу',
                'title_en' => 'Zhetysu',
                'city_value' => 'taldykorgan',
                'capacity' => 5550,
            ],
            [
                'title_ru' => 'Стадион им. Гани Муратбаева',
                'title_kk' => 'Ғани Мұратбаев атындағы стадион',
                'title_en' => 'Gani Muratbayev Stadium',
                'city_value' => 'kyzylorda',
                'capacity' => 6800,
            ],
            [
                'title_ru' => 'Мунайши',
                'title_kk' => 'Мұнайшы',
                'title_en' => 'Munayshy',
                'city_value' => 'atyrau',
                'capacity' => 8690,
            ],
            [
                'title_ru' => 'Туркестан-Арена',
                'title_kk' => 'Түркістан-Арена',
                'title_en' => 'Turkestan Arena',
                'city_value' => 'turkestan',
                'capacity' => 7000,
            ],
        ];

        foreach ($stadiums as $stadiumData) {
            $city = City::where('value', $stadiumData['city_value'])->first();

            if ($city) {
                Stadium::updateOrCreate(
                    [
                        'title_ru' => $stadiumData['title_ru'],
                        'city_id' => $city->id,
                    ],
                    [
                        'title_kk' => $stadiumData['title_kk'],
                        'title_en' => $stadiumData['title_en'],
                        'is_active' => true,
                    ]
                );
            }
        }
    }
}
