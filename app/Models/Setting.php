<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
        'group',
    ];

    protected static function booted(): void
    {
        static::saved(function (Setting $setting) {
            static::forgetCache($setting->key);
        });

        static::deleted(function (Setting $setting) {
            static::forgetCache($setting->key);
        });
    }

    public static function forgetCache(?string $key = null): void
    {
        Cache::forget('settings.all');

        if ($key !== null) {
            Cache::forget('setting.' . $key);
        }
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        $setting = static::where('key', $key)->first();

        return $setting?->value ?? $default;
    }

    public static function set(string $key, mixed $value, ?string $group = null): void
    {
        static::updateOrCreate(
            ['key' => $key],
            ['value' => $value, 'group' => $group]
        );
    }
}
