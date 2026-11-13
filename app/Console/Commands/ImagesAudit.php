<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;
use App\Models\Category;
use App\Models\Banner;
use App\Models\Gallery;
use App\Models\Page;
use App\Models\Setting;
use App\Models\Testimonial;
use App\Models\ImageOptimization;
use App\Services\ImageService;

class ImagesAudit extends Command
{
    protected $signature = 'images:audit';
    protected $description = 'Audit all existing images and identify missing/broken references';

    public function handle(): int
    {
        $imageService = app(ImageService::class);
        $issues = [];

        // Audit product images
        $this->info('Auditing product images...');
        $products = Product::with('images')->get();
        foreach ($products as $product) {
            foreach ($product->images as $image) {
                $path = $image->image;
                $exists = $imageService->url($path) !== asset('images/placeholder.svg');
                if (!$exists) {
                    $issues[] = [
                        'table' => 'product_images',
                        'id' => $image->id,
                        'path' => $path,
                        'issue' => 'Image file not found',
                    ];
                }
            }

            // Check main product images via product_images relation already covers this
        }

        // Audit category images
        $this->info('Auditing category images...');
        $categories = Category::whereNotNull('image')->get();
        foreach ($categories as $category) {
            $path = $category->image;
            $exists = $imageService->url($path) !== asset('images/placeholder.svg');
            if (!$exists) {
                $issues[] = [
                    'table' => 'categories',
                    'id' => $category->id,
                    'path' => $path,
                    'issue' => 'Image file not found',
                ];
            }
        }

        // Audit banner images
        $this->info('Auditing banner images...');
        $banners = Banner::whereNotNull('image')->get();
        foreach ($banners as $banner) {
            $path = $banner->image;
            $exists = $imageService->url($path) !== asset('images/placeholder.svg');
            if (!$exists) {
                $issues[] = [
                    'table' => 'banners',
                    'id' => $banner->id,
                    'path' => $path,
                    'issue' => 'Image file not found',
                ];
            }
        }

        // Audit gallery images
        $this->info('Auditing gallery images...');
        $galleries = Gallery::whereNotNull('image')->get();
        foreach ($galleries as $gallery) {
            $path = $gallery->image;
            $exists = $imageService->url($path) !== asset('images/placeholder.svg');
            if (!$exists) {
                $issues[] = [
                    'table' => 'galleries',
                    'id' => $gallery->id,
                    'path' => $path,
                    'issue' => 'Image file not found',
                ];
            }
        }

        // Audit settings images (logo, favicon)
        $this->info('Auditing settings images...');
        $settings = Setting::whereIn('key', ['logo', 'favicon'])->whereNotNull('value')->get();
        foreach ($settings as $setting) {
            $path = $setting->value;
            $exists = $imageService->url($path) !== asset('images/placeholder.svg');
            if (!$exists) {
                $issues[] = [
                    'table' => 'settings',
                    'id' => $setting->id,
                    'path' => $path,
                    'issue' => 'Image file not found',
                ];
            }
        }

        // Audit testimonial images
        $this->info('Auditing testimonial images...');
        $testimonials = Testimonial::whereNotNull('image')->get();
        foreach ($testimonials as $testimonial) {
            $path = $testimonial->image;
            $exists = $imageService->url($path) !== asset('images/placeholder.svg');
            if (!$exists) {
                $issues[] = [
                    'table' => 'testimonials',
                    'id' => $testimonial->id,
                    'path' => $path,
                    'issue' => 'Image file not found',
                ];
            }
        }

        // Report
        $this->table(
            ['Table', 'ID', 'Path', 'Issue'],
            array_map(fn ($i) => [$i['table'], $i['id'], $i['path'], $i['issue']], $issues)
        );

        $this->info("\nTotal issues found: " . count($issues));
        $this->info("Images already optimized: " . ImageOptimization::where('processing_status', 'completed')->count());
        $this->info("Images pending optimization: " . ImageOptimization::where('processing_status', 'pending')->count());
        $this->info("Images failed optimization: " . ImageOptimization::where('processing_status', 'failed')->count());

        return count($issues) > 0 ? 1 : 0;
    }
}
