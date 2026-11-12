<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Collection;
use App\Models\Product;
use App\Services\SettingsService;

class CollectionsController extends Controller
{
    public function __invoke(SettingsService $settings)
    {
        $collections = Collection::where('status', 'active')
            ->orderBy('sort_order')
            ->get();
        $featuredProducts = Product::where('status', 'published')
            ->where('is_featured', true)
            ->with(['category', 'images'])
            ->latest('created_at')
            ->take(8)
            ->get();

        return view('pages.collections', compact('collections', 'featuredProducts', 'settings'));
    }

    public function show(string $slug, SettingsService $settings)
    {
        $collection = Collection::where('slug', $slug)
            ->where('status', 'active')
            ->firstOrFail();
        $products = $collection->products()
            ->where('status', 'published')
            ->with(['category', 'images'])
            ->latest('created_at')
            ->paginate(12);
        $seoTitle = $collection->seo_title ?: $collection->name;
        $metaDescription = $collection->seo_description
            ?: \Illuminate\Support\Str::limit(strip_tags($collection->description ?? ''), 160);
        $canonicalUrl = route('collection.show', $collection->slug, absolute: true);
        $ogImage = $collection->image
            ? app(\App\Services\ImageService::class)->url($collection->image)
            : null;

        return view('pages.collection', compact(
            'collection',
            'products',
            'settings',
            'seoTitle',
            'metaDescription',
            'canonicalUrl',
            'ogImage',
        ));
    }
}
