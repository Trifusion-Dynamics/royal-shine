<?php

namespace App\Services\ImageOptimization;

use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
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

        if (!file_exists($localPath)) {
            throw new \Exception("File not found: {$localPath}");
        }

        // Get image dimensions
        list($width, $height) = getimagesize($localPath);
        $record->width = $width;
        $record->height = $height;
        $record->mime_type = mime_content_type($localPath);

        // Get original file size
        $record->original_size = (int) filesize($localPath);

        $disk = Storage::disk(config('royal_shine.upload_disk'));

        // Generate WebP (original size)
        $webpPath = $this->generateVariant($localPath, 'webp', null, null);
        $record->webp_path = $webpPath;

        // Generate AVIF if supported
        if ($this->isAvifSupported()) {
            $avifPath = $this->generateVariant($localPath, 'avif', null, null);
            $record->avif_path = $avifPath;
        }

        // Generate responsive variants
        foreach ($this->variants as $variantName => $config) {
            $variantPath = $this->generateVariant(
                $localPath,
                'webp',
                $variantName,
                ['width' => $config['width'], 'height' => $config['height'], 'quality' => $config['quality']]
            );
            $pathKey = $variantName . '_path';
            $record->{$pathKey} = $variantPath;
        }

        // Calculate optimized size
        $optimizedSize = 0;
        foreach (['webp_path', 'avif_path', 'thumbnail_path', 'medium_path', 'large_path'] as $pathKey) {
            $path = $record->{$pathKey};
            if ($path && $disk->exists($path)) {
                $optimizedSize += (int) $disk->size($path);
            }
        }
        $record->optimized_size = $optimizedSize;
    }

    protected function generateVariant(string $localPath, string $format, ?string $variantName, ?array $config): ?string
    {
        $disk = Storage::disk(config('royal_shine.upload_disk'));

        // Build filename
        $pathInfo = pathinfo($localPath);
        $baseName = $pathInfo['filename'];

        if ($variantName) {
            $baseName .= '_' . $variantName . '_' . $config['width'] . 'x' . $config['height'];
        }

        $baseName .= '_' . $format;
        $path = 'optimized/' . $baseName;

        // Ensure directory exists using PHP mkdir
        $diskRoot = $disk->path('');
        $fullDir = rtrim($diskRoot, '/') . '/optimized';
        if (!is_dir($fullDir)) {
            mkdir($fullDir, 0755, true);
        }

        // Read image fresh for each variant
        $image = $this->manager->read($localPath);

        // Apply resize if variant
        if ($variantName && $config) {
            $image->cover($config['width'], $config['height']);
        }

        // Encode and save using Intervention Image v3 API
        $encoder = match ($format) {
            'webp' => new \Intervention\Image\Encoders\WebpEncoder(quality: $config['quality'] ?? 85),
            'avif' => new \Intervention\Image\Encoders\AvifEncoder(quality: $config['quality'] ?? 85),
            default => new \Intervention\Image\Encoders\WebpEncoder(quality: $config['quality'] ?? 85),
        };

        $encoded = $image->encode($encoder);

        // Save using EncodedImage::save() or cast to string
        try {
            $encoded->save(Storage::disk(config('royal_shine.upload_disk'))->path($path));
        } catch (\Throwable $e) {
            // Fallback: cast to string
            Storage::disk(config('royal_shine.upload_disk'))->put($path, (string) $encoded);
        }

        return $path;
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
