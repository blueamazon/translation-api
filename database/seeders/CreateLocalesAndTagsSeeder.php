<?php

namespace Database\Seeders;

use App\Models\Locale;
use App\Models\Tag;
use Illuminate\Database\Seeder;

class CreateLocalesAndTagsSeeder extends Seeder
{
    public function run()
    {
        // Locales
        $locales = [
            ['code' => 'en', 'name' => 'English'],
            ['code' => 'fr', 'name' => 'French'],
            ['code' => 'es', 'name' => 'Spanish'],
        ];

        foreach ($locales as $locale) {
            Locale::firstOrCreate(['code' => $locale['code']], $locale);
        }

        // Tags
        $tags = ['web', 'mobile', 'desktop', 'admin', 'email'];
        foreach ($tags as $name) {
            Tag::firstOrCreate(['name' => $name]);
        }
    }
}