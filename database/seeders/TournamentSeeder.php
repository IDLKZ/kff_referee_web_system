<?php

namespace Database\Seeders;

use App\Constants\CountryConstants;
use App\Constants\TournamentConstants;
use App\Models\Country;
use App\Models\Tournament;
use Illuminate\Database\Seeder;

class TournamentSeeder extends Seeder
{
    public function run(): void
    {
        $kazakhstan = Country::where('value', CountryConstants::KAZAKHSTAN)->first();

        $tournaments = [
            [
                'title_ru' => 'Премьер Лига Казахстана',
                'title_kk' => 'Қазақстанның Премьер-Лигасы',
                'title_en' => 'Kazakhstan Premier League',
                'short_title_ru' => 'Премьер-Лига',
                'short_title_kk' => 'Премьер-Лига',
                'short_title_en' => 'Premier League',
                'description_ru' => 'Высший дивизион профессионального футбола Казахстана',
                'description_kk' => 'Қазақстанның кәсіби футболының жоғары дивизионы',
                'description_en' => 'Top tier of professional football in Kazakhstan',
                'value' => TournamentConstants::PREMIER_LEAGUE,
                'country_id' => $kazakhstan?->id,
                'level' => 1,
                'sex' => 1,
                'is_active' => true,
            ],
            [
                'title_ru' => 'Первая Лига Казахстана',
                'title_kk' => 'Қазақстанның Бірінші Лигасы',
                'title_en' => 'Kazakhstan First League',
                'short_title_ru' => 'Первая Лига',
                'short_title_kk' => 'Бірінші Лига',
                'short_title_en' => 'First League',
                'description_ru' => 'Второй по значимости дивизион футбола Казахстана',
                'description_kk' => 'Қазақстанның екінші маңызды футбол дивизионы',
                'description_en' => 'Second tier of football in Kazakhstan',
                'value' => TournamentConstants::FIRST_LEAGUE,
                'country_id' => $kazakhstan?->id,
                'level' => 2,
                'sex' => 1,
                'is_active' => true,
            ],
            [
                'title_ru' => 'Вторая Лига Казахстана',
                'title_kk' => 'Қазақстанның Екінші Лигасы',
                'title_en' => 'Kazakhstan Second League',
                'short_title_ru' => 'Вторая Лига',
                'short_title_kk' => 'Екінші Лига',
                'short_title_en' => 'Second League',
                'description_ru' => 'Третий дивизион футбола Казахстана',
                'description_kk' => 'Қазақстанның үшінші футбол дивизионы',
                'description_en' => 'Third tier of football in Kazakhstan',
                'value' => TournamentConstants::SECOND_LEAGUE,
                'country_id' => $kazakhstan?->id,
                'level' => 3,
                'sex' => 1,
                'is_active' => true,
            ],
            [
                'title_ru' => 'Женская Лига Казахстана',
                'title_kk' => 'Қазақстанның Әйелдер Лигасы',
                'title_en' => 'Kazakhstan Women\'s League',
                'short_title_ru' => 'Женская Лига',
                'short_title_kk' => 'Әйелдер Лигасы',
                'short_title_en' => 'Women\'s League',
                'description_ru' => 'Высший дивизион женского футбола Казахстана',
                'description_kk' => 'Қазақстанның әйелдер футболының жоғары дивизионы',
                'description_en' => 'Top tier of women\'s football in Kazakhstan',
                'value' => TournamentConstants::WOMENS_LEAGUE,
                'country_id' => $kazakhstan?->id,
                'level' => 1,
                'sex' => 2,
                'is_active' => true,
            ],
        ];

        foreach ($tournaments as $tournament) {
            Tournament::updateOrCreate(
                ['value' => $tournament['value']],
                $tournament
            );
        }
    }
}
