<?php

use App\Http\Controllers\Api\SubdomainController;
use App\Http\Controllers\User\SubscriptionController;
use App\Http\Controllers\User\ToolController;
use App\Http\Controllers\BlogController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;

// Public Homepage
Route::get('/', function () {
    $tools = \App\Models\Tool::where('status', true)
        ->withCount('packages')
        ->latest()
        ->take(6)
        ->get();
    
    return view('welcome', compact('tools'));
})->name('home');

// Public Blog & Tools (No auth required)
Route::get('/tools', [ToolController::class, 'index'])->name('tools.index');
Route::get('/tools/{tool}', [ToolController::class, 'show'])->name('tools.show');
Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{post:slug}', [BlogController::class, 'show'])->name('blog.show');
/**
 * Public Dynamic Pages Route
 * This captures slugs created in the admin panel and routes them to the PageController.
 * Example: domain.com/p/privacy-policy
 */
Route::get('/p/{page:slug}', [PageController::class, 'show'])->name('pages.show');
// Public API
Route::post('/api/subdomain/check', [SubdomainController::class, 'checkAvailability'])->name('api.subdomain.check');

// User Dashboard (Authenticated Users)
Route::middleware(['auth:sanctum', config('jetstream.auth_session')])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    
    // Protected User routes
    Route::prefix('my')->name('user.')->group(function () {
        Route::get('/subscriptions', function () {
            $subscriptions = auth()->user()
                ->subscriptions()
                ->with(['package.tool'])
                ->latest()
                ->paginate(10);
            return view('user.subscriptions.index', compact('subscriptions'));
        })->name('subscriptions.index');

        Route::get('/subscribe/{package}', [SubscriptionController::class, 'checkout'])->name('subscriptions.checkout');
        Route::post('/subscribe/{package}', [SubscriptionController::class, 'subscribe'])->name('subscriptions.subscribe');
        Route::get('/subscription/{subscription}/payment', [SubscriptionController::class, 'payment'])->name('subscriptions.payment');
        Route::get('/subscription/{subscription}/success', [SubscriptionController::class, 'success'])->name('subscriptions.success');
        Route::get('/subscription/{subscription}/upgrade', [SubscriptionController::class, 'upgrade'])->name('subscriptions.upgrade');
    });

    /**
     * Optional: If you have custom admin routes in web.php, 
     * wrap them in the 'admin' middleware here.
     */
    Route::middleware(['admin'])->prefix('admin-custom')->group(function() {
        // Custom admin routes
    });
});