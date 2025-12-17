<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use App\Models\Subscription;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

echo "╔════════════════════════════════════════════╗\n";
echo "║     FIX TENANT LINK                        ║\n";
echo "╚════════════════════════════════════════════╝\n\n";

$email = 'test1765886895@test.com';

// Get user
$user = User::where('email', $email)->first();

if (!$user) {
    die("❌ User not found\n");
}

echo "✓ User: {$user->name} (ID: {$user->id})\n\n";

// Get subscription
$subscription = Subscription::where('user_id', $user->id)->latest()->first();

if (!$subscription) {
    die("❌ No subscription found\n");
}

echo "📋 Subscription:\n";
echo "   ID: {$subscription->id}\n";
echo "   Status: {$subscription->status}\n";
echo "   Subdomain: {$subscription->subdomain}\n";
echo "   Tenant Active: " . ($subscription->is_tenant_active ? 'Yes' : 'No') . "\n";
echo "   Tenant ID: " . ($subscription->tenant_id ?: 'NOT SET') . "\n\n";

// Get tool
$tool = $subscription->package->tool;

// Check if tenant exists in crm-tool by querying the API
echo "🔍 Looking for tenant in CRM tool...\n";

try {
    // Check CRM tool database directly (if same server)
    $crmDbPath = dirname(__DIR__) . '/crm-tool/database/database.sqlite';
    
    if (file_exists($crmDbPath)) {
        echo "✓ Found CRM database\n";
        
        // Connect to CRM database
        $crmDb = new PDO('sqlite:' . $crmDbPath);
        $stmt = $crmDb->prepare("SELECT * FROM tenants WHERE admin_email = ?");
        $stmt->execute([$email]);
        $tenant = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($tenant) {
            echo "✓ Tenant found in CRM!\n";
            echo "   Tenant ID: {$tenant['id']}\n";
            echo "   Admin: {$tenant['admin_name']}\n";
            echo "   Database: {$tenant['tenancy_db_name']}\n";
            echo "   Status: {$tenant['status']}\n\n";
            
            // Update subscription
            echo "🔗 Linking subscription to tenant...\n";
            
            $subscription->update([
                'tenant_id' => $tenant['id'],
                'tenant_database' => $tenant['tenancy_db_name'],
                'is_tenant_active' => true,
                'tenant_created_at' => now(),
            ]);
            
            echo "✅ Link created!\n\n";
            
            // Now sync password
            echo "🔄 Syncing password...\n";
            
            $newPassword = '11223344';
            
            // Update in main platform
            $user->password = bcrypt($newPassword);
            $user->save();
            echo "✓ Password updated in main platform\n";
            
            // Sync to tenant
            $response = Http::timeout(10)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $tool->api_token,
                    'Accept' => 'application/json',
                ])
                ->post($tool->api_url . '/api/tenants/' . $tenant['id'] . '/update-password', [
                    'email' => $email,
                    'password' => $newPassword,
                ]);
            
            if ($response->successful()) {
                echo "✓ Password synced to tenant\n\n";
            } else {
                echo "⚠️  Password sync failed: " . $response->body() . "\n\n";
            }
            
            // Show login details
            echo "╔════════════════════════════════════════════╗\n";
            echo "║           LOGIN DETAILS                    ║\n";
            echo "╚════════════════════════════════════════════╝\n\n";
            
            echo "🌐 Tenant Login:\n";
            echo "   URL: http://{$subscription->subdomain}.local:8001/tenant/{$tenant['id']}/login\n";
            echo "   OR:  http://127.0.0.1:8001/tenant/{$tenant['id']}/login\n";
            echo "   Email: {$email}\n";
            echo "   Password: {$newPassword}\n\n";
            
            echo "🏠 Main Platform:\n";
            echo "   URL: http://127.0.0.1:8000/login\n";
            echo "   Email: {$email}\n";
            echo "   Password: {$newPassword}\n\n";
            
        } else {
            echo "❌ Tenant not found in CRM database\n";
            echo "   Need to create tenant!\n";
        }
        
    } else {
        echo "❌ CRM database not found at: {$crmDbPath}\n";
    }
    
} catch (\Exception $e) {
    echo "❌ Error: {$e->getMessage()}\n";
}