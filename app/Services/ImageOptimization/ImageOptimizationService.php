<?php

namespace App\Services\ImageOptimization;

use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Encoders\WebpEncoder;
use Intervention\Image\Encoders\AvifEncoder;
use App\Models\ImageOptimization;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use App\Services\ImageService;

class ImageOptimizationService
{
    protected ImageManager $manager;

    protected array $variants = [
        'thumbnail' => ['width' => 300, 'height' => 300, 'quality' => 80],
        'medium' => ['width' => 600, 'height' => 600, 'quality' => 85],
        'large' => ['width' => 1200, 'height' => 1200, 'quality' => 90],
        'zoom' => ['width' => 1800, 'height' => 1800, 'quality' => 92],
    ];

    public function __construct()
    {
        $this->manager = new ImageManager(new Driver());
    }

    public function optimize(string $originalPath): ImageOptimization
    {
        $record = ImageOptimization::updateOrCreate(
            ['original_path' => $originalPath],
            [
                'processing_status' => ImageOptimization::STATUS_PROCESSING,
                'error_message' => null,
            ]
        );

        try {
            $this->processImage($record, $originalPath);

            $record->processing_status = ImageOptimization::STATUS_COMPLETED;
            $record->error_message = null;
            $record->save();

            return $record;
        } catch (\Throwable $e) {
            $record->processing_status = ImageOptimization::STATUS_FAILED;
            $record->error_message = $e->getMessage();
            $record->save();

            throw $e;
        }
    }

    protected function processImage(ImageOptimization $record, string $originalPath): void
    {
        $imageService = app(ImageService::class);
        $url = $imageService->url($originalPath);

        // Convert URL to local path if possible
        $localPath = $this->urlToLocalPath($url, $originalPath);

        // Get image dimensions
        list($width, $height) = getimagesize($localPath);
        $record->width = $width;
        $record->height = $height;
        $record->mime_type = mime_content_type($localPath);

        // Get original file size
        $record->original_size = filesize($localPath);

        // Read original image
        $image = $this->manager->read($localPath);

        // Generate WebP
        $webpPath = $this->generateVariant($record, $image, 'webp', null);
        $record->webp_path = $webpPath;

        // Generate AVIF if supported
        if ($this->isAvifSupported()) {
            $avifPath = $this->generateVariant($record, $image, 'avif', null);
            $record->avif_path = $avifPath;
        }

        // Generate responsive variants
        foreach ($this->variants as $variantName => $config) {
            $variantImage = $this->manager->read($localPath);
            $variantImage->cover($config['width'], $config['height']);
            $variantImage->save($this->getVariantPath($record, $variantName));

            $pathKey = $variantName . '_path';
            $record->{$pathKey} = $this->getVariantPath($record, $variantName);
        }

        // Calculate optimized size (sum of all variants)
        $optimizedSize = 0;
        foreach (['webp_path', 'avif_path', 'thumbnail_path', 'medium_path', 'large_path'] as $pathKey) {
            $path = $record->{$pathKey};
            if ($path && Storage::disk(config('royal_shine.upload_disk'))->exists($path)) {
                $optimizedSize += Storage::disk(config('royal_shine.upload_disk'))->size($path);
            }
        }
        $record->optimized_size = $optimizedSize;
    }

    protected function urlToLocalPath(string $url, string $originalPath): string
    {
        // If the URL is a local asset URL, convert to local path
        if (str_starts_with($url, asset(''))) {
            $relativePath = str_replace(asset(''), '', $url);
            $localPath = public_path($relativePath);
            if (file_exists($localPath)) {
                return $localPath;
            }
        }

        // Try storage disk path
        $disk = Storage::disk(config('royal_shine.upload_disk'));
        if ($disk->exists($originalPath)) {
            return $disk->path($originalPath);
        }

        // Try storage/app/public directly
        $storagePath = storage_path('app/public/' . $originalPath);
        if (file_exists($storagePath)) {
            return $storagePath;
        }

        // Fallback to public path
        $publicPath = public_path($originalPath);
        if (file_exists($publicPath)) {
            return $publicPath;
        }

        return $url;
    }

    protected function generateVariant(ImageOptimization $record, \Intervention\Image\Image $image, string $format, ?array $resize): ?string
    {
        $disk = Storage::disk(config('royal_shine.upload_disk'));
        $pathInfo = pathinfo($record->original_path);
        $baseName = $pathInfo['filename'] . '_' . $format;

        if ($resize) {
            $baseName .= '_' . $resize['width'] . 'x' . $resize['height'];
            $image->cover($resize['width'], $resize['height']);
        }

        $path = 'optimized/' . $baseName . '.' . $format;

        // Ensure directory exists
        $diskRoot = Storage::disk(config('royal_shine.upload_disk'))->path('');
        $fullDir = rtrim($diskRoot, '/') . '/optimized';
        if (!is_dir($fullDir)) {
            mkdir($fullDir, 0755, true);
        }

        // Get the appropriate encoder
        $encoder = match ($format) {
            'webp' => new WebpEncoder(quality: $resize['quality'] ?? 85),
            'avif' => new AvifEncoder(quality: $resize['quality'] ?? 85),
            default => new WebpEncoder(quality: $resize['quality'] ?? 85),
        };

        // Encode to the target format
        $encoded = $image->encode($encoder);

        // Save to disk
        Storage::disk(config('royal_shine.upload_disk'))->put($path, (string) $encoded);

        return $path;
    }

    protected function getVariantPath(ImageOptimization $record, string $variantName): string
    {
        $pathInfo = pathinfo($record->original_path);
        $baseName = $pathInfo['filename'] . '_' . $variantName;
        $config = $this->variants[$variantName];
        $baseName .= '_' . $config['width'] . 'x' . $config['height'] . '.webp';

        return 'optimized/' . $baseName;
    }

    protected function isAvifSupported(): bool
    {
        return extension_loaded('imagick') || defined('IMAGETYPE_AVIF');
    }

    public function getOptimizationReport(): array
    {
        $total = ImageOptimization::count();
        $completed = ImageOptimization::where('processing_status', ImageOptimization::STATUS_COMPLETED)->count();
        $failed = ImageOptimization::where('processing_status', ImageOptimization::STATUS_FAILED)->count();
        $pending = ImageOptimization::where('processing_status', ImageOptimization::STATUS_PENDING)->count();

        $originalSize = ImageOptimization::where('processing_status', ImageOptimization::STATUS_COMPLETED)
            ->sum('original_size');
        $optimizedSize = ImageOptimization::where('processing_status', ImageOptimization::STATUS_COMPLETED)
            ->sum('optimized_size');

        return [
            'total' => $total,
            'completed' => $completed,
            'failed' => $failed,
            'pending' => $pending,
            'original_size' => $originalSize,
            'optimized_size' => $optimizedSize,
            'savings_percent' => $originalSize > 0 ? round((1 - ($optimizedSize / $originalSize)) * 100, 2) : 0,
        ];
    }
}
