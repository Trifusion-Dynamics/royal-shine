<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use InvalidArgumentException;

class UploadService
{
    public function __construct(
        protected string $disk = 'public',
        protected int $maxSizeKb = 2048,
        protected array $allowedMimes = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'],
    ) {}

    public static function fromConfig(): self
    {
        return new self(
            disk: config('royal_shine.upload_disk'),
            maxSizeKb: config('royal_shine.upload_max_size_kb'),
            allowedMimes: config('royal_shine.upload_allowed_mimes', [
                'image/jpeg',
                'image/png',
                'image/webp',
                'image/gif',
            ]),
        );
    }

    public function upload(UploadedFile $file, string $folder): string
    {
        $this->validate($file);

        $name = Str::uuid().'.'.$file->extension();

        $path = $file->storeAs($folder, $name, [
            'disk' => $this->disk,
        ]);

        return $path;
    }

    public function delete(string $path): void
    {
        Storage::disk($this->disk)->delete($path);
    }

    protected function validate(UploadedFile $file): void
    {
        $mime = $file->getMimeType();

        if (! in_array($mime, $this->allowedMimes, true)) {
            throw new InvalidArgumentException('Invalid file type.');
        }

        $sizeKb = $file->getSize() / 1024;

        if ($sizeKb > $this->maxSizeKb) {
            throw new InvalidArgumentException('File too large.');
        }
    }
}
