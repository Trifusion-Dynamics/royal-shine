<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Category;
use App\Models\Collection;
use App\Models\Gallery;
use App\Models\Page;
use App\Models\Product;
use App\Models\Testimonial;
use App\Services\SettingsService;

class HomeController extends Controller
{
    public function __invoke(SettingsService $settings)
    {
        $banners = Banner::where('status', 'active')
            ->where(fn ($query) => $query->whereNull('starts_at')->orWhere('starts_at', '<=', now()))
            ->where(fn ($query) => $query->whereNull('ends_at')->orWhere('ends_at', '>=', now()))
            ->orderBy('sort_order')
            ->get();
        $categories = Category::where('status', 'active')->orderBy('sort_order')->get();
        $featuredProducts = Product::where('status', 'published')
            ->where('is_featured', true)
            ->with(['category', 'images'])
            ->orderBy('sort_order')
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
        $galleryItems = Gallery::where('status', 'active')->orderBy('sort_order')->take(6)->get();
        $testimonials = Testimonial::where('is_approved', true)->orderBy('sort_order')->take(3)->get();
        $aboutPage = Page::where('slug', 'about-us')->where('status', 'published')->first();

        return view('pages.home', compact(
            'banners',
            'categories',
            'featuredProducts',
            'newArrivals',
            'featuredCollections',
            'galleryItems',
            'testimonials',
            'aboutPage',
            'settings',
        ));
    }
}
