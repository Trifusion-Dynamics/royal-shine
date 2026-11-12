<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\CategoryController;
use App\Http\Controllers\Public\ProductController;
use App\Http\Controllers\Public\EnquiryController;

Route::get('/', HomeController::class)->name('home');

Route::get('/about', function () {
    return view('pages.about');
})->name('about');

Route::get('/gallery', function () {
    return view('pages.gallery');
})->name('gallery');

Route::get('/contact', function () {
    return view('pages.contact');
})->name('contact');

Route::get('/privacy', function () {
    return view('pages.privacy');
})->name('privacy');

Route::get('/terms', function () {
    return view('pages.terms');
})->name('terms');

Route::get('/jewellery/{slug}', CategoryController::class)->name('category.show');

Route::get('/product/{slug}', ProductController::class)->name('product.show');

Route::get('/enquiry/create', [EnquiryController::class, 'create'])->name('enquiry.create');
Route::post('/enquiry', [EnquiryController::class, 'store'])->name('enquiry.store');
Route::get('/enquiry/success', [EnquiryController::class, 'success'])->name('enquiry.success');
