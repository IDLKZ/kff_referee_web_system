<?php

namespace Database\Seeders;

use App\Models\Hotel;
use App\Models\HotelRoom;
use App\Models\RoomFacility;
use App\Models\Facility;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HotelRoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get some hotels or create sample ones if they don't exist
        $hotels = Hotel::all();

        if ($hotels->isEmpty()) {
            // If no hotels exist, we'll skip this seeder
            $this->command->warn('No hotels found. Skipping HotelRoom seeder. Please run HotelSeeder first.');
            return;
        }

        $roomsData = [
            // Single rooms
            [
                'title_ru' => 'Стандартный одноместный номер',
                'title_kk' => 'Стандартты бір орындық номер',
                'title_en' => 'Standard Single Room',
                'description_ru' => 'Комфортный одноместный номер с необходимыми удобствами',
                'description_kk' => 'Қажетті ыңғайлықтары бар жайлы бір орындық номер',
                'description_en' => 'Comfortable single room with essential amenities',
                'bed_quantity' => 1,
                'room_size' => 15.50,
                'air_conditioning' => true,
                'private_bathroom' => true,
                'tv' => true,
                'wifi' => true,
                'smoking_allowed' => false,
                'facilities' => ['Кондиционер', 'Бесплатный Wi-Fi', 'Телевизор', 'Фен', 'Сейф', 'Рабочий стол']
            ],
            [
                'title_ru' => 'Улучшенный одноместный номер',
                'title_kk' => 'Жақсартылған бір орындық номер',
                'title_en' => 'Superior Single Room',
                'description_ru' => 'Просторный одноместный номер с улучшенными удобствами',
                'description_kk' => 'Жақсартылған ыңғайлықтары бар кең бір орындық номер',
                'description_en' => 'Spacious single room with enhanced amenities',
                'bed_quantity' => 1,
                'room_size' => 20.00,
                'air_conditioning' => true,
                'private_bathroom' => true,
                'tv' => true,
                'wifi' => true,
                'smoking_allowed' => false,
                'facilities' => ['Кондиционер', 'Бесплатный Wi-Fi', 'Телевизор', 'Мини-бар', 'Сейф', 'Фен', 'Халат', 'Кофемашина', 'Рабочий стол', 'Балкон']
            ],
            // Double rooms
            [
                'title_ru' => 'Стандартный двухместный номер',
                'title_kk' => 'Стандартты екі орындық номер',
                'title_en' => 'Standard Double Room',
                'description_ru' => 'Уютный двухместный номер с двуспальной кроватью',
                'description_kk' => 'Екі төсекті төсекпен ыңғайлы екі орындық номер',
                'description_en' => 'Cozy double room with queen-size bed',
                'bed_quantity' => 1,
                'room_size' => 18.00,
                'air_conditioning' => true,
                'private_bathroom' => true,
                'tv' => true,
                'wifi' => true,
                'smoking_allowed' => false,
                'facilities' => ['Кондиционер', 'Бесплатный Wi-Fi', 'Телевизор', 'Фен', 'Сейф', 'Рабочий стол']
            ],
            [
                'title_ru' => 'Семейный номер',
                'title_kk' => 'Отандық номер',
                'title_en' => 'Family Room',
                'description_ru' => 'Просторный номер для семьи с детьми',
                'description_kk' => 'Балалары бар отбасы үшін кең номер',
                'description_en' => 'Spacious room for families with children',
                'bed_quantity' => 2,
                'room_size' => 25.00,
                'air_conditioning' => true,
                'private_bathroom' => true,
                'tv' => true,
                'wifi' => true,
                'smoking_allowed' => false,
                'facilities' => ['Кондиционер', 'Бесплатный Wi-Fi', 'Телевизор', 'Мини-бар', 'Сейф', 'Фен', 'Халат', 'Рабочий стол']
            ],
            // Suite rooms
            [
                'title_ru' => 'Студия',
                'title_kk' => 'Студия',
                'title_en' => 'Studio Suite',
                'description_ru' => 'Современный номер-студия с кухонной зоной',
                'description_kk' => 'Ас бөлмесі аймағы бар заманауи студия номері',
                'description_en' => 'Modern studio suite with kitchenette',
                'bed_quantity' => 1,
                'room_size' => 30.00,
                'air_conditioning' => true,
                'private_bathroom' => true,
                'tv' => true,
                'wifi' => true,
                'smoking_allowed' => false,
                'facilities' => ['Кондиционер', 'Бесплатный Wi-Fi', 'Телевизор', 'Мини-бар', 'Сейф', 'Фен', 'Халат', 'Кухня', 'Кофемашина', 'Рабочий стол']
            ],
            [
                'title_ru' => 'Люксовый номер',
                'title_kk' => 'Люкс номері',
                'title_en' => 'Deluxe Suite',
                'description_ru' => 'Роскошный номер с отдельной гостиной и спальней',
                'description_kk' => 'Ашық бөлме мен жатын бөлмесі бар luxurious номер',
                'description_en' => 'Luxurious suite with separate living room and bedroom',
                'bed_quantity' => 2,
                'room_size' => 45.00,
                'air_conditioning' => true,
                'private_bathroom' => true,
                'tv' => true,
                'wifi' => true,
                'smoking_allowed' => false,
                'facilities' => ['Кондиционер', 'Бесплатный Wi-Fi', 'Телевизор', 'Мини-бар', 'Сейф', 'Фен', 'Халат', 'Кофемашина', 'Рабочий стол', 'Балкон', 'Джакузи']
            ],
            // Twin rooms
            [
                'title_ru' => 'Номер с двумя кроватями',
                'title_kk' => 'Екі төсекпен номер',
                'title_en' => 'Twin Room',
                'description_ru' => 'Номер с двумя отдельными односпальными кроватями',
                'description_kk' => 'Екі жеке бір төсекті төсекпен номер',
                'description_en' => 'Room with two separate single beds',
                'bed_quantity' => 2,
                'room_size' => 20.00,
                'air_conditioning' => true,
                'private_bathroom' => true,
                'tv' => true,
                'wifi' => true,
                'smoking_allowed' => false,
                'facilities' => ['Кондиционер', 'Бесплатный Wi-Fi', 'Телевизор', 'Фен', 'Сейф', 'Рабочий стол']
            ],
        ];

        // Create rooms for each hotel
        foreach ($hotels as $hotel) {
            foreach ($roomsData as $roomData) {
                // Remove facilities from room creation data
                $facilities = $roomData['facilities'];
                $roomCreateData = $roomData;
                unset($roomCreateData['facilities']);

                // Add hotel_id
                $roomCreateData['hotel_id'] = $hotel->id;

                // Create the room
                $room = HotelRoom::updateOrCreate(
                    [
                        'hotel_id' => $hotel->id,
                        'title_ru' => $roomCreateData['title_ru']
                    ],
                    $roomCreateData
                );

                // Attach facilities to the room
                if ($room->wasRecentlyCreated || $room->room_facilities->isEmpty()) {
                    $this->attachFacilitiesToRoom($room, $facilities);
                }
            }
        }
    }

    /**
     * Attach facilities to a hotel room
     */
    private function attachFacilitiesToRoom(HotelRoom $room, array $facilityNames): void
    {
        // Get all facilities
        $facilities = Facility::all();

        if ($facilities->isEmpty()) {
            // If no facilities exist, create them first
            $this->call(FacilitySeeder::class);
            $facilities = Facility::all();
        }

        // Find facility IDs by name
        foreach ($facilityNames as $facilityName) {
            $facility = $facilities->firstWhere('title_ru', $facilityName);

            if ($facility) {
                RoomFacility::updateOrCreate(
                    [
                        'room_id' => $room->id,
                        'facility_id' => $facility->id
                    ]
                );
            }
        }
    }
}