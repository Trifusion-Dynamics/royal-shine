<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Services\SettingsService;
use App\Services\ImageService;
use Illuminate\Support\Facades\Cache;

class ProductController extends Controller
{
    public function __invoke(string $slug, SettingsService $settings, ImageService $images)
    {
        $cacheKey = 'product.' . $slug;

        $data = Cache::remember($cacheKey, 900, function () use ($slug, $settings, $images) {
            $product = Product::where('slug', $slug)
                ->where('status', 'published')
                ->with(['category', 'collection', 'images'])
                ->firstOrFail();

            $related = Product::where('category_id', $product->category_id)
                ->where('id', '!=', $product->id)
                ->where('status', 'published')
                ->with('images')
                ->latest('created_at')
                ->take(4)
                ->get();

            $primaryImage = $product->images()->where('is_primary', true)->first();
            $galleryImages = $product->images()->orderBy('sort_order')->get();

            $metaTitle = $product->seo_title ?: $product->name;
            $metaDescription = $product->seo_description ?: (strlen($product->description ?? '') > 160 ? substr(strip_tags($product->description), 0, 160) . '...' : ($product->description ?? ''));
            $canonicalUrl = route('product.show', $product->slug, absolute: true);
            $ogImage = $primaryImage ? $images->url($primaryImage->image) : null;

            return compact(
                'product',
                'related',
                'primaryImage',
                'galleryImages',
                'metaTitle',
                'metaDescription',
                'canonicalUrl',
                'ogImage'
            );
        });

        return view('pages.product', array_merge($data, ['settings' => $settings]));
    }
}
