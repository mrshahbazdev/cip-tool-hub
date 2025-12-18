<?php

use App\Http\Controllers\Api\SubdomainController;
use App\Http\Controllers\User\SubscriptionController;
use App\Http\Controllers\User\ToolController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BlogController;
// Public Homepage
Route::get('/', function () {
    $tools = \App\Models\Tool::where('status', true)
        ->withCount('packages')
        ->latest()
        ->take(6)
        ->get();
    
    return view('welcome', compact('tools'));
})->name('home');

// Public Tools (no authentication required)
Route::get('/tools', [ToolController::class, 'index'])->name('tools.index');
Route::get('/tools/{tool}', [ToolController::class, 'show'])->name('tools.show');
Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{post:slug}', [BlogController::class, 'show'])->name('blog.show');
// Public API - Subdomain Checker (no auth required)
Route::post('/api/subdomain/check', [SubdomainController::class, 'checkAvailability'])
    ->name('api.subdomain.check');

// User Dashboard (Authentication required)
Route::middleware(['auth:sanctum', config('jetstream.auth_session')])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    
    // User subscriptions management
    Route::prefix('my')->name('user.')->group(function () {
        Route::get('/subscriptions', function () {
            $subscriptions = auth()->user()
                ->subscriptions()
                ->with(['package.tool'])
                ->latest()
                ->paginate(10);
            
            return view('user.subscriptions.index', compact('subscriptions'));
        })->name('subscriptions.index');
        
        // Subscribe to package
        Route::get('/subscribe/{package}', [SubscriptionController::class, 'checkout'])
            ->name('subscriptions.checkout');
        
        Route::post('/subscribe/{package}', [SubscriptionController::class, 'subscribe'])
            ->name('subscriptions.subscribe');
        
        // Payment methods
        Route::get('/subscription/{subscription}/payment/stripe', [SubscriptionController::class, 'paymentStripe'])
            ->name('subscriptions.payment.stripe');
        
        Route::get('/subscription/{subscription}/payment/paypal', [SubscriptionController::class, 'paymentPaypal'])
            ->name('subscriptions.payment.paypal');
        
        Route::get('/subscription/{subscription}/payment/bank', [SubscriptionController::class, 'paymentBank'])
            ->name('subscriptions.payment.bank');
        
        // Generic payment page
        Route::get('/subscription/{subscription}/payment', [SubscriptionController::class, 'payment'])
            ->name('subscriptions.payment');
        
        // Success page
        Route::get('/subscription/{subscription}/success', [SubscriptionController::class, 'success'])
            ->name('subscriptions.success');
    });
});