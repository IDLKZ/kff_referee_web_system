<?php

namespace Database\Seeders;

use App\Models\Hotel;
use App\Models\City;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HotelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get cities or use default
        $cities = City::all();

        if ($cities->isEmpty()) {
            $this->command->warn('No cities found. Please run CitySeeder first.');
            return;
        }

        $hotelsData = [
            [
                'title_ru' => 'Риксос Бородинская',
                'title_kk' => 'Rixos Borodino',
                'title_en' => 'Rixos Borodino',
                'description_ru' => 'Пятизвездочный отель на побережье Каспийского моря с роскошными номерами и первоклассным сервисом.',
                'description_kk' => 'Каспий теңізі жағалауындағы бес жұлдызды отель, люкс нөмірлері мен жоғары сапалы қызметімен.',
                'description_en' => 'Five-star hotel on the Caspian Sea coast with luxurious rooms and first-class service.',
                'email' => 'info@rixos-borodino.kz',
                'address_ru' => 'п. Боровое, ул. Мухтара Ауэзова, 15',
                'address_kk' => 'Бурабай кенті, Мұхтар Әуезов көшесі, 15',
                'address_en' => 'Borovoye settlement, Mukhtar Auezov Street, 15',
                'website' => 'https://rixos.com/borodino',
                'lat' => '53.0647',
                'lon' => '70.2854',
                'star' => 5,
                'is_active' => true,
                'is_partner' => true,
            ],
            [
                'title_ru' => 'Гранд Отель Тянь-Шань',
                'title_kk' => 'Gran Hotel Tien Shan',
                'title_en' => 'Grand Hotel Tien Shan',
                'description_ru' => 'Современный отель в центре города с панорамным видом на горы и удобным доступом к главным достопримечательностям.',
                'description_kk' => 'Тауларға панорамалық көрінісі бар қала ортасындағы заманауи отель, негізгі көрікті жерлерге ыңғайлы қол жеткізу.',
                'description_en' => 'Modern hotel in the city center with panoramic mountain views and easy access to main attractions.',
                'email' => 'booking@grandhotel.kz',
                'address_ru' => 'г. Алматы, ул. Достык, 112',
                'address_kk' => 'Алматы қаласы, Достық көшесі, 112',
                'address_en' => 'Almaty city, Dostyk Street, 112',
                'website' => 'https://grandhotel.kz',
                'lat' => '43.2389',
                'lon' => '76.8895',
                'star' => 4,
                'is_active' => true,
                'is_partner' => true,
            ],
            [
                'title_ru' => 'Каспий Ризорт',
                'title_kk' => 'Kaspiy Resort',
                'title_en' => 'Caspian Resort',
                'description_ru' => 'Курортный отель на берегу моря с собственным пляжем, аквапарком и развлекательными комплексами.',
                'description_kk' => 'Өз жағажайы, аквапаркі мен ойын-сауық кешендері бар теңіз жағалауындағы курорттық отель.',
                'description_en' => 'Resort hotel on the seashore with its own beach, water park and entertainment complexes.',
                'email' => 'welcome@caspianresort.kz',
                'address_ru' => 'г. Актау, ул. Есима, 45',
                'address_kk' => 'Ақтау қаласы, Есім көшесі, 45',
                'address_en' => 'Aktau city, Yesim Street, 45',
                'website' => 'https://caspianresort.kz',
                'lat' => '43.6488',
                'lon' => '51.1736',
                'star' => 4,
                'is_active' => true,
                'is_partner' => true,
            ],
            [
                'title_ru' => 'Спорт Комплекс',
                'title_kk' => 'Sport Complex',
                'title_en' => 'Sport Complex',
                'description_ru' => 'Специализированный отель для спортивных команд с тренировочными площадками и восстановительным центром.',
                'description_kk' => 'Жаттығу алаңдары мен қалпына келтіру орталығы бар спорт командаларына арналған арнайы отель.',
                'description_en' => 'Specialized hotel for sports teams with training grounds and recovery center.',
                'email' => 'info@sportcomplex.kz',
                'address_ru' => 'г. Шымкент, ул. Тауке хана, 88',
                'address_kk' => 'Шымкент қаласы, Тауке хан көшесі, 88',
                'address_en' => 'Shymkent city, Tauke Khan Street, 88',
                'website' => 'https://sportcomplex.kz',
                'lat' => '42.3420',
                'lon' => '69.5943',
                'star' => 3,
                'is_active' => true,
                'is_partner' => true,
            ],
            [
                'title_ru' => 'Бизнес Отель Центральный',
                'title_kk' => 'Business Hotel Central',
                'title_en' => 'Business Hotel Central',
                'description_ru' => 'Современный бизнес-отель в центре делового района с конференц-залами и высокоскоростным интернетом.',
                'description_kk' => 'Конференц-залдары мен жоғары жылдамдықты интернетімен іскерлік аудан ортасындағы заманауи бизнес-отель.',
                'description_en' => 'Modern business hotel in the center of the business district with conference halls and high-speed internet.',
                'email' => 'reservation@businesshotel.kz',
                'address_ru' => 'г. Астана, ул. Республика, 23',
                'address_kk' => 'Астана қаласы, Республика көшесі, 23',
                'address_en' => 'Nur-Sultan city, Republic Street, 23',
                'website' => 'https://businesshotel.kz',
                'lat' => '51.1605',
                'lon' => '71.4704',
                'star' => 4,
                'is_active' => true,
                'is_partner' => true,
            ],
            [
                'title_ru' => 'Горный Отель Алатау',
                'title_kk' => 'Mountain Hotel Alatau',
                'title_en' => 'Mountain Hotel Alatau',
                'description_ru' => 'Уютный отель в горах с доступом к горнолыжным трассам и живописными видами на природу.',
                'description_kk' => 'Таулы шаңғы трассаларына қол жеткізуі мен табиғаттың суретті көріністері бар таулардағы жайлы отель.',
                'description_en' => 'Cozy mountain hotel with access to ski slopes and picturesque nature views.',
                'email' => 'booking@alataumountain.kz',
                'address_ru' => 'г. Алматы, Медеуский район, Табаган',
                'address_kk' => 'Алматы қаласы, Медеу ауданы, Табаған',
                'address_en' => 'Almaty city, Medeu district, Tabagan',
                'website' => 'https://alataumountain.kz',
                'lat' => '43.1394',
                'lon' => '77.0574',
                'star' => 3,
                'is_active' => true,
                'is_partner' => false,
            ],
        ];

        // Create hotels for each city
        $cityIndex = 0;
        $citiesCount = $cities->count();

        foreach ($hotelsData as $hotelData) {
            // Assign a city to the hotel (cycle through available cities)
            $city = $cities[$cityIndex % $citiesCount];
            $hotelData['city_id'] = $city->id;

            Hotel::updateOrCreate(
                ['title_ru' => $hotelData['title_ru']],
                $hotelData
            );

            $cityIndex++;
        }

        $this->command->info('Created ' . count($hotelsData) . ' hotels across ' . $citiesCount . ' cities.');
    }
}