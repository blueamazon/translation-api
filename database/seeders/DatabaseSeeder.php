<?php

namespace Database\Seeders;

use App\Models\Locale;
use App\Models\Tag;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Translation\TranslationServiceProvider;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        $this->call(CreateLocalesAndTagsSeeder::class);

        // Optional: only run heavy seed in local/dev
        if (app()->isLocal() || app()->runningUnitTests()) {
            $this->call(TranslationSeeder::class);
        }
    }
}
