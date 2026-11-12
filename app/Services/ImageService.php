<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;

class ImageService
{
    public function url(?string $path): string
    {
        if (empty($path)) {
            return asset('images/placeholder.svg');
        }

        if (filter_var($path, FILTER_VALIDATE_URL)) {
            return $path;
        }

        $disk = Storage::disk(config('royal_shine.upload_disk'));

        if ($disk->exists($path)) {
            return $disk->url($path);
        }

        if (is_file(public_path($path))) {
            return asset($path);
        }

        return asset('images/placeholder.svg');
    }

    public function dimensions(?string $path): array
    {
        if (empty($path)) {
            return ['width' => 400, 'height' => 400];
        }

        $disk = Storage::disk(config('royal_shine.upload_disk'));

        if (! $disk->exists($path)) {
            return ['width' => 400, 'height' => 400];
        }

        $size = getimagesizefromstring($disk->get($path));

        if ($size === false) {
            return ['width' => 400, 'height' => 400];
        }

        return ['width' => $size[0], 'height' => $size[1]];
    }
}
