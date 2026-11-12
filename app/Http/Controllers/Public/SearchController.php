<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Enums\GoldPurity;
use App\Models\Category;
use App\Models\Collection;
use App\Models\Product;
use App\Services\SettingsService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SearchController extends Controller
{
    public function __invoke(Request $request, SettingsService $settings)
    {
        $filters = $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
            'category_id' => ['nullable', Rule::exists('categories', 'id')->where('status', 'active')],
            'collection_id' => ['nullable', Rule::exists('collections', 'id')->where('status', 'active')],
            'metal_type' => ['nullable', Rule::in(['gold', 'silver', 'artificial', 'other'])],
            'gold_purity' => ['nullable', Rule::in(array_column(GoldPurity::cases(), 'value'))],
            'sort' => ['nullable', Rule::in(['latest', 'price_low', 'price_high'])],
            'page' => ['nullable', 'integer', 'min:1'],
        ]);
        $term = trim($filters['q'] ?? '');
        $categories = Category::where('status', 'active')->orderBy('sort_order')->get(['id', 'name']);
        $collections = Collection::where('status', 'active')->orderBy('sort_order')->get(['id', 'name']);

        $query = Product::where('status', 'published')
            ->when($term !== '', function ($query) use ($term) {
                $query->where(function ($query) use ($term) {
                    $query->where('name', 'like', '%' . $term . '%')
                        ->orWhere('sku', 'like', '%' . $term . '%')
                        ->orWhere('short_description', 'like', '%' . $term . '%');
                });
            })
            ->when(isset($filters['category_id']), fn ($query) => $query->where('category_id', $filters['category_id']))
            ->when(isset($filters['collection_id']), fn ($query) => $query->where('collection_id', $filters['collection_id']))
            ->when(isset($filters['metal_type']), fn ($query) => $query->where('metal_type', $filters['metal_type']))
            ->when(isset($filters['gold_purity']), fn ($query) => $query->where('gold_purity', $filters['gold_purity']));

        $sort = $filters['sort'] ?? 'latest';
        if ($sort === 'price_low') {
            $query->whereNotNull('price')->orderBy('price');
        } elseif ($sort === 'price_high') {
            $query->whereNotNull('price')->orderByDesc('price');
        } else {
            $sort = 'latest';
            $query->latest('created_at');
        }

        $products = $query->with(['category', 'images'])->paginate(12)->withQueryString();

        $seoTitle = $term !== '' ? 'Search: ' . $term : 'Search';
        $canonicalUrl = route('search', absolute: true);

        return view('pages.search', compact(
            'products',
            'term',
            'settings',
            'seoTitle',
            'canonicalUrl',
            'categories',
            'collections',
            'sort',
        ));
    }
}
