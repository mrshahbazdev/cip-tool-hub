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
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\View\View;

class SubscriptionController extends Controller
{
    use AuthorizesRequests;

    public function index(): View
    {
        $subscriptions = auth()->user()
            ->subscriptions()
            ->with(['package.tool', 'transaction'])
            ->latest()
            ->paginate(10);

        return view('user.subscriptions.index', compact('subscriptions'));
    }

    public function upgrade(Subscription $subscription): View
    {
        $this->authorize('view', $subscription);
        
        $tool = $subscription->package->tool->load('packages');
        $packages = $tool->packages->where('status', true)->where('id', '!=', $subscription->package_id);

        return view('user.subscriptions.upgrade', compact('subscription', 'tool', 'packages'));
    }

    public function checkout(Package $package): View
    {
        if (!$package->status) {
            abort(404);
        }

        $user = auth()->user();
        $package->load('tool');

        if ($package->price == 0) {
            $hasUsedFreeBefore = $user->subscriptions()
                ->whereHas('package', function ($query) use ($package) {
                    $query->where('tool_id', $package->tool_id)
                          ->where('price', 0);
                })->exists();

            if ($hasUsedFreeBefore) {
                return redirect()->route('tools.show', $package->tool)
                    ->with('error', 'You have already used the free plan for this tool. Please choose a premium plan.');
            }
        }

        $existingSubscription = $user->subscriptions()
            ->whereHas('package', function ($query) use ($package) {
                $query->where('tool_id', $package->tool_id);
            })
            ->whereIn('status', ['active', 'pending'])
            ->latest()
            ->first();

        $isUpgrade = $existingSubscription !== null;
        $suggestedSubdomain = $existingSubscription?->subdomain ?? 
                             Str::slug($user->name) . random_int(100, 999);

        return view('user.subscriptions.checkout', compact(
            'package', 
            'isUpgrade',
            'suggestedSubdomain',
            'existingSubscription'
        ));
    }

    public function subscribe(Request $request, Package $package): RedirectResponse
    {
        $user = auth()->user();

        if ($package->price == 0) {
            $hasUsedFreeBefore = $user->subscriptions()
                ->whereHas('package', function ($query) use ($package) {
                    $query->where('tool_id', $package->tool_id)
                          ->where('price', 0);
                })->exists();

            if ($hasUsedFreeBefore) {
                return redirect()->route('tools.show', $package->tool)
                    ->with('error', 'Unauthorized: Free plans are limited to one per user.');
            }
        }

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

            $subdomain = strtolower($request->subdomain);

            $oldSubscription = $user->subscriptions()
                ->whereHas('package', function ($query) use ($package) {
                    $query->where('tool_id', $package->tool_id);
                })
                ->where('status', 'active')
                ->first();

            $subdomainTakenByOther = Subscription::where('subdomain', $subdomain)
                ->whereHas('package', function ($query) use ($package) {
                    $query->where('tool_id', $package->tool_id);
                })
                ->where('user_id', '!=', $user->id)
                ->exists();

            if ($subdomainTakenByOther) {
                return back()->withInput()->with('error', 'This subdomain is already taken.');
            }

            $expiresAt = $this->calculateExpiryDate($package);
            $paymentStatus = ($package->price == 0 || $request->payment_method === 'free') ? 'completed' : 'pending';

            $transaction = Transaction::create([
                'user_id' => $user->id,
                'package_id' => $package->id,
                'transaction_id' => 'TXN-' . strtoupper(uniqid()),
                'payment_method' => $request->payment_method === 'free' ? 'manual' : $request->payment_method,
                'amount' => $package->price,
                'currency' => 'EUR',
                'status' => $paymentStatus,
                'metadata' => ['subdomain' => $subdomain, 'upgrade' => $oldSubscription ? true : false],
            ]);

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

            if ($oldSubscription && $paymentStatus === 'completed') {
                $oldSubscription->update(['status' => 'upgraded']);
            }

            if ($paymentStatus === 'completed') {
                $tenantData = $this->createTenantOnToolServer($subscription);
                DB::commit();
                return redirect()->route('user.subscriptions.success', $subscription)
                    ->with('success', 'Plan upgraded successfully!')
                    ->with('tenant_credentials', $tenantData);
            }

            DB::commit();

            return match($request->payment_method) {
                'stripe' => redirect()->route('user.subscriptions.payment.stripe', $subscription),
                'paypal' => redirect()->route('user.subscriptions.payment.paypal', $subscription),
                'bank_transfer' => redirect()->route('user.subscriptions.payment.bank', $subscription),
                default => redirect()->route('user.subscriptions.payment', $subscription),
            };

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Subscription Error: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Operation failed: ' . $e->getMessage());
        }
    }

    private function calculateExpiryDate(Package $package)
    {
        if ($package->duration_type === 'lifetime') return null;
        $starts = now();
        return match($package->duration_type) {
            'trial', 'days' => $starts->copy()->addDays($package->duration_value),
            'months' => $starts->copy()->addMonths($package->duration_value),
            'years' => $starts->copy()->addYears($package->duration_value),
            default => $starts->copy()->addDays(30),
        };
    }

    private function createTenantOnToolServer(Subscription $subscription): array
    {
        $tool = $subscription->package->tool;
        $user = $subscription->user;

        if (!$tool->is_connected) { $tool->checkConnection(); }
        if (!$tool->is_connected) { throw new \Exception('Tool server is not available'); }

        $tenantId = 'tenant_' . Str::uuid();
        $hashedPassword = $user->password;

        $requestData = [
            'tenant_id' => $tenantId,
            'subdomain' => $subscription->subdomain,
            'subscription_id' => $subscription->id,
            'user_id' => $user->id,
            'admin_name' => $user->name,
            'admin_email' => $user->email,
            'admin_password_hash' => $hashedPassword,
            'package_name' => $subscription->package->name,
            'starts_at' => $subscription->starts_at->toIso8601String(),
            'expires_at' => $subscription->expires_at?->toIso8601String(),
        ];

        $response = Http::timeout(30)
            ->withHeaders([
                'Authorization' => 'Bearer ' . $tool->api_token,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])
            ->post($tool->api_url . '/api/tenants/create', $requestData);

        if (!$response->successful()) {
            throw new \Exception('API failed: ' . $response->body());
        }

        $responseData = $response->json();
        if (!($responseData['success'] ?? false)) {
            throw new \Exception($responseData['message'] ?? 'Unknown error');
        }

        $subscription->update([
            'tenant_id' => $tenantId,
            'is_tenant_active' => true,
            'tenant_created_at' => now(),
            'tenant_metadata' => $responseData,
        ]);

        $baseDomain = config('app.base_domain', 'ideenpipeline.de');
        $loginUrl = 'https://' . $subscription->subdomain . '.' . $baseDomain . '/tenant/' . $tenantId . '/login';

        return [
            'tenant_id' => $tenantId,
            'tenant_url' => $loginUrl,
            'subdomain' => $subscription->subdomain,
            'domain' => $subscription->subdomain . '.' . $baseDomain,
            'admin_email' => $user->email,
            'success' => true,
        ];
    }

    public function syncTenant(Request $request, Subscription $subscription): RedirectResponse
    {
        $this->authorize('update', $subscription);
        if ($subscription->tenant_id && $subscription->is_tenant_active) {
            return back()->with('info', 'Tenant already exists.');
        }

        $request->validate(['password' => ['required', 'string', 'min:8']]);

        try {
            DB::beginTransaction();
            if (!Hash::check($request->password, auth()->user()->password)) {
                return back()->with('error', 'Incorrect password.');
            }

            $tenantData = $this->createTenantOnToolServer($subscription);
            DB::commit();

            return redirect()->route('user.subscriptions.show', $subscription)
                ->with('success', 'Tenant synchronized!')
                ->with('tenant_credentials', $tenantData);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Sync failed: ' . $e->getMessage());
        }
    }

    public function payment(Subscription $subscription): View
    {
        $this->authorize('view', $subscription);
        if ($subscription->status === 'active') {
            return redirect()->route('user.subscriptions.success', $subscription);
        }
        $subscription->load(['package.tool', 'transaction']);
        return view('user.subscriptions.payment', compact('subscription'));
    }

    public function paymentStripe(Subscription $subscription): View
    {
        $this->authorize('view', $subscription);
        $subscription->load(['package.tool', 'transaction']);
        return view('user.subscriptions.payment-stripe', compact('subscription'));
    }

    public function paymentPaypal(Subscription $subscription): View
    {
        $this->authorize('view', $subscription);
        $subscription->load(['package.tool', 'transaction']);
        return view('user.subscriptions.payment-paypal', compact('subscription'));
    }

    public function paymentBank(Subscription $subscription): View
    {
        $this->authorize('view', $subscription);
        $subscription->load(['package.tool', 'transaction']);
        return view('user.subscriptions.payment-bank', compact('subscription'));
    }

    public function success(Subscription $subscription): View
    {
        $this->authorize('view', $subscription);
        $subscription->load(['package.tool', 'transaction']);
        $tenantCredentials = session('tenant_credentials');
        $loginUrl = null;
        if ($subscription->tenant_id) {
            $baseDomain = config('app.base_domain', 'ideenpipeline.de');
            $loginUrl = 'https://' . $subscription->subdomain . '.' . $baseDomain . '/tenant/' . $subscription->tenant_id . '/login';
        }
        return view('user.subscriptions.success', compact('subscription', 'tenantCredentials', 'loginUrl'));
    }

    public function show(Subscription $subscription): View
    {
        $this->authorize('view', $subscription);
        $subscription->load(['package.tool', 'transaction']);
        $loginUrl = null;
        if ($subscription->tenant_id && $subscription->is_tenant_active) {
            $baseDomain = config('app.base_domain', 'ideenpipeline.de');
            $loginUrl = 'https://' . $subscription->subdomain . '.' . $baseDomain . '/tenant/' . $subscription->tenant_id . '/login';
        }
        return view('user.subscriptions.show', compact('subscription', 'loginUrl'));
    }

    public function cancel(Request $request, Subscription $subscription): RedirectResponse
    {
        $this->authorize('update', $subscription);
        try {
            $subscription->update(['status' => 'cancelled', 'is_tenant_active' => false]);
            if ($subscription->tenant_id) {
                $tool = $subscription->package->tool;
                Http::timeout(10)->withHeaders(['Authorization' => 'Bearer ' . $tool->api_token])
                    ->post($tool->api_url . '/api/tenants/' . $subscription->tenant_id . '/update-status', ['status' => 'inactive']);
            }
            return redirect()->route('user.subscriptions.index')->with('success', 'Subscription cancelled.');
        } catch (\Exception $e) {
            return back()->with('error', 'Cancellation failed.');
        }
    }
}