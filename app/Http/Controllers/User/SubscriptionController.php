<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Package;
use App\Models\Subscription;
use App\Models\Transaction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class SubscriptionController extends Controller
{
    /**
     * Show subscription checkout page
     */
    public function checkout(Package $package): View
    {
        if (!$package->status) {
            abort(404, 'Package not found');
        }

        $package->load('tool');

        // Check if user already has active subscription
        $hasActiveSubscription = auth()->user()
            ->activeSubscriptions()
            ->whereHas('package', function ($query) use ($package) {
                $query->where('tool_id', $package->tool_id);
            })
            ->exists();

        return view('user.subscriptions.checkout', compact('package', 'hasActiveSubscription'));
    }

    /**
     * Process subscription
     */
    public function subscribe(Request $request, Package $package): RedirectResponse
    {
        $request->validate([
            'subdomain' => ['required', 'string', 'min:3', 'max:63', 'regex:/^[a-z0-9]([a-z0-9-]{0,61}[a-z0-9])?$/'],
            'payment_method' => ['required', 'in:stripe,paypal,bank_transfer,manual'],
        ]);

        try {
            DB::beginTransaction();

            $subdomain = strtolower($request->subdomain);

            // Check if subdomain already exists
            $exists = Subscription::where('subdomain', $subdomain)
                ->whereHas('package', function ($query) use ($package) {
                    $query->where('tool_id', $package->tool_id);
                })
                ->exists();

            if ($exists) {
                return back()
                    ->withInput()
                    ->with('error', 'This subdomain is already taken. Please choose another one.');
            }

            // Calculate expiry date
            $expiresAt = $this->calculateExpiryDate($package);

            // Create transaction
            $transaction = Transaction::create([
                'user_id' => auth()->id(),
                'package_id' => $package->id,
                'transaction_id' => 'TXN-' . strtoupper(uniqid()),
                'payment_method' => $request->payment_method,
                'amount' => $package->price,
                'currency' => 'EUR',
                'status' => $package->price == 0 ? 'completed' : 'pending',
                'metadata' => [
                    'subdomain' => $subdomain,
                    'package_name' => $package->name,
                    'tool_name' => $package->tool->name,
                ],
            ]);

            // Create subscription
            $subscription = Subscription::create([
                'user_id' => auth()->id(),
                'package_id' => $package->id,
                'transaction_id' => $transaction->id,
                'subdomain' => $subdomain,
                'starts_at' => now(),
                'expires_at' => $expiresAt,
                'status' => $package->price == 0 ? 'active' : 'pending',
                'admin_email' => auth()->user()->email, // Added from new controller
            ]);

            // Create tenant if free package or manual payment (new feature)
            if ($package->price == 0 || $request->payment_method === 'manual') {
                try {
                    $tenantData = $this->createTenantOnToolServer($subscription);
                    
                    DB::commit();
                    
                    return redirect()
                        ->route('user.subscriptions.success', $subscription)
                        ->with('success', 'Subscription activated successfully!')
                        ->with('tenant_credentials', $tenantData); // Added tenant credentials
                } catch (\Exception $e) {
                    DB::rollBack();
                    
                    \Log::error('Tenant creation failed: ' . $e->getMessage());
                    \Log::error('Subscription ID: ' . ($subscription->id ?? 'N/A'));
                    
                    return back()
                        ->withInput()
                        ->with('error', 'Failed to create tenant: ' . $e->getMessage());
                }
            }

            DB::commit();

            // Redirect based on payment method
            return match($request->payment_method) {
                'stripe' => redirect()->route('user.subscriptions.payment.stripe', $subscription),
                'paypal' => redirect()->route('user.subscriptions.payment.paypal', $subscription),
                'bank_transfer' => redirect()->route('user.subscriptions.payment.bank', $subscription),
                default => redirect()->route('user.subscriptions.payment', $subscription),
            };

        } catch (\Exception $e) {
            DB::rollBack();
            
            \Log::error('Subscription creation failed: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return back()
                ->withInput()
                ->with('error', 'Failed to create subscription: ' . $e->getMessage());
        }
    }

    /**
     * Create tenant on tool server (new method from new controller)
     */
    private function createTenantOnToolServer(Subscription $subscription): array
    {
        $tool = $subscription->package->tool;

        // Check tool connection
        if (!$tool->is_connected) {
            $tool->checkConnection();
        }

        if (!$tool->is_connected) {
            throw new \Exception('Tool server is not available');
        }

        // Generate credentials
        $tenantId = 'tenant_' . Str::uuid();
        $adminPassword = Str::random(16);

        // Create tenant via API
        $response = $tool->createTenant([
            'tenant_id' => $tenantId,
            'subdomain' => $subscription->subdomain,
            'subscription_id' => $subscription->id,
            'user_id' => $subscription->user_id,
            'admin_name' => $subscription->user->name,
            'admin_email' => $subscription->user->email,
            'admin_password' => $adminPassword,
            'package_name' => $subscription->package->name,
            'starts_at' => $subscription->starts_at->toIso8601String(),
            'expires_at' => $subscription->expires_at?->toIso8601String(),
            'metadata' => [
                'platform_subscription_id' => $subscription->id,
                'platform_user_id' => $subscription->user_id,
            ],
        ]);

        // Update subscription with tenant info
        $subscription->update([
            'tenant_id' => $tenantId,
            'tenant_database' => $response['data']['database'] ?? null,
            'admin_password' => encrypt($adminPassword),
            'is_tenant_active' => true,
            'tenant_created_at' => now(),
            'tenant_metadata' => $response,
        ]);

        return [
            'tenant_url' => $response['data']['login_url'] ?? null,
            'admin_email' => $subscription->user->email,
            'admin_password' => $adminPassword,
            'tenant_id' => $tenantId,
        ];
    }

    /**
     * Calculate expiry date based on package
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
     * Show all subscriptions (new method from new controller)
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
     * Show payment page
     */
    public function payment(Subscription $subscription): View
    {
        if ($subscription->user_id !== auth()->id()) {
            abort(403);
        }

        if ($subscription->status === 'active') {
            return redirect()->route('user.subscriptions.success', $subscription);
        }

        $subscription->load(['package.tool', 'transaction']);

        return view('user.subscriptions.payment', compact('subscription'));
    }

    /**
     * Show PayPal payment page
     */
    public function paymentPaypal(Subscription $subscription): View
    {
        if ($subscription->user_id !== auth()->id()) {
            abort(403);
        }

        if ($subscription->status === 'active') {
            return redirect()->route('user.subscriptions.success', $subscription);
        }

        $subscription->load(['package.tool', 'transaction']);

        return view('user.subscriptions.payment-paypal', compact('subscription'));
    }

    /**
     * Show Stripe payment page
     */
    public function paymentStripe(Subscription $subscription): View
    {
        if ($subscription->user_id !== auth()->id()) {
            abort(403);
        }

        if ($subscription->status === 'active') {
            return redirect()->route('user.subscriptions.success', $subscription);
        }

        $subscription->load(['package.tool', 'transaction']);

        return view('user.subscriptions.payment-stripe', compact('subscription'));
    }

    /**
     * Show bank transfer instructions
     */
    public function paymentBank(Subscription $subscription): View
    {
        if ($subscription->user_id !== auth()->id()) {
            abort(403);
        }

        $subscription->load(['package.tool', 'transaction']);

        return view('user.subscriptions.payment-bank', compact('subscription'));
    }
    
    /**
     * Show success page
     */
    public function success(Subscription $subscription): View
    {
        if ($subscription->user_id !== auth()->id()) {
            abort(403);
        }

        $subscription->load(['package.tool', 'transaction']);

        return view('user.subscriptions.success', compact('subscription'));
    }
}