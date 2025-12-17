<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use App\Models\Tool;
use App\Models\Package;
use App\Models\Transaction;
use App\Models\Subscription;
use Illuminate\Support\Str;

echo "╔════════════════════════════════════════════╗\n";
echo "║  TEST SINGLE-DB MULTI-TENANCY              ║\n";
echo "╚════════════════════════════════════════════╝\n\n";

// Clean up old test data
echo "🧹 Cleaning old test data...\n";
User::where('email', 'like', 'singledbtest%@test.com')->delete();
Subscription::where('subdomain', 'like', 'singledbtest%')->delete();
Transaction::where('user_id', 999999)->delete();

// Step 1: Create test user
echo "👤 Creating test user...\n";
$timestamp = time();
$user = User::create([
    'name' => "SingleDB Test User {$timestamp}",
    'email' => "singledbtest{$timestamp}@test.com",
    'password' => bcrypt('testpass123'),
    'role' => 'user',
    'email_verified_at' => now(),
]);
echo "✓ User created: {$user->name} (ID: {$user->id})\n\n";

// Step 2: Get tool and package
echo "🔧 Getting tool and package...\n";
$tool = Tool::where('domain', 'crm')->first();

if (!$tool) {
    die("❌ CRM tool not found. Create it first!\n");
}

$package = $tool->packages()->where('status', true)->first();

if (!$package) {
    die("❌ No active package found for CRM tool!\n");
}

echo "✓ Tool: {$tool->name}\n";
echo "✓ Package: {$package->name} (\${$package->price})\n\n";

// Step 3: Create transaction
echo "💳 Creating transaction...\n";
$transaction = Transaction::create([
    'user_id' => $user->id,
    'package_id' => $package->id,
    'amount' => $package->price,
    'payment_method' => 'test',
    'transaction_id' => 'TXN_SINGLE_' . $timestamp,
    'status' => 'completed',
]);
echo "✓ Transaction created: {$transaction->transaction_id}\n\n";

// Step 4: Create subscription
echo "📦 Creating subscription...\n";
$subdomain = 'singledbtest' . $timestamp;
$subscription = Subscription::create([
    'user_id' => $user->id,
    'package_id' => $package->id,
    'transaction_id' => $transaction->id,
    'subdomain' => $subdomain,
    'starts_at' => now(),
    'expires_at' => now()->addDays($package->duration_days),
    'status' => 'active',
    'admin_email' => $user->email,
]);
echo "✓ Subscription created (ID: {$subscription->id})\n";
echo "  Subdomain: {$subdomain}\n\n";

// Step 5: Create tenant via API
echo "🏢 Creating tenant in CRM tool...\n";
$tenantId = 'tenant_' . Str::uuid();
$password = 'testpass123';

try {
    $response = $tool->createTenant([
        'tenant_id' => $tenantId,
        'subdomain' => $subdomain,
        'subscription_id' => $subscription->id,
        'user_id' => $user->id,
        'admin_name' => $user->name,
        'admin_email' => $user->email,
        'admin_password' => $password,
        'package_name' => $package->name,
        'starts_at' => $subscription->starts_at->toIso8601String(),
        'expires_at' => $subscription->expires_at ? $subscription->expires_at->toIso8601String() : null,
    ]);

    if ($response['success']) {
        echo "✓ Tenant created successfully!\n";
        echo "  Tenant ID: {$tenantId}\n";
        
        // Update subscription with tenant info
        $subscription->update([
            'tenant_id' => $tenantId,
            'is_tenant_active' => true,
            'tenant_created_at' => now(),
        ]);
        
        echo "✓ Subscription linked to tenant\n\n";
    } else {
        echo "❌ Tenant creation failed: {$response['message']}\n";
        exit(1);
    }
} catch (\Exception $e) {
    echo "❌ Error: {$e->getMessage()}\n";
    exit(1);
}

// Step 6: Test password sync
echo "🔄 Testing password sync...\n";
$newPassword = 'newpass456';

$syncResponse = \Illuminate\Support\Facades\Http::timeout(10)
    ->withHeaders([
        'Authorization' => 'Bearer ' . $tool->api_token,
        'Accept' => 'application/json',
    ])
    ->post($tool->api_url . '/api/tenants/' . $tenantId . '/update-password', [
        'email' => $user->email,
        'password' => $newPassword,
    ]);

if ($syncResponse->successful()) {
    echo "✓ Password synced successfully!\n\n";
    $password = $newPassword; // Update for login
} else {
    echo "⚠️  Password sync failed: {$syncResponse->body()}\n\n";
}

// Summary
echo "╔════════════════════════════════════════════╗\n";
echo "║           TEST COMPLETE ✅                  ║\n";
echo "╚════════════════════════════════════════════╝\n\n";

echo "🌐 TENANT LOGIN:\n";
echo "   URL: http://127.0.0.1:8001/tenant/{$tenantId}/login\n";
echo "   OR:  http://{$subdomain}.local:8001/tenant/{$tenantId}/login\n";
echo "   Email: {$user->email}\n";
echo "   Password: {$password}\n\n";

echo "🏠 MAIN PLATFORM:\n";
echo "   URL: http://127.0.0.1:8000/login\n";
echo "   Email: {$user->email}\n";
echo "   Password: {$password}\n\n";

echo "🔍 VERIFY IN DATABASE:\n";
echo "   cd crm-tool && php artisan tinker\n";
echo "   \$tenant = \\App\\Models\\Tenant::find('{$tenantId}');\n";
echo "   \$tenant->users;\n\n";