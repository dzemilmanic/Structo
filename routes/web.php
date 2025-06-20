<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestimonialController;
use App\Models\Testimonial;
use App\Http\Controllers\QuestionsController;
use App\Http\Controllers\AnswerController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\ProfiRequestController;

Route::get('/', function () {
    // This gets ALL testimonials, but we'll use JavaScript to paginate them client-side
    $testimonials = Testimonial::with('user')->latest()->get();
    
    // Calculate the number of pages needed for pagination
    $testimonialsPerPage = 2;
    $totalPages = ceil($testimonials->count() / $testimonialsPerPage);
    
    return view('home', compact('testimonials', 'totalPages'));
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    // Add password update route
    Route::put('/password', [ProfileController::class, 'updatePassword'])->name('password.update');
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/questions', [QuestionsController::class, 'index'])->name('questions.index');
Route::resource('questions', QuestionsController::class);
Route::resource('questions.answers', AnswerController::class)->shallow();

Route::resource('answers', AnswerController::class)->only([
    'store', 'edit', 'update', 'destroy'
]);

Route::post('/answers/{answer}/solution', [AnswerController::class, 'markAsSolution'])->name('answers.solution');

Route::get('/users', [UsersController::class, 'index'])->name('users.index');
Route::get('/users/{user}', [UsersController::class, 'show'])->name('users.show');

Route::middleware('auth')->group(function () {
    Route::post('/profi-requests', [ProfiRequestController::class, 'store'])->name('profi-requests.store');
});

Route::middleware(['auth', 'can:isAdmin'])->group(function () {
    Route::get('/admin/profi-requests', [ProfiRequestController::class, 'index'])->name('admin.profi-requests.index');
    Route::post('/admin/profi-requests/{id}/approve', [ProfiRequestController::class, 'approve'])->name('admin.profi-requests.approve');
    Route::post('/admin/profi-requests/{id}/reject', [ProfiRequestController::class, 'reject'])->name('admin.profi-requests.reject');
});

// Testimonials API route
Route::get('/testimonials', [TestimonialController::class, 'getTestimonials']);
Route::delete('/testimonials/{testimonial}', [TestimonialController::class, 'destroy'])->name('testimonials.destroy');

Route::post('/testimonials', [TestimonialController::class, 'store'])->middleware('auth');
Route::get('/testimonials/load', [TestimonialController::class, 'getTestimonials']);

require __DIR__.'/auth.php';