<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Languages;

class LanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Languages::create([
            'languagename' => 'English', 
            'languagenativename' => 'English',
            'is_rtl' => '0',
            'languagecode' => 'en',
        ]);
    }
}
