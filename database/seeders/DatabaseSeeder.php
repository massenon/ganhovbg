<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;


use Database\Seeders\RoleSeeder;
use Database\Seeders\CountrySeeder;
use Database\Seeders\AdminSeeder;
use Database\Seeders\TitleSeeder;
use Database\Seeders\SettingTableSeeder;
use Database\Seeders\SeopageSeeder;
use Database\Seeders\FootertextSeeder;
use Database\Seeders\PagesSeeder;
use Database\Seeders\EmailTemplatesSeeder;
use Database\Seeders\Customcssjs;
use Database\Seeders\CustomerrorSeeder;
use Database\Seeders\ArticleCategorySeeder;
use Database\Seeders\TestimonialSeeder;
use Database\Seeders\FaqSeeder;
use Database\Seeders\LanguageSeeder;
use Database\Seeders\TranslationSeeder;
use Database\Seeders\Permissiongroupupdate;
use Database\Seeders\TimezoneSeeder;
use Database\Seeders\SettingUpdateSeeder;
use Database\Seeders\NewUpdateSeederV3_1;
use Modules\Uhelpupdate\Database\Seeders\UhelpupdateDatabaseSeeder;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        $this->call([

            RoleSeeder::class,
            CountrySeeder::class,
            AdminSeeder::class,
            TitleSeeder::class,
            SettingTableSeeder::class,
            SeopageSeeder::class,
            FootertextSeeder::class,
            // PagesSeeder::class,
            EmailTemplatesSeeder::class,
            Customcssjs::class,
            CustomerrorSeeder::class,
            ArticleCategorySeeder::class,
            TestimonialSeeder::class,
            FaqSeeder::class,
            SettingUpdateSeeder::class,
            TimezoneSeeder::class,
            UhelpupdateDatabaseSeeder::class,
            Permissiongroupupdate::class,
            LanguageSeeder::class,
            TranslationSeeder::class,

            // Next Update V3.1
            NewUpdateSeederV3_1::class,
        ]);
    }
}
