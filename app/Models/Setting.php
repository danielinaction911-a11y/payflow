<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'key',
        'value',
        'type',
        'group',
        'label',
        'description',
        'is_public',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'is_public' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    /**
     * Get a setting value by key, with automatic type casting.
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        $setting = static::where('key', $key)->first();

        if (! $setting) {
            return $default;
        }

        return match ($setting->type) {
            'boolean' => (bool) $setting->value,
            'number' => is_numeric($setting->value) ? $setting->value + 0 : $setting->value,
            'json' => json_decode($setting->value, true),
            default => $setting->value,
        };
    }

    /**
     * Set (create or update) a setting value and clear its cache.
     */
    public static function set(string $key, mixed $value): void
    {
        static::where('key', $key)->update(['value' => $value]);
        cache()->forget("setting:{$key}");
    }

    protected static function booted(): void
    {
        static::saved(fn($setting) => cache()->forget("setting:{$setting->key}"));
        static::deleted(fn($setting) => cache()->forget("setting:{$setting->key}"));
    }

    // SCOPES
    public function scopeGroup($query, $group)
    {
        return $query->where('group', $group);
    }

    public function getCastValueAttribute()
    {
        return match ($this->type) {
            'boolean' => (bool) $this->value,
            'number', 'integer' => is_numeric($this->value) ? $this->value + 0 : 0,
            'json', 'array' => json_decode($this->value, true) ?? [],
            default => $this->value,
        };
    }
}
