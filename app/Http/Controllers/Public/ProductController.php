<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Services\ImageService;
use App\Services\SettingsService;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function __invoke(string $slug, SettingsService $settings, ImageService $images)
    {
        $product = Product::where('slug', $slug)
            ->where('status', 'published')
            ->with(['category', 'collection', 'images'])
            ->firstOrFail();
        $related = Product::where('category_id', $product->category_id)
            ->whereKeyNot($product->id)
            ->where('status', 'published')
            ->with(['category', 'images'])
            ->latest('created_at')
            ->take(4)
            ->get();
        $galleryImages = $product->images->sortByDesc('is_primary')->values();
        $primaryImage = $galleryImages->first();
        $imageUrls = $galleryImages
            ->map(fn ($image): string => $images->url($image->image))
            ->whenEmpty(fn ($urls) => $urls->push($images->url(null)))
            ->all();
        $metaTitle = $product->seo_title ?: $product->name;
        $metaDescription = $product->seo_description
            ?: Str::limit(strip_tags($product->description ?: $product->short_description ?: ''), 160);
        $canonicalUrl = route('product.show', $product->slug, absolute: true);
        $ogImage = $primaryImage ? $images->url($primaryImage->image) : null;
        $structuredData = [
            '@context' => 'https://schema.org',
            '@type' => 'Product',
            'name' => $product->name,
            'description' => strip_tags($product->description ?: $product->short_description ?: ''),
            'sku' => $product->sku,
            'category' => $product->category?->name,
            'image' => $imageUrls,
        ];

        if (! $product->price_on_request && $product->price !== null) {
            $structuredData['offers'] = [
                '@type' => 'Offer',
                'priceCurrency' => 'INR',
                'price' => $product->price,
                'url' => $canonicalUrl,
            ];
        }

        return view('pages.product', compact(
            'product',
            'related',
            'primaryImage',
            'galleryImages',
            'imageUrls',
            'metaTitle',
            'metaDescription',
            'canonicalUrl',
            'ogImage',
            'structuredData',
            'settings',
        ));
    }
}
