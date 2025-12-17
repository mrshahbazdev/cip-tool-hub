<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Package;
use App\Models\Subscription;
use App\Models\Transaction;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Illuminate\Support\Arr;

class SubscriptionController extends Controller
{
    use AuthorizesRequests;
    /**
     * Show all user subscriptions
     */
    public function index(): View
    {
        $subscriptions = auth()->user()
            ->subscriptions()
            ->with(['package.tool', 'transaction'])
            ->latest()
            ->paginate(10);

        return view('user.subscriptions.index', compact('subscriptions'));
    }

    /**
     * Show subscription checkout page
     */
    public function checkout(Package $package): View
    {
        if (!$package->status) {
            abort(404, 'Package not found or inactive');
        }

        $package->load('tool');

        // Check if user already has active subscription for this tool
        $hasActiveSubscription = auth()->user()
            ->activeSubscriptions()
            ->whereHas('package', function ($query) use ($package) {
                $query->where('tool_id', $package->tool_id);
            })
            ->exists();

        // Get existing subdomain if user has one
        $existingSubscription = auth()->user()
            ->subscriptions()
            ->whereHas('package', function ($query) use ($package) {
                $query->where('tool_id', $package->tool_id);
            })
            ->latest()
            ->first();

        $suggestedSubdomain = $existingSubscription?->subdomain ?? 
                             Str::slug(auth()->user()->name) . random_int(100, 999);

        return view('user.subscriptions.checkout', compact(
            'package', 
            'hasActiveSubscription',
            'suggestedSubdomain'
        ));
    }

    /**
     * Process subscription
     */
    public function subscribe(Request $request, Package $package): RedirectResponse
    {
        $request->validate([
            'subdomain' => [
                'required', 
                'string', 
                'min:3', 
                'max:63', 
                'regex:/^[a-z0-9]([a-z0-9-]{0,61}[a-z0-9])?$/',
                'not_in:www,mail,ftp,admin,api,app,dev,test,staging,production'
            ],
            'payment_method' => ['required', 'in:stripe,paypal,bank_transfer,manual,free'],
        ]);

        try {
            DB::beginTransaction();

            $user = auth()->user();
            $subdomain = strtolower($request->subdomain);

            // Check if subdomain already exists for this tool
            $subdomainExists = Subscription::where('subdomain', $subdomain)
                ->whereHas('package', function ($query) use ($package) {
                    $query->where('tool_id', $package->tool_id);
                })
                ->exists();

            if ($subdomainExists) {
                return back()
                    ->withInput()
                    ->with('error', 'This subdomain is already taken. Please choose another one.');
            }

            // Calculate expiry date
            $expiresAt = $this->calculateExpiryDate($package);

            // Determine payment status
            $paymentStatus = ($package->price == 0 || $request->payment_method === 'free') 
                ? 'completed' 
                : 'pending';

            // Create transaction
            $transaction = Transaction::create([
                'user_id' => $user->id,
                'package_id' => $package->id,
                'transaction_id' => 'TXN-' . strtoupper(uniqid()),
                'payment_method' => $request->payment_method === 'free' ? 'manual' : $request->payment_method,
                'amount' => $package->price,
                'currency' => 'EUR',
                'status' => $paymentStatus,
                'metadata' => [
                    'subdomain' => $subdomain,
                    'package_name' => $package->name,
                    'tool_name' => $package->tool->name,
                    'user_email' => $user->email,
                ],
            ]);

            // Create subscription
            $subscription = Subscription::create([
                'user_id' => $user->id,
                'package_id' => $package->id,
                'transaction_id' => $transaction->id,
                'subdomain' => $subdomain,
                'starts_at' => now(),
                'expires_at' => $expiresAt,
                'status' => $paymentStatus === 'completed' ? 'active' : 'pending',
                'admin_email' => $user->email,
            ]);

            // Auto-activate for free packages - FIX IS HERE
            if ($paymentStatus === 'completed') {
                try {
                    Log::info('Creating tenant for free subscription', [
                        'user_id' => $user->id,
                        'subscription_id' => $subscription->id,
                        'subdomain' => $subdomain,
                    ]);
                    
                    // Create tenant on tool server - NO PASSWORD NEEDED
                    $tenantData = $this->createTenantOnToolServer($subscription);
                    
                    DB::commit();
                    
                    Log::info('Subscription and tenant created successfully', [
                        'subscription_id' => $subscription->id,
                        'tenant_id' => $tenantData['tenant_id'] ?? null,
                        'subdomain' => $subscription->subdomain,
                    ]);
                    
                    return redirect()
                        ->route('user.subscriptions.success', $subscription)
                        ->with('success', 'Subscription activated successfully! Login with your main platform credentials.')
                        ->with('tenant_credentials', $tenantData);
                        
                } catch (\Exception $e) {
                    DB::rollBack();
                    
                    Log::error('Tenant creation failed', [
                        'subscription_id' => $subscription->id ?? null,
                        'user_id' => $user->id,
                        'subdomain' => $subdomain,
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString(),
                    ]);
                    
                    return back()
                        ->withInput()
                        ->with('error', 'Failed to create tenant: ' . $e->getMessage());
                }
            }

            DB::commit();

            // Redirect to payment based on method
            return match($request->payment_method) {
                'stripe' => redirect()->route('user.subscriptions.payment.stripe', $subscription),
                'paypal' => redirect()->route('user.subscriptions.payment.paypal', $subscription),
                'bank_transfer' => redirect()->route('user.subscriptions.payment.bank', $subscription),
                default => redirect()->route('user.subscriptions.payment', $subscription),
            };

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Subscription creation failed', [
                'user_id' => auth()->id(),
                'package_id' => $package->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            return back()
                ->withInput()
                ->with('error', 'Failed to create subscription: ' . $e->getMessage());
        }
    }
    /**
     * Create tenant on tool server via API
     */
    private function createTenantOnToolServer(Subscription $subscription): array
    {
        $tool = $subscription->package->tool;
        $user = $subscription->user;

        // Check tool connection
        if (!$tool->is_connected) {
            $tool->checkConnection();
        }

        if (!$tool->is_connected) {
            throw new \Exception('Tool server is not available. Please contact support.');
        }

        // Generate tenant ID
        $tenantId = 'tenant_' . Str::uuid();

        // Get hashed password directly from user database
        $hashedPassword = $user->password;

        // Prepare API request data
        $requestData = [
            'tenant_id' => $tenantId,
            'subdomain' => $subscription->subdomain,
            'subscription_id' => $subscription->id,
            'user_id' => $user->id,
            'admin_name' => $user->name,
            'admin_email' => $user->email,
            'admin_password_hash' => $hashedPassword, // Send hashed password
            'package_name' => $subscription->package->name,
            'starts_at' => $subscription->starts_at->toIso8601String(),
            'expires_at' => $subscription->expires_at?->toIso8601String(),
        ];

        Log::info('Creating tenant via API with hashed password', [
            'tool_url' => $tool->api_url,
            'tenant_id' => $tenantId,
            'subdomain' => $subscription->subdomain,
            'admin_email' => $user->email,
        ]);

        // Make API request
        $response = Http::timeout(30)
            ->withHeaders([
                'Authorization' => 'Bearer ' . $tool->api_token,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])
            ->post($tool->api_url . '/api/tenants/create', $requestData);

        if (!$response->successful()) {
            Log::error('Tenant creation API failed', [
                'status' => $response->status(),
                'response' => $response->body(),
                'request_data' => array_except($requestData, ['admin_password_hash']),
            ]);
            
            throw new \Exception('API request failed with status ' . $response->status());
        }

        $responseData = $response->json();

        if (!($responseData['success'] ?? false)) {
            throw new \Exception($responseData['message'] ?? 'Unknown error from tool server');
        }

        // ⚠️ IMPORTANT: Update subscription with tenant info
        $subscription->update([
            'tenant_id' => $tenantId,
            'is_tenant_active' => true,
            'tenant_created_at' => now(),
            'tenant_metadata' => $responseData,
        ]);

        Log::info('Subscription updated with tenant info', [
            'subscription_id' => $subscription->id,
            'tenant_id' => $tenantId,
            'is_tenant_active' => true,
        ]);

        // Build login URL
        $baseDomain = config('app.base_domain', 'ideenpipeline.de');
        $loginUrl = 'https://' . $subscription->subdomain . '.' . $baseDomain . 
                '/tenant/' . $tenantId . '/login';

        return [
            'tenant_id' => $tenantId,
            'tenant_url' => $loginUrl,
            'subdomain' => $subscription->subdomain,
            'domain' => $subscription->subdomain . '.' . $baseDomain,
            'admin_email' => $user->email,
            'message' => 'Use same email and password as your main account',
            'success' => true,
        ];
    }

    /**
     * Sync existing subscription to create tenant
     */
    public function syncTenant(Request $request, Subscription $subscription): RedirectResponse
    {
        if ($subscription->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access');
        }

        if ($subscription->tenant_id && $subscription->is_tenant_active) {
            return back()->with('info', 'Tenant already exists and is active.');
        }

        // Validate password input
        $request->validate([
            'password' => ['required', 'string', 'min:8'],
        ]);

        try {
            DB::beginTransaction();

            // Get password from request (user must provide it)
            $plainPassword = $request->password;
            
            // Verify password matches user's current password
            if (!Hash::check($plainPassword, auth()->user()->password)) {
                return back()
                    ->withInput()
                    ->with('error', 'The provided password does not match your current password.');
            }

            // Create tenant with user's actual password
            $tenantData = $this->createTenantOnToolServer($subscription, $plainPassword);

            DB::commit();

            Log::info('Tenant synced for existing subscription', [
                'subscription_id' => $subscription->id,
                'tenant_id' => $tenantData['tenant_id'],
                'user_id' => auth()->id(),
            ]);

            return redirect()
                ->route('user.subscriptions.show', $subscription)
                ->with('success', 'Tenant created successfully! You can now login with your current password.')
                ->with('tenant_credentials', $tenantData);

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Tenant sync failed', [
                'subscription_id' => $subscription->id,
                'user_id' => auth()->id(),
                'error' => $e->getMessage(),
            ]);

            return back()->with('error', 'Failed to create tenant: ' . $e->getMessage());
        }
    }

        /**

    /**
     * Calculate expiry date based on package duration
     */
    private function calculateExpiryDate(Package $package)
    {
        if ($package->duration_type === 'lifetime') {
            return null;
        }

        $starts = now();
        
        return match($package->duration_type) {
            'trial', 'days' => $starts->copy()->addDays($package->duration_value),
            'months' => $starts->copy()->addMonths($package->duration_value),
            'years' => $starts->copy()->addYears($package->duration_value),
            default => $starts->copy()->addDays(30),
        };
    }

    /**
     * Show payment page
     */
    public function payment(Subscription $subscription): View
    {
        $this->authorize('view', $subscription);

        if ($subscription->status === 'active') {
            return redirect()->route('user.subscriptions.success', $subscription);
        }

        $subscription->load(['package.tool', 'transaction']);

        return view('user.subscriptions.payment', compact('subscription'));
    }

    /**
     * Show Stripe payment page
     */
    public function paymentStripe(Subscription $subscription): View
    {
        $this->authorize('view', $subscription);

        if ($subscription->status === 'active') {
            return redirect()->route('user.subscriptions.success', $subscription);
        }

        $subscription->load(['package.tool', 'transaction']);

        return view('user.subscriptions.payment-stripe', compact('subscription'));
    }

    /**
     * Show PayPal payment page
     */
    public function paymentPaypal(Subscription $subscription): View
    {
        $this->authorize('view', $subscription);

        if ($subscription->status === 'active') {
            return redirect()->route('user.subscriptions.success', $subscription);
        }

        $subscription->load(['package.tool', 'transaction']);

        return view('user.subscriptions.payment-paypal', compact('subscription'));
    }

    /**
     * Show bank transfer instructions
     */
    public function paymentBank(Subscription $subscription): View
    {
        $this->authorize('view', $subscription);

        $subscription->load(['package.tool', 'transaction']);

        return view('user.subscriptions.payment-bank', compact('subscription'));
    }
    
    /**
     * Show subscription success page
     */
    public function success(Subscription $subscription): View
    {
        $this->authorize('view', $subscription);

        $subscription->load(['package.tool', 'transaction']);

        // Get tenant credentials from session if available
        $tenantCredentials = session('tenant_credentials');

        // Build login URL from subscription
        $loginUrl = null;
        if ($subscription->tenant_id) {
            $baseDomain = config('app.base_domain', 'ideenpipeline.de');
            $loginUrl = 'https://' . $subscription->subdomain . '.' . $baseDomain . 
                       '/tenant/' . $subscription->tenant_id . '/login';
        }

        return view('user.subscriptions.success', compact(
            'subscription', 
            'tenantCredentials',
            'loginUrl'
        ));
    }

    /**
     * Show single subscription details
     */
    public function show(Subscription $subscription): View
    {
        $this->authorize('view', $subscription);

        $subscription->load(['package.tool', 'transaction']);

        // Build tenant login URL if active
        $loginUrl = null;
        if ($subscription->tenant_id && $subscription->is_tenant_active) {
            $baseDomain = config('app.base_domain', 'ideenpipeline.de');
            $loginUrl = 'https://' . $subscription->subdomain . '.' . $baseDomain . 
                       '/tenant/' . $subscription->tenant_id . '/login';
        }

        return view('user.subscriptions.show', compact('subscription', 'loginUrl'));
    }

    /**
     * Cancel subscription
     */
    public function cancel(Request $request, Subscription $subscription): RedirectResponse
    {
        $this->authorize('update', $subscription);

        try {
            $subscription->update([
                'status' => 'cancelled',
                'is_tenant_active' => false,
            ]);

            // Notify tool server to deactivate tenant
            if ($subscription->tenant_id) {
                $tool = $subscription->package->tool;
                
                try {
                    Http::timeout(10)
                        ->withHeaders(['Authorization' => 'Bearer ' . $tool->api_token])
                        ->post($tool->api_url . '/api/tenants/' . $subscription->tenant_id . '/update-status', [
                            'status' => 'inactive',
                        ]);
                } catch (\Exception $e) {
                    Log::error('Failed to deactivate tenant on tool server', [
                        'tenant_id' => $subscription->tenant_id,
                        'error' => $e->getMessage(),
                    ]);
                }
            }

            return redirect()
                ->route('user.subscriptions.index')
                ->with('success', 'Subscription cancelled successfully');

        } catch (\Exception $e) {
            Log::error('Subscription cancellation failed', [
                'subscription_id' => $subscription->id,
                'error' => $e->getMessage(),
            ]);

            return back()->with('error', 'Failed to cancel subscription');
        }
    }
}