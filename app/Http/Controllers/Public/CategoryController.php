<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Services\SettingsService;
use Illuminate\Support\Facades\Cache;

class CategoryController extends Controller
{
    public function __invoke(Request $request, string $slug, SettingsService $settings)
    {
        $cacheKey = 'category.' . $slug . '.' . md5(serialize($request->all()));

        $data = Cache::remember($cacheKey, 900, function () use ($request, $slug, $settings) {
            $category = Category::where('slug', $slug)->where('status', 'active')->firstOrFail();

            $query = Product::where('category_id', $category->id)
                ->where('status', 'published')
                ->with(['category', 'images']);

            if ($request->filled('search')) {
                $search = $request->get('search');
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%')
                      ->orWhere('sku', 'like', '%' . $search . '%');
                });
            }

            $metalTypes = Product::where('category_id', $category->id)
                ->where('status', 'published')
                ->whereNotNull('metal_type')
                ->distinct()
                ->pluck('metal_type');

            $goldPurities = Product::where('category_id', $category->id)
                ->where('status', 'published')
                ->whereNotNull('gold_purity')
                ->distinct()
                ->pluck('gold_purity');

            if ($request->filled('metal_type')) {
                $query->where('metal_type', $request->get('metal_type'));
            }

            if ($request->filled('gold_purity')) {
                $query->where('gold_purity', $request->get('gold_purity'));
            }

            $sort = $request->get('sort', 'latest');
            $hasPrices = Product::where('category_id', $category->id)
                ->where('status', 'published')
                ->whereNotNull('price')
                ->exists();

            if ($sort === 'price_low' && $hasPrices) {
                $query->orderBy('price', 'asc');
            } elseif ($sort === 'price_high' && $hasPrices) {
                $query->orderBy('price', 'desc');
            } else {
                $query->latest('created_at');
                $sort = 'latest';
            }

            $products = $query->paginate(12)->withQueryString();

            $seoTitle = $category->seo_title ?: $category->name;
            $metaDescription = $category->seo_description
                ?: ($category->description
                    ? \Illuminate\Support\Str::limit(strip_tags($category->description), 160)
                    : null);
            $canonicalUrl = route('category.show', $category->slug, absolute: true);
            $ogImage = $category->image ? app(\App\Services\ImageService::class)->url($category->image) : null;

            return compact(
                'category',
                'products',
                'metalTypes',
                'goldPurities',
                'hasPrices',
                'sort',
                'seoTitle',
                'metaDescription',
                'canonicalUrl',
                'ogImage'
            );
        });

        return view('pages.category', array_merge($data, ['settings' => $settings]));
    }
}
