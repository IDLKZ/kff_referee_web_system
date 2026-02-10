<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            // Справочники (без зависимостей)
            RoleSeeder::class,
            PermissionSeeder::class,
            RolePermissionSeeder::class,
            CountrySeeder::class,
            CitySeeder::class,
            JudgeTypeSeeder::class,
            SeasonSeeder::class,
            ClubTypeSeeder::class,
            TransportTypeSeeder::class,
            FacilitySeeder::class,

            // Бизнес-процесс
            CategoryOperationSeeder::class,
            OperationSeeder::class,

            // Основные сущности
            TournamentSeeder::class,
            StadiumSeeder::class,
            ClubSeeder::class,
            HotelSeeder::class,
            HotelRoomSeeder::class,

            // Пользователи (после ролей)
            UserSeeder::class,
        ]);
    }
}
