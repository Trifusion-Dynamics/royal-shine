<?php

namespace App\Http\Controllers\Public;

use App\Enums\GoldPurity;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Collection;
use App\Models\Product;
use App\Services\SettingsService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class CategoryController extends Controller
{
    public function __invoke(Request $request, string $slug, SettingsService $settings)
    {
        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'metal_type' => ['nullable', Rule::in(['gold', 'silver', 'artificial', 'other'])],
            'gold_purity' => ['nullable', Rule::in(array_column(GoldPurity::cases(), 'value'))],
            'collection_id' => ['nullable', Rule::exists('collections', 'id')->where('status', 'active')],
            'min_price' => ['nullable', 'numeric', 'min:0', 'max:999999999'],
            'max_price' => ['nullable', 'numeric', 'min:0', 'max:999999999'],
            'sort' => ['nullable', Rule::in(['latest', 'price_low', 'price_high'])],
            'page' => ['nullable', 'integer', 'min:1'],
        ]);

        if (
            isset($filters['min_price'], $filters['max_price'])
            && $filters['min_price'] > $filters['max_price']
        ) {
            throw ValidationException::withMessages([
                'max_price' => 'The maximum price must be greater than or equal to the minimum price.',
            ]);
        }

        $category = Category::where('slug', $slug)
            ->where('status', 'active')
            ->firstOrFail();
        $baseQuery = Product::where('category_id', $category->id)
            ->where('status', 'published');
        $metalTypes = (clone $baseQuery)->whereNotNull('metal_type')->distinct()->pluck('metal_type');
        $goldPurities = (clone $baseQuery)
            ->whereNotNull('gold_purity')
            ->distinct()
            ->pluck('gold_purity')
            ->map(fn (GoldPurity|string $purity): string => $purity instanceof GoldPurity ? $purity->value : $purity);
        $collections = Collection::where('status', 'active')
            ->whereHas('products', fn ($query) => $query->where('category_id', $category->id)->where('status', 'published'))
            ->orderBy('sort_order')
            ->get(['id', 'name']);
        $hasPrices = (clone $baseQuery)->whereNotNull('price')->exists();

        $query = (clone $baseQuery)->with(['category', 'images']);
        if (! empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($builder) use ($search): void {
                $builder->where('name', 'like', '%' . $search . '%')
                    ->orWhere('sku', 'like', '%' . $search . '%');
            });
        }

        foreach (['metal_type', 'gold_purity', 'collection_id'] as $filter) {
            if (! empty($filters[$filter])) {
                $query->where($filter, $filters[$filter]);
            }
        }
        if (isset($filters['min_price'])) {
            $query->where('price', '>=', $filters['min_price']);
        }
        if (isset($filters['max_price'])) {
            $query->where('price', '<=', $filters['max_price']);
        }

        $sort = $filters['sort'] ?? 'latest';
        if ($sort === 'price_low' && $hasPrices) {
            $query->whereNotNull('price')->orderBy('price');
        } elseif ($sort === 'price_high' && $hasPrices) {
            $query->whereNotNull('price')->orderByDesc('price');
        } else {
            $sort = 'latest';
            $query->latest('created_at');
        }

        $products = $query->paginate(12)->withQueryString();
        $seoTitle = $category->seo_title ?: $category->name;
        $metaDescription = $category->seo_description
            ?: ($category->description
                ? \Illuminate\Support\Str::limit(strip_tags($category->description), 160)
                : null);
        $canonicalUrl = route('category.show', $category->slug, absolute: true);
        $ogImage = $category->image
            ? app(\App\Services\ImageService::class)->url($category->image)
            : null;

        return view('pages.category', compact(
            'category',
            'products',
            'metalTypes',
            'goldPurities',
            'collections',
            'hasPrices',
            'sort',
            'seoTitle',
            'metaDescription',
            'canonicalUrl',
            'ogImage',
            'settings',
        ));
    }
}
