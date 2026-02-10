<?php

namespace Database\Seeders;

use App\Constants\CountryConstants;
use App\Models\City;
use App\Models\Country;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kazakhstanId = Country::where('value', CountryConstants::KAZAKHSTAN)->first()?->id;

        $cities = [
            ['title_ru' => 'Алматы', 'title_kk' => 'Алматы', 'title_en' => 'Almaty', 'value' => 'almaty'],
            ['title_ru' => 'Астана', 'title_kk' => 'Астана', 'title_en' => 'Astana', 'value' => 'astana'],
            ['title_ru' => 'Караганда', 'title_kk' => 'Қарағанды', 'title_en' => 'Karaganda', 'value' => 'karaganda'],
            ['title_ru' => 'Шымкент', 'title_kk' => 'Шымкент', 'title_en' => 'Shymkent', 'value' => 'shymkent'],
            ['title_ru' => 'Абай', 'title_kk' => 'Абай', 'title_en' => 'Abai', 'value' => 'abai'],
            ['title_ru' => 'Айтеке би', 'title_kk' => 'Айтеке би', 'title_en' => 'Aiteke bi', 'value' => 'aiteke bi'],
            ['title_ru' => 'Аксай', 'title_kk' => 'Ақсай', 'title_en' => 'Aksay', 'value' => 'aksai'],
            ['title_ru' => 'Аксай', 'title_kk' => 'Ақсай', 'title_en' => 'Aksay', 'value' => 'aksay'],
            ['title_ru' => 'Аксу', 'title_kk' => 'Ақсу', 'title_en' => 'Aksu', 'value' => 'aksu'],
            ['title_ru' => 'Аксуат', 'title_kk' => 'Аксуат', 'title_en' => 'Aksuat', 'value' => 'aksuat'],
            ['title_ru' => 'Актау', 'title_kk' => 'Ақтау', 'title_en' => 'Aktau', 'value' => 'aktau'],
            ['title_ru' => 'Актобе', 'title_kk' => 'Ақтөбе', 'title_en' => 'Aktobe', 'value' => 'aktobe'],
            ['title_ru' => 'Алтай', 'title_kk' => 'Алтай', 'title_en' => 'altai', 'value' => 'altai'],
            ['title_ru' => 'Аральск', 'title_kk' => 'Арал', 'title_en' => 'Aralsk', 'value' => 'aral'],
            ['title_ru' => 'Аркалык', 'title_kk' => 'Арқалық', 'title_en' => 'Arkalyk', 'value' => 'arkalyk'],
            ['title_ru' => 'Арысь', 'title_kk' => 'Арысь', 'title_en' => 'Arys', 'value' => 'arys'],
            ['title_ru' => 'Атбасар', 'title_kk' => 'Атбасар', 'title_en' => 'Atbasar', 'value' => 'atbasar'],
            ['title_ru' => 'Атырау', 'title_kk' => 'Атырау', 'title_en' => 'Atyrau', 'value' => 'atyrau'],
            ['title_ru' => 'Аулиеколь', 'title_kk' => 'Аулиеколь', 'title_en' => 'Auliekol', 'value' => 'auliekol'],
            ['title_ru' => 'Байконур', 'title_kk' => 'Байконур', 'title_en' => 'Baikonur', 'value' => 'baikonur'],
            ['title_ru' => 'Балхаш', 'title_kk' => 'Балхаш', 'title_en' => 'Balhash', 'value' => 'balhash'],
            ['title_ru' => 'Бейнеу', 'title_kk' => 'Бейнеу', 'title_en' => 'Beineu', 'value' => 'beineu'],
            ['title_ru' => 'Бурабай', 'title_kk' => 'Бурабай', 'title_en' => 'Burabay', 'value' => 'burabay'],
            ['title_ru' => 'Глубокое', 'title_kk' => 'Глубокое', 'title_en' => 'Глубокое', 'value' => 'glubokoe'],
            ['title_ru' => 'Державинск', 'title_kk' => 'Державинск', 'title_en' => 'Державинск', 'value' => 'derzhavinsk'],
            ['title_ru' => 'Ерейментау', 'title_kk' => 'Ерейментау', 'title_en' => 'Ereymentau', 'value' => 'ereymentau'],
            ['title_ru' => 'Есік', 'title_kk' => 'Есік', 'title_en' => 'Esik', 'value' => 'esik'],
            ['title_ru' => 'Жайрем', 'title_kk' => 'Жәйрем', 'title_en' => 'Zhairem', 'value' => 'zhairem'],
            ['title_ru' => 'Жанакорган', 'title_kk' => 'Жанакорган', 'title_en' => 'Zhanakorgan', 'value' => 'zhanakorgan'],
            ['title_ru' => 'Жанаозен', 'title_kk' => 'Жаңаөзен', 'title_en' => 'Zhanaozen', 'value' => 'zhanaozen'],
            ['title_ru' => 'Жанарка', 'title_kk' => 'Жаңарқа', 'title_en' => 'Zhanarka', 'value' => 'zhanarka'],
            ['title_ru' => 'Жаркент', 'title_kk' => 'Жаркент', 'title_en' => 'Zharkent', 'value' => 'zharkent'],
            ['title_ru' => 'Жезказган', 'title_kk' => 'Жезказган', 'title_en' => 'Zhezkazgan', 'value' => 'zhezkazgan'],
            ['title_ru' => 'Жетисай', 'title_kk' => 'Жетысай', 'title_en' => 'zhetisay', 'value' => 'zhetisay'],
            ['title_ru' => 'Житикара', 'title_kk' => 'Житикара', 'title_en' => 'Zhetikara', 'value' => 'zhetikara'],
            ['title_ru' => 'Зыряновск', 'title_kk' => 'Зыряновск', 'title_en' => 'Zyryanovsk', 'value' => 'zyryanovsk'],
            ['title_ru' => 'Индер', 'title_kk' => 'Индер', 'title_en' => 'Inder', 'value' => 'inder'],
            ['title_ru' => 'Иргели', 'title_kk' => 'Иргели', 'title_en' => 'Irgeli', 'value' => 'irgeli'],
            ['title_ru' => 'Иртышск', 'title_kk' => 'Иртышск', 'title_en' => 'Irtyshsk', 'value' => 'irtyshsk'],
            ['title_ru' => 'Иссык', 'title_kk' => 'Иссык', 'title_en' => 'Issyk', 'value' => 'issyk'],
            ['title_ru' => 'Казалинск', 'title_kk' => 'Казалинск', 'title_en' => 'Kazalinsk', 'value' => 'kazalinsk'],
            ['title_ru' => 'Казалы', 'title_kk' => 'Қазалы', 'title_en' => 'Kazaly', 'value' => 'kazaly'],
            ['title_ru' => 'Кандыагаш', 'title_kk' => 'Қандыағаш', 'title_en' => 'Kandyagash', 'value' => 'kandyagash'],
            ['title_ru' => 'Капшагай', 'title_kk' => 'Қапшағай', 'title_en' => 'Kapchagay', 'value' => 'kapchagay'],
            ['title_ru' => 'Карабулак', 'title_kk' => 'Карабулак', 'title_en' => 'Karabulak', 'value' => 'karabulak'],
            ['title_ru' => 'Каркаралы', 'title_kk' => 'Қарқаралы', 'title_en' => 'Karkaraly', 'value' => 'karkaraly'],
            ['title_ru' => 'Каскелен', 'title_kk' => 'Каскелен', 'title_en' => 'Kaskelen', 'value' => 'kaskelen'],
            ['title_ru' => 'Кентау', 'title_kk' => 'Кентау', 'title_en' => 'Kentau', 'value' => 'kentau'],
            ['title_ru' => 'Кокшетау', 'title_kk' => 'Көкшетау', 'title_en' => 'Kokshetau', 'value' => 'kokshetau'],
            ['title_ru' => 'Конаев', 'title_kk' => 'Конаев', 'title_en' => 'Konaev', 'value' => 'konaev'],
            ['title_ru' => 'Кордай', 'title_kk' => 'Кордай', 'title_en' => 'Kordai', 'value' => 'kordai'],
            ['title_ru' => 'Костанай', 'title_kk' => 'Қостанай', 'title_en' => 'Kostanay', 'value' => 'kostanay'],
            ['title_ru' => 'Красный Яр', 'title_kk' => 'Красный Яр', 'title_en' => 'Красный Яр', 'value' => 'krasniy'],
            ['title_ru' => 'Кульсары', 'title_kk' => 'Кульсары', 'title_en' => 'Kulsary', 'value' => 'kulsary'],
            ['title_ru' => 'Курмангазы', 'title_kk' => 'Құрманғазы', 'title_en' => 'Kurmangazy', 'value' => 'kurmangazy'],
            ['title_ru' => 'Курчатов', 'title_kk' => 'Курчатов', 'title_en' => 'Kurchatov', 'value' => 'kurchatov'],
            ['title_ru' => 'Кызылорда', 'title_kk' => 'Қызылорда', 'title_en' => 'Kyzylorda', 'value' => 'kyzylorda'],
            ['title_ru' => 'Лисаковск', 'title_kk' => 'Лисаковск', 'title_en' => 'Lisakovsk', 'value' => 'lisakovsk'],
            ['title_ru' => 'Макинск', 'title_kk' => 'Макинск', 'title_en' => 'Makinsk', 'value' => 'makinsk'],
            ['title_ru' => 'Мангыстау', 'title_kk' => 'Мангыстау', 'title_en' => 'Mangystau', 'value' => 'mangystau'],
            ['title_ru' => 'Мерке', 'title_kk' => 'Мерке', 'title_en' => 'Merke', 'value' => 'merke'],
            ['title_ru' => 'Новоишимка', 'title_kk' => 'Новоишимка', 'title_en' => 'Novoishimka', 'value' => 'novoishimka'],
            ['title_ru' => 'Осакаровка', 'title_kk' => 'Осакаровка', 'title_en' => 'Osakarovka', 'value' => 'osakarovka'],
            ['title_ru' => 'Павлодар', 'title_kk' => 'Павлодар', 'title_en' => 'Pavlodar', 'value' => 'pavlodar'],
            ['title_ru' => 'Петропавловск', 'title_kk' => 'Петропавл', 'title_en' => 'Petropavlovsk', 'value' => 'petropavlovsk'],
            ['title_ru' => 'Риддер', 'title_kk' => 'Риддер', 'title_en' => 'Ridder', 'value' => 'ridder'],
            ['title_ru' => 'Рудный', 'title_kk' => 'Рудный', 'title_en' => 'Rudnyi', 'value' => 'rudnyi'],
            ['title_ru' => 'Сарань', 'title_kk' => 'Саран', 'title_en' => 'Saran', 'value' => 'saran'],
            ['title_ru' => 'Сарыагаш', 'title_kk' => 'Сарыагаш', 'title_en' => 'Saryagash', 'value' => 'saryagash'],
            ['title_ru' => 'Сатпаев', 'title_kk' => 'Сатпаев', 'title_en' => 'Satpayev', 'value' => 'satpayev'],
            ['title_ru' => 'Семей', 'title_kk' => 'Семей', 'title_en' => 'Semey', 'value' => 'semey'],
            ['title_ru' => 'Сергеевка', 'title_kk' => 'Сергеевка', 'title_en' => 'Sergeevka', 'value' => 'sergeevka'],
            ['title_ru' => 'Степногорск', 'title_kk' => 'Степногорск', 'title_en' => 'Stepnogorsk', 'value' => 'stepnogorsk'],
            ['title_ru' => 'Тайынша', 'title_kk' => 'Тайынша', 'title_en' => 'Taiynsha', 'value' => 'taiynsha'],
            ['title_ru' => 'Талгар', 'title_kk' => 'Талгар', 'title_en' => 'Talgar', 'value' => 'talgar'],
            ['title_ru' => 'Талдыкорган', 'title_kk' => 'Талдықорған', 'title_en' => 'Taldykorgan', 'value' => 'taldykorgan'],
            ['title_ru' => 'Тараз', 'title_kk' => 'Тараз', 'title_en' => 'Taraz', 'value' => 'taraz'],
            ['title_ru' => 'Текели', 'title_kk' => 'Текели', 'title_en' => 'Tekeli', 'value' => 'tekeli'],
            ['title_ru' => 'Темиртау', 'title_kk' => 'Теміртау', 'title_en' => 'Temirtau', 'value' => 'temirtau'],
            ['title_ru' => 'Тобыл', 'title_kk' => 'Тобыл', 'title_en' => 'Tobol', 'value' => 'tobol'],
            ['title_ru' => 'Туркестан', 'title_kk' => 'Туркестан', 'title_en' => 'Turkestan', 'value' => 'turkestan'],
            ['title_ru' => 'Узын-агаш', 'title_kk' => 'Узын-агаш', 'title_en' => 'Usyn-agash', 'value' => 'usynagash'],
            ['title_ru' => 'Уральск', 'title_kk' => 'Орал', 'title_en' => 'Uralsk', 'value' => 'uralsk'],
            ['title_ru' => 'Урджар', 'title_kk' => 'Урджар', 'title_en' => 'Urjar', 'value' => 'urjar'],
            ['title_ru' => 'Усть-Каменогорск', 'title_kk' => 'Өскемен', 'title_en' => 'Ust-Kamenogorsk', 'value' => 'ustkamenogorsk'],
            ['title_ru' => 'Уштобе', 'title_kk' => 'Үштөбе', 'title_en' => 'Ushtobe', 'value' => 'ushtobe'],
            ['title_ru' => 'Хромтау', 'title_kk' => 'Хромтау', 'title_en' => 'Khromtau', 'value' => 'Khromtau'],
            ['title_ru' => 'Шаульдер', 'title_kk' => 'Шаульдер', 'title_en' => 'Shaulder', 'value' => 'shaulder'],
            ['title_ru' => 'Шахтинск', 'title_kk' => 'Шахтинск', 'title_en' => 'Shakhtinsk', 'value' => 'shakhtinsk'],
            ['title_ru' => 'Шемонаиха', 'title_kk' => 'Шемонаиха', 'title_en' => 'Shemonaiha', 'value' => 'shemonaiha'],
            ['title_ru' => 'Шиели', 'title_kk' => 'Шиелі', 'title_en' => 'Shieli', 'value' => 'shieli'],
            ['title_ru' => 'Шортанды', 'title_kk' => 'Шортанды', 'title_en' => 'Shortandy', 'value' => 'shortandy'],
            ['title_ru' => 'Щербакты', 'title_kk' => 'Щербакты', 'title_en' => 'Sherbakty', 'value' => 'sherbakty'],
            ['title_ru' => 'Щучинск', 'title_kk' => 'Щучинск', 'title_en' => 'Shchuchinsk', 'value' => 'shchuchinsk'],
            ['title_ru' => 'Экибастуз', 'title_kk' => 'Екібастұз', 'title_en' => 'Ekibastuz', 'value' => 'ekibastuz'],
        ];

        foreach ($cities as $cityData) {
            City::updateOrCreate(
                ['value' => $cityData['value']],
                array_merge($cityData, ['country_id' => $kazakhstanId, 'is_active' => true])
            );
        }
    }
}
