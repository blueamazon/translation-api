<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Locale;
use App\Models\Tag;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Translation>
 */
class TranslationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $locales = Locale::pluck('id')->toArray();
        $tags = Tag::pluck('id')->toArray();

        $groups = ['auth', 'ui', 'errors', 'forms', 'navigation'];
        $keys = collect(range(1, 500))->map(fn($i) => "screen{$i}.button{$i}");

        return [
            'key' => $keys->random(),
            'group' => $groups[array_rand($groups)],
            'locale_id' => $locales[array_rand($locales)],
            'content' => Str::sentence(6),
        ];
    }
}
