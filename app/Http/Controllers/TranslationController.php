<?php

namespace App\Http\Controllers;

use App\Models\Locale;
use App\Models\Tag;
use App\Models\Translation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;

class TranslationController extends Controller
{
    public function index()
    {
        return Translation::with(['locale', 'tags'])
            ->paginate(100);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'key' => 'required|string|max:255',
            'group' => 'nullable|string|max:100',
            'locale_id' => 'required|exists:locales,id',
            'content' => 'required|string',
            'tag_names' => 'nullable|array',
        ]);

        $translation = DB::transaction(function () use ($validated) {
            $t = Translation::create([
                'key' => $validated['key'],
                'group' => $validated['group'] ?? 'default',
                'locale_id' => $validated['locale_id'],
                'content' => $validated['content'],
            ]);

            if (!empty($validated['tag_names'])) {
                $tagIds = collect($validated['tag_names'])->map(function ($name) {
                    return Tag::firstOrCreate(['name' => $name])->id;
                });
                $t->tags()->attach($tagIds);
            }

            return $t->load('tags');
        });

        return response()->json($translation, 201);
    }

    public function show(Translation $translation)
    {
        return $translation->load('tags');
    }

    public function update(Request $request, Translation $translation)
    {
        $translation->update($request->validate([
            'content' => 'required|string',
            'tag_names' => 'nullable|array'
        ]));

        if ($request->has('tag_names')) {
            $tagIds = collect($request->tag_names)->map(fn($name) => Tag::firstOrCreate(['name' => $name])->id);
            $translation->tags()->sync($tagIds);
        }

        return $translation->load('tags');
    }

    public function destroy(Translation $translation)
    {
        $translation->delete();
        return response()->noContent();
    }

    public function export($locale)
    {
        $localeId = Cache::remember("locale_id_{$locale}", 3600, function () use ($locale) {
            return Locale::where('code', $locale)->value('id');
        });

        if (!$localeId) {
            return response()->json(['error' => 'Locale not found'], 404);
        }

        // Stream JSON for large datasets
        $response = new StreamedResponse(function () use ($localeId) {
            $handle = fopen('php://output', 'w');
            fwrite($handle, '[');

            Translation::where('locale_id', $localeId)
                ->with('tags:name') // only name
                ->orderBy('id')
                ->chunk(500, function ($translations, $page) use ($handle) {
                    foreach ($translations as $i => $t) {
                        if ($page > 1 || $i > 0) fwrite($handle, ',');
                        fwrite($handle, json_encode([
                            'key' => $t->key,
                            'group' => $t->group,
                            'content' => $t->content,
                            'tags' => $t->tags->pluck('name')
                        ]));
                    }
                });

            fwrite($handle, ']');
            fclose($handle);
        });

        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('X-Performance', 'Optimized Stream');
        return $response;
    }

    public function search(Request $request)
    {
        $query = Translation::query()->with('locale', 'tags');

        if ($key = $request->get('key')) {
            $query->where('key', 'like', "%{$key}%");
        }

        if ($content = $request->get('content')) {
            $query->where('content', 'like', "%{$content}%");
        }

        if ($tags = $request->get('tags')) {
            $tagIds = Tag::whereIn('name', is_array($tags) ? $tags : [$tags])->pluck('id');
            $query->whereHas('tags', function ($q) use ($tagIds) {
                $q->whereIn('tags.id', $tagIds);
            });
        }

        return $query->limit(100)->get();
    }
}