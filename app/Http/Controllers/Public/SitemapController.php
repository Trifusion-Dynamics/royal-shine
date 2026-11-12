<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function __invoke(): Response
    {
        $urls = [];

        $static = [
            ['name' => 'home', 'changefreq' => 'daily', 'priority' => '1.0'],
            ['name' => 'jewellery.index', 'changefreq' => 'weekly', 'priority' => '0.9'],
            ['name' => 'about', 'changefreq' => 'monthly', 'priority' => '0.5'],
            ['name' => 'gallery', 'changefreq' => 'monthly', 'priority' => '0.5'],
            ['name' => 'contact', 'changefreq' => 'monthly', 'priority' => '0.5'],
            ['name' => 'privacy', 'changefreq' => 'yearly', 'priority' => '0.3'],
            ['name' => 'terms', 'changefreq' => 'yearly', 'priority' => '0.3'],
        ];

        foreach ($static as $entry) {
            $urls[] = [
                'loc' => route($entry['name']),
                'changefreq' => $entry['changefreq'],
                'priority' => $entry['priority'],
                'lastmod' => now()->toAtomString(),
            ];
        }

        Category::where('status', 'active')
            ->orderBy('sort_order')
            ->get()
            ->each(function (Category $category) use (&$urls) {
                $urls[] = [
                    'loc' => route('category.show', $category->slug),
                    'changefreq' => 'weekly',
                    'priority' => '0.8',
                    'lastmod' => $category->updated_at?->toAtomString() ?? now()->toAtomString(),
                ];
            });

        Product::where('status', 'published')
            ->orderBy('updated_at', 'desc')
            ->get()
            ->each(function (Product $product) use (&$urls) {
                $urls[] = [
                    'loc' => route('product.show', $product->slug),
                    'changefreq' => 'weekly',
                    'priority' => '0.7',
                    'lastmod' => $product->updated_at?->toAtomString() ?? now()->toAtomString(),
                ];
            });

        return response()
            ->view('sitemap', compact('urls'))
            ->header('Content-Type', 'application/xml');
    }
}
