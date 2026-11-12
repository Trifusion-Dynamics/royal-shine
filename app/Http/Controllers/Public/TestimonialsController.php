<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use App\Services\SettingsService;

class TestimonialsController extends Controller
{
    public function __invoke(SettingsService $settings)
    {
        $testimonials = Testimonial::where('is_approved', true)
            ->orderBy('sort_order')
            ->get();

        return view('pages.testimonials', compact('testimonials', 'settings'));
    }
}
