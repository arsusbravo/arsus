<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

// English routes (default, no prefix)
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('services', [HomeController::class, 'services'])->name('services');
Route::get('about-us', [HomeController::class, 'about'])->name('about');
Route::get('contact', [HomeController::class, 'contact'])->name('contact');
Route::post('contact', [HomeController::class, 'submitContact'])->name('contact.submit');
Route::get('cases', [HomeController::class, 'cases'])->name('cases');
Route::get('cases/{slug}', [HomeController::class, 'caseShow'])->name('case.show');

// Dutch routes (nl prefix)
Route::prefix('nl')->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('nl.home');
    Route::get('services', [HomeController::class, 'services'])->name('nl.services');
    Route::get('about-us', [HomeController::class, 'about'])->name('nl.about');
    Route::get('contact', [HomeController::class, 'contact'])->name('nl.contact');
    Route::post('contact', [HomeController::class, 'submitContact'])->name('nl.contact.submit');
    Route::get('cases', [HomeController::class, 'cases'])->name('nl.cases');
    Route::get('cases/{slug}', [HomeController::class, 'caseShow'])->name('nl.case.show');
});

// Indonesian routes (id prefix)
Route::prefix('id')->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('id.home');
    Route::get('services', [HomeController::class, 'services'])->name('id.services');
    Route::get('about-us', [HomeController::class, 'about'])->name('id.about');
    Route::get('contact', [HomeController::class, 'contact'])->name('id.contact');
    Route::post('contact', [HomeController::class, 'submitContact'])->name('id.contact.submit');
    Route::get('cases', [HomeController::class, 'cases'])->name('id.cases');
    Route::get('cases/{slug}', [HomeController::class, 'caseShow'])->name('id.case.show');
});
