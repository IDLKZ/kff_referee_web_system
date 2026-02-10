<?php

namespace Database\Seeders;

use App\Constants\CountryConstants;
use App\Models\Country;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $countries = [
            [
                'title_ru' => 'Казахстан',
                'title_kk' => 'Қазақстан',
                'title_en' => 'Kazakhstan',
                'value' => CountryConstants::KAZAKHSTAN,
                'is_active' => true,
            ],
        ];

        foreach ($countries as $countryData) {
            Country::updateOrCreate(
                ['value' => $countryData['value']],
                $countryData
            );
        }
    }
}
