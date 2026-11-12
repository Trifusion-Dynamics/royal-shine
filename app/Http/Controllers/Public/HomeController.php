<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Category;
use App\Models\Collection;
use App\Models\Product;
use App\Services\SettingsService;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    public function __invoke(SettingsService $settings)
    {
        $data = Cache::remember('home.page', 900, function () {
            $banners = Banner::where('status', 'active')
                ->orderBy('sort_order')
                ->get();

            $categories = Category::where('status', 'active')
                ->orderBy('sort_order')
                ->get();

            $featuredProducts = Product::where('status', 'published')
                ->where('is_featured', true)
                ->with(['category', 'images'])
                ->orderBy('sort_order', 'desc')
                ->take(8)
                ->get();

            $newArrivals = Product::where('status', 'published')
                ->with(['category', 'images'])
                ->latest('created_at')
                ->take(8)
                ->get();

            $featuredCollections = Collection::where('status', 'active')
                ->where('is_featured', true)
                ->orderBy('sort_order')
                ->take(4)
                ->get();

            return compact('banners', 'categories', 'featuredProducts', 'newArrivals', 'featuredCollections');
        });

        return view('pages.home', array_merge($data, ['settings' => $settings]));
    }
}
