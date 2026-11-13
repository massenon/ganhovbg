<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Timezone;
use DB;

class TimezoneSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('timezone')->insert([
            [
                'timezone' => 'Europe/Amsterdam',
                'group' => 'Europe',
                'utc' => '(GMT/UTC +02:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Europe/Andorra',
                'group' => 'Europe',
                'utc' => '(GMT/UTC +02:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Europe/Astrakhan',
                'group' => 'Europe',
                'utc' => '(GMT/UTC +04:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Europe/Athens',
                'group' => 'Europe',
                'utc' => '(GMT/UTC +03:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Europe/Belgrade',
                'group' => 'Europe',
                'utc' => '(GMT/UTC +02:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Europe/Berlin',
                'group' => 'Europe',
                'utc' => '(GMT/UTC +02:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Europe/Bratislava',
                'group' => 'Europe',
                'utc' => '(GMT/UTC +02:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Europe/Brussels',
                'group' => 'Europe',
                'utc' => '(GMT/UTC +02:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Europe/Bucharest',
                'group' => 'Europe',
                'utc' => '(GMT/UTC +03:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Europe/Budapest',
                'group' => 'Europe',
                'utc' => '(GMT/UTC +02:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Europe/Busingen',
                'group' => 'Europe',
                'utc' => '(GMT/UTC +02:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Europe/Chisinau',
                'group' => 'Europe',
                'utc' => '(GMT/UTC +03:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Europe/Copenhagen',
                'group' => 'Europe',
                'utc' => '(GMT/UTC +02:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Europe/Dublin',
                'group' => 'Europe',
                'utc' => '(GMT/UTC +01:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Europe/Gibraltar',
                'group' => 'Europe',
                'utc' => '(GMT/UTC +02:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Europe/Guernsey',
                'group' => 'Europe',
                'utc' => '(GMT/UTC +01:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Europe/Helsinki',
                'group' => 'Europe',
                'utc' => '(GMT/UTC +03:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Europe/Isle_of_Man',
                'group' => 'Europe',
                'utc' => '(GMT/UTC +01:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Europe/Istanbul',
                'group' => 'Europe',
                'utc' => '(GMT/UTC +03:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Europe/Jersey',
                'group' => 'Europe',
                'utc' => '(GMT/UTC +01:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Europe/Kaliningrad',
                'group' => 'Europe',
                'utc' => '(GMT/UTC +02:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Europe/Kiev',
                'group' => 'Europe',
                'utc' => '(GMT/UTC +03:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Europe/Kirov',
                'group' => 'Europe',
                'utc' => '(GMT/UTC +03:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Europe/Lisbon',
                'group' => 'Europe',
                'utc' => '(GMT/UTC +01:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Europe/Ljubljana',
                'group' => 'Europe',
                'utc' => '(GMT/UTC +02:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Europe/London',
                'group' => 'Europe',
                'utc' => '(GMT/UTC +01:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Europe/Luxembourg',
                'group' => 'Europe',
                'utc' => '(GMT/UTC +02:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Europe/Madrid',
                'group' => 'Europe',
                'utc' => '(GMT/UTC +02:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Europe/Malta',
                'group' => 'Europe',
                'utc' => '(GMT/UTC +02:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Europe/Mariehamn',
                'group' => 'Europe',
                'utc' => '(GMT/UTC +03:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Europe/Minsk',
                'group' => 'Europe',
                'utc' => '(GMT/UTC +03:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Europe/Monaco',
                'group' => 'Europe',
                'utc' => '(GMT/UTC +02:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Europe/Moscow',
                'group' => 'Europe',
                'utc' => '(GMT/UTC +03:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Europe/Oslo',
                'group' => 'Europe',
                'utc' => '(GMT/UTC +02:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Europe/Paris',
                'group' => 'Europe',
                'utc' => '(GMT/UTC +02:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Europe/Podgorica',
                'group' => 'Europe',
                'utc' => '(GMT/UTC +02:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Europe/Prague',
                'group' => 'Europe',
                'utc' => '(GMT/UTC +02:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Europe/Riga',
                'group' => 'Europe',
                'utc' => '(GMT/UTC +03:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Europe/Rome',
                'group' => 'Europe',
                'utc' => '(GMT/UTC +02:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Europe/Samara',
                'group' => 'Europe',
                'utc' => '(GMT/UTC +04:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Europe/San_Marino',
                'group' => 'Europe',
                'utc' => '(GMT/UTC +02:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Europe/Sarajevo',
                'group' => 'Europe',
                'utc' => '(GMT/UTC +02:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Europe/Saratov',
                'group' => 'Europe',
                'utc' => '(GMT/UTC +04:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Europe/Simferopol',
                'group' => 'Europe',
                'utc' => '(GMT/UTC +03:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Europe/Skopje',
                'group' => 'Europe',
                'utc' => '(GMT/UTC +02:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Europe/Sofia',
                'group' => 'Europe',
                'utc' => '(GMT/UTC +03:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Europe/Stockholm',
                'group' => 'Europe',
                'utc' => '(GMT/UTC +02:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Europe/Tallinn',
                'group' => 'Europe',
                'utc' => '(GMT/UTC +03:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Europe/Tirane',
                'group' => 'Europe',
                'utc' => '(GMT/UTC +02:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Europe/Ulyanovsk',
                'group' => 'Europe',
                'utc' => '(GMT/UTC +04:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Europe/Uzhgorod',
                'group' => 'Europe',
                'utc' => '(GMT/UTC +03:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Europe/Vaduz',
                'group' => 'Europe',
                'utc' => '(GMT/UTC +02:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Europe/Vatican',
                'group' => 'Europe',
                'utc' => '(GMT/UTC +02:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Europe/Vienna',
                'group' => 'Europe',
                'utc' => '(GMT/UTC +02:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Europe/Vilnius',
                'group' => 'Europe',
                'utc' => '(GMT/UTC +03:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Europe/Volgograd',
                'group' => 'Europe',
                'utc' => '(GMT/UTC +03:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Europe/Warsaw',
                'group' => 'Europe',
                'utc' => '(GMT/UTC +02:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Europe/Zagreb',
                'group' => 'Europe',
                'utc' => '(GMT/UTC +02:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Europe/Zaporozhye',
                'group' => 'Europe',
                'utc' => '(GMT/UTC +03:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Europe/Zurich',
                'group' => 'Europe',
                'utc' => '(GMT/UTC +02:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'America/Adak',
                'group' => 'America',
                'utc' => '(GMT/UTC -09:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'America/Anchorage',
                'group' => 'America',
                'utc' => '(GMT/UTC -08:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'America/Anguilla',
                'group' => 'America',
                'utc' => '(GMT/UTC -04:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'America/Antigua',
                'group' => 'America',
                'utc' => '(GMT/UTC -04:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'America/Araguaina',
                'group' => 'America',
                'utc' => '(GMT/UTC -03:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'America/Argentina/Buenos_Aires',
                'group' => 'America',
                'utc' => '(GMT/UTC -03:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'America/Argentina/Catamarca',
                'group' => 'America',
                'utc' => '(GMT/UTC -03:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'America/Argentina/Cordoba',
                'group' => 'America',
                'utc' => '(GMT/UTC -03:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'America/Argentina/Jujuy',
                'group' => 'America',
                'utc' => '(GMT/UTC -03:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'America/Argentina/La_Rioja',
                'group' => 'America',
                'utc' => '(GMT/UTC -03:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'America/Argentina/Mendoza',
                'group' => 'America',
                'utc' => '(GMT/UTC -03:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'America/Argentina/Rio_Gallegos',
                'group' => 'America',
                'utc' => '(GMT/UTC -03:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'America/Argentina/Salta',
                'group' => 'America',
                'utc' => '(GMT/UTC -03:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'America/Argentina/San_Juan',
                'group' => 'America',
                'utc' => '(GMT/UTC -03:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'America/Argentina/San_Luis',
                'group' => 'America',
                'utc' => '(GMT/UTC -03:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'America/Argentina/Tucuman',
                'group' => 'America',
                'utc' => '(GMT/UTC -03:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'America/Argentina/Ushuaia',
                'group' => 'America',
                'utc' => '(GMT/UTC -03:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'America/Aruba',
                'group' => 'America',
                'utc' => '(GMT/UTC -04:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'America/Asuncion',
                'group' => 'America',
                'utc' => '(GMT/UTC -04:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'America/Atikokan',
                'group' => 'America',
                'utc' => '(GMT/UTC -05:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'America/Bahia',
                'group' => 'America',
                'utc' => '(GMT/UTC -03:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'America/Bahia_Banderas',
                'group' => 'America',
                'utc' => '(GMT/UTC -05:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'America/Barbados',
                'group' => 'America',
                'utc' => '(GMT/UTC -04:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'America/Belem',
                'group' => 'America',
                'utc' => '(GMT/UTC -03:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'America/Belize',
                'group' => 'America',
                'utc' => '(GMT/UTC -06:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'America/Blanc-Sablon',
                'group' => 'America',
                'utc' => '(GMT/UTC -04:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'America/Boa_Vista',
                'group' => 'America',
                'utc' => '(GMT/UTC -04:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'America/Bogota',
                'group' => 'America',
                'utc' => '(GMT/UTC -05:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'America/Boise',
                'group' => 'America',
                'utc' => '(GMT/UTC -06:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'America/Cambridge_Bay',
                'group' => 'America',
                'utc' => '(GMT/UTC -06:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'America/Campo_Grande',
                'group' => 'America',
                'utc' => '(GMT/UTC -04:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'America/Cancun',
                'group' => 'America',
                'utc' => '(GMT/UTC -05:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'America/Caracas',
                'group' => 'America',
                'utc' => '(GMT/UTC -04:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'America/Cayenne',
                'group' => 'America',
                'utc' => '(GMT/UTC -03:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'America/Cayman',
                'group' => 'America',
                'utc' => '(GMT/UTC -05:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'America/Chicago',
                'group' => 'America',
                'utc' => '(GMT/UTC -05:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'America/Chihuahua',
                'group' => 'America',
                'utc' => '(GMT/UTC -06:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'America/Costa_Rica',
                'group' => 'America',
                'utc' => '(GMT/UTC -06:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'America/Creston',
                'group' => 'America',
                'utc' => '(GMT/UTC -07:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'America/Cuiaba',
                'group' => 'America',
                'utc' => '(GMT/UTC -04:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'America/Curacao',
                'group' => 'America',
                'utc' => '(GMT/UTC -04:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'America/Danmarkshavn',
                'group' => 'America',
                'utc' => '(GMT/UTC +00:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'America/Dawson',
                'group' => 'America',
                'utc' => '(GMT/UTC -07:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'America/Dawson_Creek',
                'group' => 'America',
                'utc' => '(GMT/UTC -07:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'America/Denver',
                'group' => 'America',
                'utc' => '(GMT/UTC -06:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'America/Detroit',
                'group' => 'America',
                'utc' => '(GMT/UTC -04:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'America/Dominica',
                'group' => 'America',
                'utc' => '(GMT/UTC -04:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'America/Edmonton',
                'group' => 'America',
                'utc' => '(GMT/UTC -06:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'America/Eirunepe',
                'group' => 'America',
                'utc' => '(GMT/UTC -05:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'America/El_Salvador',
                'group' => 'America',
                'utc' => '(GMT/UTC -06:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'America/Fort_Nelson',
                'group' => 'America',
                'utc' => '(GMT/UTC -07:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'America/Fortaleza',
                'group' => 'America',
                'utc' => '(GMT/UTC -03:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'America/Glace_Bay',
                'group' => 'America',
                'utc' => '(GMT/UTC -03:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'America/Goose_Bay',
                'group' => 'America',
                'utc' => '(GMT/UTC -03:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'America/Grand_Turk',
                'group' => 'America',
                'utc' => '(GMT/UTC -04:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'America/Grenada',
                'group' => 'America',
                'utc' => '(GMT/UTC -04:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'America/Guadeloupe',
                'group' => 'America',
                'utc' => '(GMT/UTC -04:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'America/Guatemala',
                'group' => 'America',
                'utc' => '(GMT/UTC -06:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'America/Guayaquil',
                'group' => 'America',
                'utc' => '(GMT/UTC -05:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'America/Guyana',
                'group' => 'America',
                'utc' => '(GMT/UTC -04:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'America/Halifax',
                'group' => 'America',
                'utc' => '(GMT/UTC -03:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'America/Havana',
                'group' => 'America',
                'utc' => '(GMT/UTC -04:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'America/Hermosillo',
                'group' => 'America',
                'utc' => '(GMT/UTC -07:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'America/Indiana/Indianapolis',
                'group' => 'America',
                'utc' => '(GMT/UTC -04:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'America/Indiana/Knox',
                'group' => 'America',
                'utc' => '(GMT/UTC -05:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'America/Indiana/Marengo',
                'group' => 'America',
                'utc' => '(GMT/UTC -04:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'America/Indiana/Petersburg',
                'group' => 'America',
                'utc' => '(GMT/UTC -04:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'America/Indiana/Tell_City',
                'group' => 'America',
                'utc' => '(GMT/UTC -05:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'America/Indiana/Vevay',
                'group' => 'America',
                'utc' => '(GMT/UTC -04:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'America/Indiana/Vincennes',
                'group' => 'America',
                'utc' => '(GMT/UTC -04:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'America/Indiana/Winamac',
                'group' => 'America',
                'utc' => '(GMT/UTC -04:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'America/Inuvik',
                'group' => 'America',
                'utc' => '(GMT/UTC -06:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'America/Iqaluit',
                'group' => 'America',
                'utc' => '(GMT/UTC -04:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],

            [
                'timezone' => 'America/Jamaica',
                'group' => 'America',
                'utc' => '(GMT/UTC -05:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'America/Juneau',
                'group' => 'America',
                'utc' => '(GMT/UTC -08:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'America/Kentucky/Louisville',
                'group' => 'America',
                'utc' => '(GMT/UTC -04:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'America/Kentucky/Monticello',
                'group' => 'America',
                'utc' => '(GMT/UTC -04:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'America/Kralendijk',
                'group' => 'America',
                'utc' => '(GMT/UTC -04:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'America/La_Paz',
                'group' => 'America',
                'utc' => '(GMT/UTC -04:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'America/Lima',
                'group' => 'America',
                'utc' => '(GMT/UTC -05:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'America/Los_Angeles',
                'group' => 'America',
                'utc' => '(GMT/UTC -07:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'America/Lower_Princes',
                'group' => 'America',
                'utc' => '(GMT/UTC -04:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'America/Maceio',
                'group' => 'America',
                'utc' => '(GMT/UTC -03:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'America/Managua',
                'group' => 'America',
                'utc' => '(GMT/UTC -06:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'America/Manaus',
                'group' => 'America',
                'utc' => '(GMT/UTC -04:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'America/Marigot',
                'group' => 'America',
                'utc' => '(GMT/UTC -04:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'America/Martinique',
                'group' => 'America',
                'utc' => '(GMT/UTC -04:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'America/Matamoros',
                'group' => 'America',
                'utc' => '(GMT/UTC -05:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'America/Mazatlan',
                'group' => 'America',
                'utc' => '(GMT/UTC -06:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'America/Menominee',
                'group' => 'America',
                'utc' => '(GMT/UTC -05:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'America/Merida',
                'group' => 'America',
                'utc' => '(GMT/UTC -05:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'America/Metlakatla',
                'group' => 'America',
                'utc' => '(GMT/UTC -08:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'America/Mexico_City',
                'group' => 'America',
                'utc' => '(GMT/UTC -05:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'America/Miquelon',
                'group' => 'America',
                'utc' => '(GMT/UTC -02:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'America/Moncton',
                'group' => 'America',
                'utc' => '(GMT/UTC -03:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'America/Monterrey',
                'group' => 'America',
                'utc' => '(GMT/UTC -05:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'America/Montevideo',
                'group' => 'America',
                'utc' => '(GMT/UTC -03:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'America/Montserrat',
                'group' => 'America',
                'utc' => '(GMT/UTC -04:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'America/Nassau',
                'group' => 'America',
                'utc' => '(GMT/UTC -04:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'America/New_York',
                'group' => 'America',
                'utc' => '(GMT/UTC -04:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'America/Nipigon',
                'group' => 'America',
                'utc' => '(GMT/UTC -04:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'America/Nome',
                'group' => 'America',
                'utc' => '(GMT/UTC -08:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'America/Noronha',
                'group' => 'America',
                'utc' => '(GMT/UTC -02:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'America/North_Dakota/Beulah',
                'group' => 'America',
                'utc' => '(GMT/UTC -05:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'America/North_Dakota/Center',
                'group' => 'America',
                'utc' => '(GMT/UTC -05:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'America/North_Dakota/New_Salem',
                'group' => 'America',
                'utc' => '(GMT/UTC -05:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'America/Nuuk',
                'group' => 'America',
                'utc' => '(GMT/UTC -02:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'America/Ojinaga',
                'group' => 'America',
                'utc' => '(GMT/UTC -06:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'America/Panama',
                'group' => 'America',
                'utc' => '(GMT/UTC -05:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'America/Pangnirtung',
                'group' => 'America',
                'utc' => '(GMT/UTC -04:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'America/Paramaribo',
                'group' => 'America',
                'utc' => '(GMT/UTC -03:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'America/Phoenix',
                'group' => 'America',
                'utc' => '(GMT/UTC -07:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'America/Port-au-Prince',
                'group' => 'America',
                'utc' => '(GMT/UTC -04:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'America/Port_of_Spain',
                'group' => 'America',
                'utc' => '(GMT/UTC -04:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'America/Porto_Velho',
                'group' => 'America',
                'utc' => '(GMT/UTC -04:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'America/Puerto_Rico',
                'group' => 'America',
                'utc' => '(GMT/UTC -04:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'America/Punta_Arenas',
                'group' => 'America',
                'utc' => '(GMT/UTC -03:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'America/Rainy_River',
                'group' => 'America',
                'utc' => '(GMT/UTC -05:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'America/Rankin_Inlet',
                'group' => 'America',
                'utc' => '(GMT/UTC -05:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'America/Recife',
                'group' => 'America',
                'utc' => '(GMT/UTC -03:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],

            [
                'timezone' => 'America/Regina',
                'group' => 'America',
                'utc' => '(GMT/UTC -06:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],[
                'timezone' => 'America/Resolute',
                'group' => 'America',
                'utc' => '(GMT/UTC -05:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'America/Rio_Branco',
                'group' => 'America',
                'utc' => '(GMT/UTC -05:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],

            [
                'timezone' => 'America/Santarem',
                'group' => 'America',
                'utc' => '(GMT/UTC -03:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'America/Santiago',
                'group' => 'America',
                'utc' => '(GMT/UTC -04:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'America/Santo_Domingo',
                'group' => 'America',
                'utc' => '(GMT/UTC -04:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'America/Sao_Paulo',
                'group' => 'America',
                'utc' => '(GMT/UTC -03:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'America/Scoresbysund',
                'group' => 'America',
                'utc' => '(GMT/UTC +00:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'America/Sitka',
                'group' => 'America',
                'utc' => '(GMT/UTC -08:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'America/St_Barthelemy',
                'group' => 'America',
                'utc' => '(GMT/UTC -04:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'America/St_Johns',
                'group' => 'America',
                'utc' => '(GMT/UTC -02:30)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'America/St_Kitts',
                'group' => 'America',
                'utc' => '(GMT/UTC -04:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'America/St_Lucia',
                'group' => 'America',
                'utc' => '(GMT/UTC -04:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'America/St_Thomas',
                'group' => 'America',
                'utc' => '(GMT/UTC -04:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'America/St_Vincent',
                'group' => 'America',
                'utc' => '(GMT/UTC -04:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'America/Swift_Current',
                'group' => 'America',
                'utc' => '(GMT/UTC -06:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'America/Tegucigalpa',
                'group' => 'America',
                'utc' => '(GMT/UTC -06:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'America/Thule',
                'group' => 'America',
                'utc' => '(GMT/UTC -03:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'America/Thunder_Bay',
                'group' => 'America',
                'utc' => '(GMT/UTC -04:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'America/Tijuana',
                'group' => 'America',
                'utc' => '(GMT/UTC -07:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'America/Toronto',
                'group' => 'America',
                'utc' => '(GMT/UTC -04:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'America/Tortola',
                'group' => 'America',
                'utc' => '(GMT/UTC -04:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'America/Vancouver',
                'group' => 'America',
                'utc' => '(GMT/UTC -07:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'America/Whitehorse',
                'group' => 'America',
                'utc' => '(GMT/UTC -07:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'America/Winnipeg',
                'group' => 'America',
                'utc' => '(GMT/UTC -05:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'America/Yakutat',
                'group' => 'America',
                'utc' => '(GMT/UTC -08:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'America/Yellowknife',
                'group' => 'America',
                'utc' => '(GMT/UTC -06:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Indian/Antananarivo',
                'group' => 'Indian',
                'utc' => '(GMT/UTC +03:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Indian/Chagos',
                'group' => 'Indian',
                'utc' => '(GMT/UTC +06:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Indian/Christmas',
                'group' => 'Indian',
                'utc' => '(GMT/UTC +07:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Indian/Cocos',
                'group' => 'Indian',
                'utc' => '(GMT/UTC +06:30)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Indian/Comoro',
                'group' => 'Indian',
                'utc' => '(GMT/UTC +03:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Indian/Kerguelen',
                'group' => 'Indian',
                'utc' => '(GMT/UTC +05:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Indian/Mahe',
                'group' => 'Indian',
                'utc' => '(GMT/UTC +04:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Indian/Maldives',
                'group' => 'Indian',
                'utc' => '(GMT/UTC +05:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Indian/Mauritius',
                'group' => 'Indian',
                'utc' => '(GMT/UTC +04:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Indian/Mayotte',
                'group' => 'Indian',
                'utc' => '(GMT/UTC +03:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Indian/Reunion',
                'group' => 'Indian',
                'utc' => '(GMT/UTC +04:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Australia/Adelaide',
                'group' => 'Australia',
                'utc' => '(GMT/UTC +09:30)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Australia/Brisbane',
                'group' => 'Australia',
                'utc' => '(GMT/UTC +10:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Australia/Broken_Hill',
                'group' => 'Australia',
                'utc' => '(GMT/UTC +09:30)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Australia/Darwin',
                'group' => 'Australia',
                'utc' => '(GMT/UTC +09:30)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Australia/Eucla',
                'group' => 'Australia',
                'utc' => '(GMT/UTC +08:45)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Australia/Hobart',
                'group' => 'Australia',
                'utc' => '(GMT/UTC +10:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Australia/Lindeman',
                'group' => 'Australia',
                'utc' => '(GMT/UTC +10:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Australia/Lord_Howe',
                'group' => 'Australia',
                'utc' => '(GMT/UTC +10:30)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Australia/Melbourne',
                'group' => 'Australia',
                'utc' => '(GMT/UTC +10:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Australia/Perth',
                'group' => 'Australia',
                'utc' => '(GMT/UTC +08:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Australia/Sydney',
                'group' => 'Australia',
                'utc' => '(GMT/UTC +10:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Asia/Aden',
                'group' => 'Asia',
                'utc' => '(GMT/UTC +03:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Asia/Almaty',
                'group' => 'Asia',
                'utc' => '(GMT/UTC +06:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Asia/Amman',
                'group' => 'Asia',
                'utc' => '(GMT/UTC +03:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Asia/Anadyr',
                'group' => 'Asia',
                'utc' => '(GMT/UTC +12:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Asia/Aqtau',
                'group' => 'Asia',
                'utc' => '(GMT/UTC +05:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Asia/Aqtobe',
                'group' => 'Asia',
                'utc' => '(GMT/UTC +05:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Asia/Ashgabat',
                'group' => 'Asia',
                'utc' => '(GMT/UTC +05:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Asia/Atyrau',
                'group' => 'Asia',
                'utc' => '(GMT/UTC +05:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Asia/Baghdad',
                'group' => 'Asia',
                'utc' => '(GMT/UTC +03:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Asia/Bahrain',
                'group' => 'Asia',
                'utc' => '(GMT/UTC +03:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Asia/Baku',
                'group' => 'Asia',
                'utc' => '(GMT/UTC +04:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Asia/Bangkok',
                'group' => 'Asia',
                'utc' => '(GMT/UTC +07:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Asia/Barnaul',
                'group' => 'Asia',
                'utc' => '(GMT/UTC +07:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Asia/Beirut',
                'group' => 'Asia',
                'utc' => '(GMT/UTC +03:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Asia/Bishkek',
                'group' => 'Asia',
                'utc' => '(GMT/UTC +06:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Asia/Brunei',
                'group' => 'Asia',
                'utc' => '(GMT/UTC +08:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Asia/Chita',
                'group' => 'Asia',
                'utc' => '(GMT/UTC +09:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Asia/Choibalsan',
                'group' => 'Asia',
                'utc' => '(GMT/UTC +08:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Asia/Colombo',
                'group' => 'Asia',
                'utc' => '(GMT/UTC +05:30)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Asia/Damascus',
                'group' => 'Asia',
                'utc' => '(GMT/UTC +03:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Asia/Dhaka',
                'group' => 'Asia',
                'utc' => '(GMT/UTC +06:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Asia/Dili',
                'group' => 'Asia',
                'utc' => '(GMT/UTC +09:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Asia/Dubai',
                'group' => 'Asia',
                'utc' => '(GMT/UTC +04:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Asia/Dushanbe',
                'group' => 'Asia',
                'utc' => '(GMT/UTC +05:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Asia/Famagusta',
                'group' => 'Asia',
                'utc' => '(GMT/UTC +03:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Asia/Gaza',
                'group' => 'Asia',
                'utc' => '(GMT/UTC +03:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Asia/Hebron',
                'group' => 'Asia',
                'utc' => '(GMT/UTC +03:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Asia/Ho_Chi_Minh',
                'group' => 'Asia',
                'utc' => '(GMT/UTC +07:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Asia/Hong_Kong',
                'group' => 'Asia',
                'utc' => '(GMT/UTC +08:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Asia/Hovd',
                'group' => 'Asia',
                'utc' => '(GMT/UTC +07:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Asia/Irkutsk',
                'group' => 'Asia',
                'utc' => '(GMT/UTC +08:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Asia/Jakarta',
                'group' => 'Asia',
                'utc' => '(GMT/UTC +07:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Asia/Jayapura',
                'group' => 'Asia',
                'utc' => '(GMT/UTC +09:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Asia/Jerusalem',
                'group' => 'Asia',
                'utc' => '(GMT/UTC +03:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Asia/Kabul',
                'group' => 'Asia',
                'utc' => '(GMT/UTC +04:30)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Asia/Kamchatka',
                'group' => 'Asia',
                'utc' => '(GMT/UTC +12:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Asia/Karachi',
                'group' => 'Asia',
                'utc' => '(GMT/UTC +05:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Asia/Kathmandu',
                'group' => 'Asia',
                'utc' => '(GMT/UTC +05:45)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Asia/Khandyga',
                'group' => 'Asia',
                'utc' => '(GMT/UTC +09:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Asia/Kolkata',
                'group' => 'Asia',
                'utc' => '(GMT/UTC +05:30)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Asia/Krasnoyarsk',
                'group' => 'Asia',
                'utc' => '(GMT/UTC +07:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Asia/Kuala_Lumpur',
                'group' => 'Asia',
                'utc' => '(GMT/UTC +08:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Asia/Kuching',
                'group' => 'Asia',
                'utc' => '(GMT/UTC +08:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Asia/Kuwait',
                'group' => 'Asia',
                'utc' => '(GMT/UTC +03:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Asia/Macau',
                'group' => 'Asia',
                'utc' => '(GMT/UTC +08:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Asia/Magadan',
                'group' => 'Asia',
                'utc' => '(GMT/UTC +11:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Asia/Makassar',
                'group' => 'Asia',
                'utc' => '(GMT/UTC +08:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Asia/Manila',
                'group' => 'Asia',
                'utc' => '(GMT/UTC +08:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Asia/Muscat',
                'group' => 'Asia',
                'utc' => '(GMT/UTC +04:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Asia/Nicosia',
                'group' => 'Asia',
                'utc' => '(GMT/UTC +03:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Asia/Novokuznetsk',
                'group' => 'Asia',
                'utc' => '(GMT/UTC +07:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Asia/Novosibirsk',
                'group' => 'Asia',
                'utc' => '(GMT/UTC +07:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Asia/Omsk',
                'group' => 'Asia',
                'utc' => '(GMT/UTC +06:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Asia/Oral',
                'group' => 'Asia',
                'utc' => '(GMT/UTC +05:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Asia/Phnom_Penh',
                'group' => 'Asia',
                'utc' => '(GMT/UTC +07:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Asia/Pontianak',
                'group' => 'Asia',
                'utc' => '(GMT/UTC +07:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Asia/Pyongyang',
                'group' => 'Asia',
                'utc' => '(GMT/UTC +09:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Asia/Qatar',
                'group' => 'Asia',
                'utc' => '(GMT/UTC +03:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Asia/Qostanay',
                'group' => 'Asia',
                'utc' => '(GMT/UTC +06:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Asia/Qyzylorda',
                'group' => 'Asia',
                'utc' => '(GMT/UTC +05:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Asia/Riyadh',
                'group' => 'Asia',
                'utc' => '(GMT/UTC +03:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Asia/Sakhalin',
                'group' => 'Asia',
                'utc' => '(GMT/UTC +11:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Asia/Samarkand',
                'group' => 'Asia',
                'utc' => '(GMT/UTC +05:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Asia/Seoul',
                'group' => 'Asia',
                'utc' => '(GMT/UTC +09:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Asia/Shanghai',
                'group' => 'Asia',
                'utc' => '(GMT/UTC +08:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Asia/Singapore',
                'group' => 'Asia',
                'utc' => '(GMT/UTC +08:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Asia/Srednekolymsk',
                'group' => 'Asia',
                'utc' => '(GMT/UTC +11:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Asia/Taipei',
                'group' => 'Asia',
                'utc' => '(GMT/UTC +08:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Asia/Tashkent',
                'group' => 'Asia',
                'utc' => '(GMT/UTC +05:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Asia/Tbilisi',
                'group' => 'Asia',
                'utc' => '(GMT/UTC +04:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Asia/Tehran',
                'group' => 'Asia',
                'utc' => '(GMT/UTC +04:30)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Asia/Thimphu',
                'group' => 'Asia',
                'utc' => '(GMT/UTC +06:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Asia/Tokyo',
                'group' => 'Asia',
                'utc' => '(GMT/UTC +09:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Asia/Tomsk',
                'group' => 'Asia',
                'utc' => '(GMT/UTC +07:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Asia/Ulaanbaatar',
                'group' => 'Asia',
                'utc' => '(GMT/UTC +08:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Asia/Urumqi',
                'group' => 'Asia',
                'utc' => '(GMT/UTC +06:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Asia/Ust-Nera',
                'group' => 'Asia',
                'utc' => '(GMT/UTC +10:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Asia/Vientiane',
                'group' => 'Asia',
                'utc' => '(GMT/UTC +07:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Asia/Vladivostok',
                'group' => 'Asia',
                'utc' => '(GMT/UTC +10:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Asia/Yakutsk',
                'group' => 'Asia',
                'utc' => '(GMT/UTC +09:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Asia/Yangon',
                'group' => 'Asia',
                'utc' => '(GMT/UTC +06:30)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Asia/Yekaterinburg',
                'group' => 'Asia',
                'utc' => '(GMT/UTC +05:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Asia/Yerevan',
                'group' => 'Asia',
                'utc' => '(GMT/UTC +04:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Africa/Abidjan',
                'group' => 'Africa',
                'utc' => '(GMT/UTC +00:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Africa/Accra',
                'group' => 'Africa',
                'utc' => '(GMT/UTC +00:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Africa/Addis_Ababa',
                'group' => 'Africa',
                'utc' => '(GMT/UTC +03:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Africa/Algiers',
                'group' => 'Africa',
                'utc' => '(GMT/UTC +01:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Africa/Asmara',
                'group' => 'Africa',
                'utc' => '(GMT/UTC +03:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Africa/Bamako',
                'group' => 'Africa',
                'utc' => '(GMT/UTC +00:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Africa/Bangui',
                'group' => 'Africa',
                'utc' => '(GMT/UTC +01:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Africa/Banjul',
                'group' => 'Africa',
                'utc' => '(GMT/UTC +00:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Africa/Bissau',
                'group' => 'Africa',
                'utc' => '(GMT/UTC +00:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Africa/Blantyre',
                'group' => 'Africa',
                'utc' => '(GMT/UTC +02:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Africa/Brazzaville',
                'group' => 'Africa',
                'utc' => '(GMT/UTC +01:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Africa/Bujumbura',
                'group' => 'Africa',
                'utc' => '(GMT/UTC +02:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Africa/Cairo',
                'group' => 'Africa',
                'utc' => '(GMT/UTC +02:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Africa/Casablanca',
                'group' => 'Africa',
                'utc' => '(GMT/UTC +01:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Africa/Ceuta',
                'group' => 'Africa',
                'utc' => '(GMT/UTC +02:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Africa/Conakry',
                'group' => 'Africa',
                'utc' => '(GMT/UTC +00:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Africa/Dakar',
                'group' => 'Africa',
                'utc' => '(GMT/UTC +00:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Africa/Dar_es_Salaam',
                'group' => 'Africa',
                'utc' => '(GMT/UTC +03:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Africa/Djibouti',
                'group' => 'Africa',
                'utc' => '(GMT/UTC +03:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Africa/Douala',
                'group' => 'Africa',
                'utc' => '(GMT/UTC +01:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Africa/El_Aaiun',
                'group' => 'Africa',
                'utc' => '(GMT/UTC +01:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Africa/Freetown',
                'group' => 'Africa',
                'utc' => '(GMT/UTC +00:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Africa/Gaborone',
                'group' => 'Africa',
                'utc' => '(GMT/UTC +02:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Africa/Harare',
                'group' => 'Africa',
                'utc' => '(GMT/UTC +02:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Africa/Johannesburg',
                'group' => 'Africa',
                'utc' => '(GMT/UTC +02:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Africa/Juba',
                'group' => 'Africa',
                'utc' => '(GMT/UTC +02:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Africa/Kampala',
                'group' => 'Africa',
                'utc' => '(GMT/UTC +03:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Africa/Khartoum',
                'group' => 'Africa',
                'utc' => '(GMT/UTC +02:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Africa/Kigali',
                'group' => 'Africa',
                'utc' => '(GMT/UTC +02:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Africa/Kinshasa',
                'group' => 'Africa',
                'utc' => '(GMT/UTC +01:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Africa/Lagos',
                'group' => 'Africa',
                'utc' => '(GMT/UTC +01:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Africa/Libreville',
                'group' => 'Africa',
                'utc' => '(GMT/UTC +01:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Africa/Lome',
                'group' => 'Africa',
                'utc' => '(GMT/UTC +00:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Africa/Luanda',
                'group' => 'Africa',
                'utc' => '(GMT/UTC +01:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Africa/Lubumbashi',
                'group' => 'Africa',
                'utc' => '(GMT/UTC +02:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Africa/Lusaka',
                'group' => 'Africa',
                'utc' => '(GMT/UTC +02:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Africa/Malabo',
                'group' => 'Africa',
                'utc' => '(GMT/UTC +01:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Africa/Maputo',
                'group' => 'Africa',
                'utc' => '(GMT/UTC +02:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Africa/Maseru',
                'group' => 'Africa',
                'utc' => '(GMT/UTC +02:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Africa/Mbabane',
                'group' => 'Africa',
                'utc' => '(GMT/UTC +02:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Africa/Mogadishu',
                'group' => 'Africa',
                'utc' => '(GMT/UTC +03:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Africa/Monrovia',
                'group' => 'Africa',
                'utc' => '(GMT/UTC +00:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Africa/Nairobi',
                'group' => 'Africa',
                'utc' => '(GMT/UTC +03:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Africa/Ndjamena',
                'group' => 'Africa',
                'utc' => '(GMT/UTC +01:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Africa/Niamey',
                'group' => 'Africa',
                'utc' => '(GMT/UTC +01:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Africa/Nouakchott',
                'group' => 'Africa',
                'utc' => '(GMT/UTC +00:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Africa/Ouagadougou',
                'group' => 'Africa',
                'utc' => '(GMT/UTC +00:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Africa/Porto-Novo',
                'group' => 'Africa',
                'utc' => '(GMT/UTC +01:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Africa/Sao_Tome',
                'group' => 'Africa',
                'utc' => '(GMT/UTC +00:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Africa/Tripoli',
                'group' => 'Africa',
                'utc' => '(GMT/UTC +02:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Africa/Tunis',
                'group' => 'Africa',
                'utc' => '(GMT/UTC +01:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Africa/Windhoek',
                'group' => 'Africa',
                'utc' => '(GMT/UTC +02:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Antarctica/Casey',
                'group' => 'Antarctica',
                'utc' => '(GMT/UTC +11:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Antarctica/Davis',
                'group' => 'Antarctica',
                'utc' => '(GMT/UTC +07:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Antarctica/DumontDUrville',
                'group' => 'Antarctica',
                'utc' => '(GMT/UTC +10:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Antarctica/Macquarie',
                'group' => 'Antarctica',
                'utc' => '(GMT/UTC +10:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Antarctica/Mawson',
                'group' => 'Antarctica',
                'utc' => '(GMT/UTC +05:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Antarctica/McMurdo',
                'group' => 'Antarctica',
                'utc' => '(GMT/UTC +12:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Antarctica/Palmer',
                'group' => 'Antarctica',
                'utc' => '(GMT/UTC -03:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Antarctica/Rothera',
                'group' => 'Antarctica',
                'utc' => '(GMT/UTC -03:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Antarctica/Syowa',
                'group' => 'Antarctica',
                'utc' => '(GMT/UTC +03:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Antarctica/Troll',
                'group' => 'Antarctica',
                'utc' => '(GMT/UTC +02:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Antarctica/Vostok',
                'group' => 'Antarctica',
                'utc' => '(GMT/UTC +06:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Arctic/Longyearbyen',
                'group' => 'Arctic',
                'utc' => '(GMT/UTC +02:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Atlantic/Azores',
                'group' => 'Atlantic',
                'utc' => '(GMT/UTC +00:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Atlantic/Bermuda',
                'group' => 'Atlantic',
                'utc' => '(GMT/UTC -03:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Atlantic/Canary',
                'group' => 'Atlantic',
                'utc' => '(GMT/UTC +01:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Atlantic/Cape_Verde',
                'group' => 'Atlantic',
                'utc' => '(GMT/UTC -01:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Atlantic/Faroe',
                'group' => 'Atlantic',
                'utc' => '(GMT/UTC +01:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Atlantic/Madeira',
                'group' => 'Atlantic',
                'utc' => '(GMT/UTC +01:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Atlantic/Reykjavik',
                'group' => 'Atlantic',
                'utc' => '(GMT/UTC +00:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Atlantic/South_Georgia',
                'group' => 'Atlantic',
                'utc' => '(GMT/UTC -02:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Atlantic/St_Helena',
                'group' => 'Atlantic',
                'utc' => '(GMT/UTC +00:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Atlantic/Stanley',
                'group' => 'Atlantic',
                'utc' => '(GMT/UTC -03:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Pacific/Apia',
                'group' => 'Pacific',
                'utc' => '(GMT/UTC +13:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Pacific/Auckland',
                'group' => 'Pacific',
                'utc' => '(GMT/UTC +12:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Pacific/Bougainville',
                'group' => 'Pacific',
                'utc' => '(GMT/UTC +11:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Pacific/Chatham',
                'group' => 'Pacific',
                'utc' => '(GMT/UTC +12:45)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Pacific/Chuuk',
                'group' => 'Pacific',
                'utc' => '(GMT/UTC +10:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Pacific/Easter',
                'group' => 'Pacific',
                'utc' => '(GMT/UTC -06:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Pacific/Efate',
                'group' => 'Pacific',
                'utc' => '(GMT/UTC +11:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Pacific/Fakaofo',
                'group' => 'Pacific',
                'utc' => '(GMT/UTC +13:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Pacific/Fiji',
                'group' => 'Pacific',
                'utc' => '(GMT/UTC +12:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Pacific/Funafuti',
                'group' => 'Pacific',
                'utc' => '(GMT/UTC +12:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Pacific/Galapagos',
                'group' => 'Pacific',
                'utc' => '(GMT/UTC -06:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Pacific/Gambier',
                'group' => 'Pacific',
                'utc' => '(GMT/UTC -09:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Pacific/Guadalcanal',
                'group' => 'Pacific',
                'utc' => '(GMT/UTC +11:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Pacific/Guam',
                'group' => 'Pacific',
                'utc' => '(GMT/UTC +10:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Pacific/Honolulu',
                'group' => 'Pacific',
                'utc' => '(GMT/UTC -10:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Pacific/Kanton',
                'group' => 'Pacific',
                'utc' => '(GMT/UTC +13:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Pacific/Kiritimati',
                'group' => 'Pacific',
                'utc' => '(GMT/UTC +14:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Pacific/Kosrae',
                'group' => 'Pacific',
                'utc' => '(GMT/UTC +11:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Pacific/Kwajalein',
                'group' => 'Pacific',
                'utc' => '(GMT/UTC +12:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Pacific/Majuro',
                'group' => 'Pacific',
                'utc' => '(GMT/UTC +12:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Pacific/Marquesas',
                'group' => 'Pacific',
                'utc' => '(GMT/UTC -09:30)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Pacific/Midway',
                'group' => 'Pacific',
                'utc' => '(GMT/UTC -11:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Pacific/Nauru',
                'group' => 'Pacific',
                'utc' => '(GMT/UTC +12:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Pacific/Niue',
                'group' => 'Pacific',
                'utc' => '(GMT/UTC -11:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Pacific/Norfolk',
                'group' => 'Pacific',
                'utc' => '(GMT/UTC +11:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Pacific/Noumea',
                'group' => 'Pacific',
                'utc' => '(GMT/UTC +11:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Pacific/Pago_Pago',
                'group' => 'Pacific',
                'utc' => '(GMT/UTC -11:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Pacific/Palau',
                'group' => 'Pacific',
                'utc' => '(GMT/UTC +09:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Pacific/Pitcairn',
                'group' => 'Pacific',
                'utc' => '(GMT/UTC -08:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Pacific/Pohnpei',
                'group' => 'Pacific',
                'utc' => '(GMT/UTC +11:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Pacific/Port_Moresby',
                'group' => 'Pacific',
                'utc' => '(GMT/UTC +10:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Pacific/Rarotonga',
                'group' => 'Pacific',
                'utc' => '(GMT/UTC -10:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Pacific/Saipan',
                'group' => 'Pacific',
                'utc' => '(GMT/UTC +10:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Pacific/Tahiti',
                'group' => 'Pacific',
                'utc' => '(GMT/UTC -10:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Pacific/Tarawa',
                'group' => 'Pacific',
                'utc' => '(GMT/UTC +12:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Pacific/Tongatapu',
                'group' => 'Pacific',
                'utc' => '(GMT/UTC +13:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Pacific/Wake',
                'group' => 'Pacific',
                'utc' => '(GMT/UTC +12:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'Pacific/Wallis',
                'group' => 'Pacific',
                'utc' => '(GMT/UTC +12:00)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'timezone' => 'UTC',
                'group' => 'UTC',
                'utc' => '',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);
    }
}
