<?php

namespace Database\Seeders;

use App\Models\TransportType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TransportTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $transportTypes = [
            [
                'title_ru' => 'Автомобиль',
                'title_kk' => 'Автомобиль',
                'title_en' => 'Car',
                'value' => 'car',
                'is_active' => true,
            ],
            [
                'title_ru' => 'Микроавтобус',
                'title_kk' => 'Микроавтобус',
                'title_en' => 'Minivan',
                'value' => 'minivan',
                'is_active' => true,
            ],
            [
                'title_ru' => 'Автобус',
                'title_kk' => 'Автобус',
                'title_en' => 'Bus',
                'value' => 'bus',
                'is_active' => true,
            ],
            [
                'title_ru' => 'Самолёт',
                'title_kk' => 'Ұшақ',
                'title_en' => 'Airplane',
                'value' => 'airplane',
                'is_active' => true,
            ],
            [
                'title_ru' => 'Поезд',
                'title_kk' => 'Пойыз',
                'title_en' => 'Train',
                'value' => 'train',
                'is_active' => true,
            ],
            [
                'title_ru' => 'Такси',
                'title_kk' => 'Такси',
                'title_en' => 'Taxi',
                'value' => 'taxi',
                'is_active' => true,
            ],
        ];

        foreach ($transportTypes as $transportTypeData) {
            TransportType::updateOrCreate(
                ['value' => $transportTypeData['value']],
                $transportTypeData
            );
        }
    }
}