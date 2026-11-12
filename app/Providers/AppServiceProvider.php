<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Category;
use App\Models\Banner;
use App\Models\Collection;
use App\Models\Enquiry;
use App\Models\Gallery;
use App\Models\Page;
use App\Models\Product;
use App\Models\Setting;
use App\Models\Testimonial;
use App\Models\User;
use App\Observers\AdminActivityObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(\App\Services\SettingsService::class, function ($app) {
            return new \App\Services\SettingsService();
        });

        $this->app->singleton(\App\Services\ImageService::class, function ($app) {
            return new \App\Services\ImageService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        foreach ([
            Banner::class,
            Category::class,
            Collection::class,
            Enquiry::class,
            Gallery::class,
            Page::class,
            Product::class,
            Setting::class,
            Testimonial::class,
            User::class,
        ] as $model) {
            $model::observe(AdminActivityObserver::class);
        }

        View::composer('*', function ($view) {
            $view->with('navCategories', Category::where('status', 'active')->orderBy('sort_order')->get());
        });
    }
}
