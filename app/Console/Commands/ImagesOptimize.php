<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ImageOptimization;
use App\Services\ImageOptimization\ImageOptimizationService;
use App\Models\Product;
use App\Models\Category;
use App\Models\Banner;
use App\Models\Gallery;
use App\Models\Setting;
use App\Models\Testimonial;
use App\Services\ImageService;

class ImagesOptimize extends Command
{
    protected $signature = 'images:optimize {--all : Optimize all images} {--dry-run : Show what would be optimized without actually doing it}';
    protected $description = 'Optimize existing images (WebP, AVIF, responsive variants)';

    public function handle(): int
    {
        $dryRun = $this->option('dry-run');
        $optimizeAll = $this->option('all');

        if ($dryRun) {
            $this->info('DRY RUN - No images will be actually optimized');
        }

        $imageService = app(ImageService::class);
        $paths = $this->collectImagePaths($imageService);

        $this->info("Found " . count($paths) . " unique image paths to optimize");

        if ($dryRun) {
            foreach ($paths as $path) {
                $alreadyOptimized = ImageOptimization::where('original_path', $path)
                    ->where('processing_status', 'completed')
                    ->exists();
                $status = $alreadyOptimized ? 'already optimized' : 'would optimize';
                $this->line("  [{$status}] {$path}");
            }
            return 0;
        }

        $service = app(ImageOptimizationService::class);
        $bar = $this->output->createProgressBar(count($paths));
        $bar->start();

        foreach ($paths as $path) {
            $alreadyOptimized = ImageOptimization::where('original_path', $path)
                ->where('processing_status', 'completed')
                ->exists();

            if (!$alreadyOptimized) {
                try {
                    $service->optimize($path);
                } catch (\Throwable $e) {
                    $this->error("\nFailed to optimize {$path}: " . $e->getMessage());
                }
            }

            $bar->advance();
        }

        $bar->finish();

        // Show report
        $report = $service->getOptimizationReport();
        $this->info("\nOptimization Report:");
        $this->table(
            ['Metric', 'Value'],
            [
                ['Total images', $report['total']],
                ['Completed', $report['completed']],
                ['Failed', $report['failed']],
                ['Pending', $report['pending']],
                ['Original size (bytes)', $report['original_size']],
                ['Optimized size (bytes)', $report['optimized_size']],
                ['Savings (%)', $report['savings_percent'] . '%'],
            ]
        );

        return 0;
    }

    protected function collectImagePaths(ImageService $imageService): array
    {
        $paths = [];

        // Product images
        foreach (Product::with('images')->get() as $product) {
            foreach ($product->images as $image) {
                if ($image->image) {
                    $paths[$image->image] = $image->image;
                }
            }
        }

        // Category images
        foreach (Category::whereNotNull('image')->get() as $category) {
            if ($category->image) {
                $paths[$category->image] = $category->image;
            }
        }

        // Banner images
        foreach (Banner::whereNotNull('image')->get() as $banner) {
            if ($banner->image) {
                $paths[$banner->image] = $banner->image;
            }
        }

        // Gallery images
        foreach (Gallery::whereNotNull('image')->get() as $gallery) {
            if ($gallery->image) {
                $paths[$gallery->image] = $gallery->image;
            }
        }

        // Settings images (logo, favicon)
        foreach (Setting::whereIn('key', ['logo', 'favicon'])->whereNotNull('value')->get() as $setting) {
            if ($setting->value) {
                $paths[$setting->value] = $setting->value;
            }
        }

        // Testimonial images
        foreach (Testimonial::whereNotNull('image')->get() as $testimonial) {
            if ($testimonial->image) {
                $paths[$testimonial->image] = $testimonial->image;
            }
        }

        return array_values($paths);
    }
}
