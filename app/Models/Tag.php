<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property int $id
 * @property string $name
 */
class Tag extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
    ];

    /**
     * Get all translations tagged with this tag.
     */
    public function translations(): BelongsToMany
    {
        return $this->belongsToMany(Translation::class, 'translation_tag');
    }

    /**
     * Boot the model and make tag names unique on save.
     */
    protected static function boot(): void
    {
        parent::boot();

        static::saving(function ($tag) {
            $tag->name = strtolower(trim($tag->name));
        });
    }
}
