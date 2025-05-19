<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestimonialController;
use App\Models\Testimonial;

Route::get('/', function () {
    $testimonials = Testimonial::with('user')->latest()->take(5)->get();
    return view('home', compact('testimonials'));
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/jobs', function () {
    return view('jobs');
});

Route::get('/contact', function () {
    return view('contact');
});

Route::get('/services', function () {
    return view('services.index');
});

Route::get('/services/{type}', function ($type) {
    return view('services.show', compact('type'));
});

Route::view('/privacy', 'policy.privacy')->name('privacy');
Route::view('/terms', 'policy.terms')->name('terms');
Route::view('/cookies', 'policy.cookies')->name('cookies');
Route::view('/users', 'users')->name('users');



Route::post('/testimonials', [TestimonialController::class, 'store'])->middleware('auth');

require __DIR__.'/auth.php';
