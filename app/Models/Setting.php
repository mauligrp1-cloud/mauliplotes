<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = ['key', 'value', 'group', 'description'];

    /**
     * Get setting value by key with optional default and caching
     */
    public static function get(string $key, $default = null)
    {
        $val = Cache::rememberForever("setting_{$key}", function () use ($key, $default) {
            $setting = static::where('key', $key)->first();
            return $setting ? $setting->value : $default;
        });

        if (is_string($val) && preg_match('#^https?://(localhost|127\.0\.0\.1)(:\d+)?/storage/(.+)$#', $val, $matches)) {
            return '/storage/' . $matches[3];
        }

        return $val;
    }

    /**
     * Get JSON decoded setting value by key
     */
    public static function getJson(string $key, $default = [])
    {
        $val = static::get($key, null);
        if ($val === null) {
            return $default;
        }

        $decoded = json_decode($val, true);
        return is_array($decoded) ? $decoded : $default;
    }

    /**
     * Set or update setting value by key and clear cache
     */
    public static function set(string $key, $value, string $group = 'general', ?string $description = null)
    {
        if (is_string($value) && preg_match('#^https?://(localhost|127\.0\.0\.1)(:\d+)?/storage/(.+)$#', $value, $matches)) {
            $value = '/storage/' . $matches[3];
        }

        $setting = static::updateOrCreate(
            ['key' => $key],
            [
                'value' => $value,
                'group' => $group,
                'description' => $description ?? $key,
            ]
        );

        Cache::forget("setting_{$key}");
        return $setting;
    }

    /**
     * Set JSON encoded setting value by key
     */
    public static function setJson(string $key, $value, string $group = 'general', ?string $description = null)
    {
        return static::set($key, json_encode($value), $group, $description);
    }
}
