<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

class SettingsService
{
    public function get(string $key, mixed $default = null): mixed
    {
        return Cache::remember('setting.' . $key, 3600, fn () => Setting::get($key, $default));
    }

    public function all(): array
    {
        return Cache::remember('settings.all', 3600, fn () => Setting::pluck('value', 'key')->toArray());
    }

    public function clear(): void
    {
        Cache::forget('settings.all');
        // Individual setting keys are forgotten on next get via Setting model
    }
}
