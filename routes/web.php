<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestimonialController;
use App\Models\Testimonial;
use App\Http\Controllers\QuestionsController;
use App\Http\Controllers\AnswerController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\ProfiRequestController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ServiceCategoryController;
use App\Http\Services\S3TestService;

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

// Admin Routes
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('dashboard');
    Route::get('/jobs', [AdminController::class, 'jobs'])->name('jobs');
    Route::get('/services', [AdminController::class, 'services'])->name('services');
    Route::delete('/jobs/{job}', [AdminController::class, 'destroyJob'])->name('jobs.destroy');
    Route::delete('/services/{service}', [AdminController::class, 'destroyService'])->name('services.destroy');
    
    // Service Categories Management
    Route::get('/categories', [ServiceCategoryController::class, 'index'])->name('categories');
    Route::post('/categories', [ServiceCategoryController::class, 'store'])->name('categories.store');
    Route::put('/categories/{category}', [ServiceCategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{category}', [ServiceCategoryController::class, 'destroy'])->name('categories.destroy');
});

// Testimonials API route
Route::get('/testimonials', [TestimonialController::class, 'getTestimonials']);
Route::delete('/testimonials/{testimonial}', [TestimonialController::class, 'destroy'])->name('testimonials.destroy');

Route::post('/testimonials', [TestimonialController::class, 'store'])->middleware('auth');
Route::get('/testimonials/load', [TestimonialController::class, 'getTestimonials']);

Route::get('/test-s3', function (S3TestService $s3Test) {
    try {
        $config = $s3Test->getS3Config();
        $tests = $s3Test->testS3Connection();
        
        return response()->json([
            'status' => 'success',
            'config' => $config,
            'tests' => $tests
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage(),
            'config' => $s3Test->getS3Config()
        ], 500);
    }
})->middleware('auth');

// Job management routes
Route::middleware(['auth'])->group(function () {
    // Dashboard and main job routes
    Route::get('/jobs', [JobController::class, 'index'])->name('jobs.index');
    Route::get('/jobs/create', [JobController::class, 'create'])->name('jobs.create');
    Route::post('/jobs', [JobController::class, 'store'])->name('jobs.store');
    Route::get('/jobs/{job}', [JobController::class, 'show'])->name('jobs.show');
    Route::get('/jobs/{job}/edit', [JobController::class, 'edit'])->name('jobs.edit');
    Route::put('/jobs/{job}', [JobController::class, 'update'])->name('jobs.update');
    Route::delete('/jobs/{job}', [JobController::class, 'destroy'])->name('jobs.destroy');
    
    // Admin job and service deletion routes
    Route::delete('/admin/jobs/{job}', [JobController::class, 'adminDestroyJob'])->name('admin.jobs.destroy');
    Route::delete('/admin/services/{service}', [JobController::class, 'adminDestroyService'])->name('admin.services.destroy');
    
    // Service management routes
    Route::get('/services', [ServiceController::class, 'index'])->name('services.index');
    Route::get('/services/create', [ServiceController::class, 'create'])->name('services.create');
    Route::post('/services', [ServiceController::class, 'store'])->name('services.store');
    Route::get('/services/{service}', [ServiceController::class, 'show'])->name('services.show');
    Route::get('/services/{service}/edit', [ServiceController::class, 'edit'])->name('services.edit');
    Route::put('/services/{service}', [ServiceController::class, 'update'])->name('services.update');
    Route::delete('/services/{service}', [ServiceController::class, 'destroy'])->name('services.destroy');
    
    // Job request routes (for professionals)
    Route::post('/jobs/{job}/request', [JobController::class, 'requestJob'])->name('jobs.request');
    Route::post('/job-requests/{jobRequest}/accept', [JobController::class, 'acceptJobRequest'])->name('job-requests.accept');
    Route::post('/job-requests/{jobRequest}/reject', [JobController::class, 'rejectJobRequest'])->name('job-requests.reject');
    
    // Service request routes (for users)
    Route::post('/services/{service}/request', [ServiceController::class, 'requestService'])->name('services.request');
    Route::post('/service-requests/{serviceRequest}/accept', [ServiceController::class, 'acceptServiceRequest'])->name('service-requests.accept');
    Route::post('/service-requests/{serviceRequest}/reject', [ServiceController::class, 'rejectServiceRequest'])->name('service-requests.reject');
    
    // Job completion
    Route::post('/jobs/{job}/complete', [JobController::class, 'completeJob'])->name('jobs.complete');
});

require __DIR__.'/auth.php';