<?php

namespace Database\Seeders;

use App\Models\Facility;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FacilitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $facilities = [
            [
                'title_ru' => 'Кондиционер',
                'title_kk' => 'Кондиционер',
                'title_en' => 'Air Conditioning',
            ],
            [
                'title_ru' => 'Бесплатный Wi-Fi',
                'title_kk' => 'Тегін Wi-Fi',
                'title_en' => 'Free Wi-Fi',
            ],
            [
                'title_ru' => 'Телевизор',
                'title_kk' => 'Телевизор',
                'title_en' => 'Television',
            ],
            [
                'title_ru' => 'Мини-бар',
                'title_kk' => 'Мини-бар',
                'title_en' => 'Mini-bar',
            ],
            [
                'title_ru' => 'Сейф',
                'title_kk' => 'Сейф',
                'title_en' => 'Safe',
            ],
            [
                'title_ru' => 'Фен',
                'title_kk' => 'Фен',
                'title_en' => 'Hair Dryer',
            ],
            [
                'title_ru' => 'Халат',
                'title_kk' => 'Халат',
                'title_en' => 'Bathrobe',
            ],
            [
                'title_ru' => 'Кофемашина',
                'title_kk' => 'Кофе машинасы',
                'title_en' => 'Coffee Machine',
            ],
            [
                'title_ru' => 'Рабочий стол',
                'title_kk' => 'Жұмыс үстелі',
                'title_en' => 'Work Desk',
            ],
            [
                'title_ru' => 'Балкон',
                'title_kk' => 'Балкон',
                'title_en' => 'Balcony',
            ],
            [
                'title_ru' => 'Джакузи',
                'title_kk' => 'Джакузи',
                'title_en' => 'Jacuzzi',
            ],
            [
                'title_ru' => 'Кухня',
                'title_kk' => 'Ас бөлмесі',
                'title_en' => 'Kitchen',
            ],
            [
                'title_ru' => 'Стиральная машина',
                'title_kk' => 'Кір жуу машинасы',
                'title_en' => 'Washing Machine',
            ],
            [
                'title_ru' => 'Парковка',
                'title_kk' => 'Көлік тұрағы',
                'title_en' => 'Parking',
            ],
            [
                'title_ru' => 'Бассейн',
                'title_kk' => 'Бассейн',
                'title_en' => 'Swimming Pool',
            ],
            [
                'title_ru' => 'Спортзал',
                'title_kk' => 'Спорт залы',
                'title_en' => 'Gym',
            ],
            [
                'title_ru' => 'Спа-центр',
                'title_kk' => 'Спа орталығы',
                'title_en' => 'Spa Center',
            ],
            [
                'title_ru' => 'Ресторан',
                'title_kk' => 'Мейрамхана',
                'title_en' => 'Restaurant',
            ],
            [
                'title_ru' => 'Room service',
                'title_kk' => 'Нөмірдегі қызмет',
                'title_en' => 'Room Service',
            ],
            [
                'title_ru' => 'Конференц-зал',
                'title_kk' => 'Конференц-зал',
                'title_en' => 'Conference Hall',
            ],
        ];

        foreach ($facilities as $facilityData) {
            Facility::updateOrCreate(
                ['title_ru' => $facilityData['title_ru']],
                $facilityData
            );
        }
    }
}