<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Services\SettingsService;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function __invoke(Request $request, SettingsService $settings)
    {
        $term = trim((string) $request->get('q'));

        $products = Product::where('status', 'published')
            ->when($term !== '', function ($query) use ($term) {
                $query->where(function ($query) use ($term) {
                    $query->where('name', 'like', '%' . $term . '%')
                        ->orWhere('sku', 'like', '%' . $term . '%')
                        ->orWhere('short_description', 'like', '%' . $term . '%');
                });
            })
            ->with(['category', 'images'])
            ->latest('created_at')
            ->paginate(12)
            ->withQueryString();

        $seoTitle = $term !== '' ? 'Search: ' . $term : 'Search';
        $canonicalUrl = route('search', absolute: true);

        return view('pages.search', compact('products', 'term', 'settings', 'seoTitle', 'canonicalUrl'));
    }
}
