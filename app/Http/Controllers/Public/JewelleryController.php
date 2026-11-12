<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Services\SettingsService;

class JewelleryController extends Controller
{
    public function __invoke(SettingsService $settings)
    {
        $categories = Category::where('status', 'active')
            ->orderBy('sort_order')
            ->get();

        $featuredProducts = Product::where('status', 'published')
            ->where('is_featured', true)
            ->with(['category', 'images'])
            ->latest('created_at')
            ->take(8)
            ->get();

        return view('pages.jewellery', compact('categories', 'featuredProducts', 'settings'));
    }
}
