<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\CategoryController;
use App\Http\Controllers\Public\ProductController;
use App\Http\Controllers\Public\EnquiryController;
use App\Http\Controllers\Public\JewelleryController;
use App\Http\Controllers\Public\CollectionsController;
use App\Http\Controllers\Public\TestimonialsController;
use App\Http\Controllers\Public\PageController;
use App\Http\Controllers\Public\SearchController;
use App\Http\Controllers\Public\SitemapController;

Route::get('/', HomeController::class)->name('home');
Route::get('/about-us', [PageController::class, 'about'])->name('about');
Route::get('/gallery', [PageController::class, 'gallery'])->name('gallery');
Route::get('/contact-us', [PageController::class, 'contact'])->name('contact');
Route::post('/contact-us', [EnquiryController::class, 'contactStore'])->middleware('throttle.enquiry')->name('contact.store');
Route::get('/privacy-policy', [PageController::class, 'privacy'])->name('privacy');
Route::get('/terms-and-conditions', [PageController::class, 'terms'])->name('terms');

Route::get('/404', function () {
    return view('errors.404');
})->name('404');

Route::get('/jewellery', JewelleryController::class)->name('jewellery.index');
Route::get('/jewellery/{slug}', CategoryController::class)->name('category.show');
Route::get('/product/{slug}', ProductController::class)->name('product.show');
Route::get('/search', SearchController::class)->name('search');
Route::get('/sitemap.xml', SitemapController::class)->name('sitemap');
Route::get('/collections/{slug}', [CollectionsController::class, 'show'])->name('collection.show');

Route::get('/enquiry/create', [EnquiryController::class, 'create'])->name('enquiry.create');
Route::post('/enquiry', [EnquiryController::class, 'store'])->middleware('throttle.enquiry')->name('enquiry.store');

Route::get('/enquiry/success', [EnquiryController::class, 'success'])->name('enquiry.success');

Route::get('/collections', CollectionsController::class)->name('collections');

Route::get('/testimonials', TestimonialsController::class)->name('testimonials');

// Fallback 404 for any other missing routes
Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});
