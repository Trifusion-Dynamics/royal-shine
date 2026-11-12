<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\JewelleryController;
use App\Http\Controllers\Public\CategoryController;
use App\Http\Controllers\Public\ProductController;
use App\Http\Controllers\Public\SearchController;
use App\Http\Controllers\Public\PageController;
use App\Http\Controllers\Public\EnquiryController;
use App\Http\Controllers\Public\SitemapController;

Route::get('/', HomeController::class)->name('home');

// Jewellery discovery
Route::get('/jewellery', JewelleryController::class)->name('jewellery.index');
Route::get('/jewellery/{slug}', CategoryController::class)->name('category.show');

// Global product search
Route::get('/search', SearchController::class)->name('search');

// Product details
Route::get('/product/{slug}', ProductController::class)->name('product.show');

// Content pages (managed from the admin panel)
Route::get('/about-us', [PageController::class, 'about'])->name('about');
Route::get('/gallery', [PageController::class, 'gallery'])->name('gallery');
Route::get('/contact-us', [PageController::class, 'contact'])->name('contact');
Route::get('/privacy-policy', [PageController::class, 'privacy'])->name('privacy');
Route::get('/terms-and-conditions', [PageController::class, 'terms'])->name('terms');

// Enquiry
Route::get('/enquiry/create', [EnquiryController::class, 'create'])->name('enquiry.create');
Route::post('/enquiry', [EnquiryController::class, 'store'])
    ->middleware('throttle:10,1')
    ->name('enquiry.store');
Route::get('/enquiry/success', [EnquiryController::class, 'success'])->name('enquiry.success');

// SEO
Route::get('/sitemap.xml', SitemapController::class)->name('sitemap');
