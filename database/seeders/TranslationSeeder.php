<?php

namespace Database\Seeders;

use App\Models\Locale;
use App\Models\Tag;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TranslationSeeder extends Seeder
{
    public function run()
    {
        if (!Locale::exists() || !Tag::exists()) {
            $this->command->error('Locales or Tags missing! Run CreateLocalesAndTagsSeeder first.');
            return;
        }

        $localeIds = Locale::pluck('id')->toArray();
        $tagIds    = Tag::pluck('id')->toArray();

        $groups = ['auth', 'ui', 'forms', 'navigation', 'errors', 'dashboard', 'settings'];

        $total     = 100000;
        $chunkSize = 200;
        $bar       = $this->command->getOutput()->createProgressBar($total);
        $bar->start();

        for ($i = 1; $i <= $total; $i += $chunkSize) {
            $chunk = [];

            for ($j = 0; $j < $chunkSize; $j++) {
                $chunk[] = [
                    'key'       => "screen{$i}.element{$j}", // unique key
                    'group'     => $groups[array_rand($groups)],
                    'locale_id' => $localeIds[array_rand($localeIds)],
                    'content'   => fake()->sentence(rand(5, 12)),
                    'created_at' => now()->subDays(rand(0, 365)),
                    'updated_at' => now(),
                ];
            }

            DB::table('translations')->insert($chunk);

            // fetch inserted IDs safely
            $insertedIds = DB::table('translations')
                ->orderBy('id', 'desc')
                ->limit($chunkSize)
                ->pluck('id')
                ->toArray();

            $pivot = [];
            foreach ($insertedIds as $id) {
                foreach (collect($tagIds)->random(2) as $tagId) {
                    $pivot[] = ['translation_id' => $id, 'tag_id' => $tagId];
                }
            }

            DB::table('translation_tag')->insert($pivot);

            $bar->advance($chunkSize);
        }

        $bar->finish();
        $this->command->info("\n{$total} translations seeded with tags.");
    }
}
