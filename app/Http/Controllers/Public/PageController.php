<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use App\Models\Page;
use App\Services\SettingsService;

class PageController extends Controller
{
    public function about(SettingsService $settings)
    {
        return view('pages.about', [
            'page' => $this->content('about-us'),
            'settings' => $settings,
        ]);
    }

    public function contact(SettingsService $settings)
    {
        return view('pages.contact', [
            'page' => $this->content('contact-us'),
            'settings' => $settings,
        ]);
    }

    public function gallery(SettingsService $settings)
    {
        $items = Gallery::where('status', 'active')
            ->orderBy('sort_order')
            ->get();

        return view('pages.gallery', [
            'items' => $items,
            'page' => $this->content('gallery'),
            'settings' => $settings,
        ]);
    }

    public function privacy(SettingsService $settings)
    {
        return view('pages.privacy', [
            'page' => $this->content('privacy-policy'),
            'settings' => $settings,
        ]);
    }

    public function terms(SettingsService $settings)
    {
        return view('pages.terms', [
            'page' => $this->content('terms-and-conditions'),
            'settings' => $settings,
        ]);
    }

    protected function content(string $slug): ?Page
    {
        return Page::where('slug', $slug)
            ->where('status', 'published')
            ->first();
    }
}
