<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Subscription;
use App\Models\User;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                // Basic Information Section
                Section::make('Basic Information')
                    ->schema([
                        TextInput::make('name')
                            ->label('Full Name')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Enter user full name')
                            ->helperText('User\'s full name for display and identification')
                            ->columnSpan(1),
                        
                        TextInput::make('email')
                            ->label('Email Address')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255)
                            ->placeholder('user@example.com')
                            ->helperText('User email for login and tenant access')
                            ->columnSpan(1),

                        Select::make('role')
                            ->label('User Role')
                            ->required()
                            ->options([
                                'admin' => 'Administrator',
                                'user' => 'Regular User',
                            ])
                            ->default('user')
                            ->helperText('Select user access level')
                            ->searchable()
                            ->columnSpan(2),
                    ])
                    ->columns(2)
                    ->collapsible(),

                // Password Section
                Section::make('Password Management')
                    ->schema([
                        TextInput::make('password')
                            ->label('Password')
                            ->password()
                            ->dehydrateStateUsing(function ($state, $record) {
                                if (!filled($state)) {
                                    return null;
                                }
                                
                                // If editing and password is being changed, sync to tenants
                                if ($record && filled($state)) {
                                    static::syncPasswordToTenants($record, $state);
                                }
                                
                                return Hash::make($state);
                            })
                            ->dehydrated(fn ($state) => filled($state))
                            ->required(fn (string $operation): bool => $operation === 'create')
                            ->maxLength(255)
                            ->placeholder('Min 8 characters')
                            ->helperText('🔄 Leave empty to keep current password. Changes will sync to all active tenants.')
                            ->revealable()
                            ->minLength(8)
                            ->columnSpan(1),
                        
                        TextInput::make('password_confirmation')
                            ->label('Confirm Password')
                            ->password()
                            ->same('password')
                            ->requiredWith('password')
                            ->maxLength(255)
                            ->placeholder('Repeat password')
                            ->dehydrated(false)
                            ->revealable()
                            ->columnSpan(1),
                    ])
                    ->columns(2)
                    ->collapsible(),

                // Subscriptions Info Section
                Section::make('Subscriptions & Tenant Sync Info')
                    ->schema([
                        Placeholder::make('subscriptions_info')
                            ->label('')
                            ->content(function ($record) {
                                if (!$record || !$record->exists) {
                                    return '📋 No subscriptions yet. User will be able to create subscriptions after registration.';
                                }
                                
                                // Load subscriptions with relationships
                                $subscriptions = Subscription::where('user_id', $record->id)
                                    ->with(['package.tool'])
                                    ->get();
                                
                                $total = $subscriptions->count();
                                $active = $subscriptions->where('status', 'active')->count();
                                $activeTenants = $subscriptions->where('status', 'active')
                                    ->where('is_tenant_active', true)
                                    ->whereNotNull('tenant_id')
                                    ->count();
                                
                                if ($total === 0) {
                                    return '📋 No subscriptions. This user has not created any subscriptions yet.';
                                }
                                
                                $html = "<div class='space-y-4'>";
                                
                                // Stats
                                $html .= "<div class='flex items-center gap-6 p-3 bg-gray-50 rounded-lg'>";
                                $html .= "<div><span class='text-2xl font-bold text-blue-600'>{$total}</span><br><span class='text-xs text-gray-600'>Total</span></div>";
                                $html .= "<div><span class='text-2xl font-bold text-green-600'>{$active}</span><br><span class='text-xs text-gray-600'>Active</span></div>";
                                $html .= "<div><span class='text-2xl font-bold text-purple-600'>{$activeTenants}</span><br><span class='text-xs text-gray-600'>Active Tenants</span></div>";
                                $html .= "</div>";
                                
                                // Password sync info
                                if ($activeTenants > 0) {
                                    $html .= "<div class='p-3 bg-green-50 border border-green-200 rounded-lg'>";
                                    $html .= "✅ <strong>PASSWORD SYNC ACTIVE</strong><br>";
                                    $html .= "<span class='text-sm'>When you change this user's password, it will automatically sync to <strong>{$activeTenants} active tenant database(s)</strong>.</span>";
                                    $html .= "</div>";
                                    
                                    // List active tenants
                                    $activeTenantsList = $subscriptions->where('status', 'active')
                                        ->where('is_tenant_active', true)
                                        ->whereNotNull('tenant_id');
                                    
                                    $html .= "<div class='space-y-2'>";
                                    $html .= "<strong class='text-sm'>Active Tenants:</strong>";
                                    $html .= "<ul class='space-y-1 ml-4'>";
                                    foreach ($activeTenantsList as $tenant) {
                                        $toolName = $tenant->package->tool->name ?? 'Unknown Tool';
                                        $html .= "<li class='text-sm flex items-center gap-2'>";
                                        $html .= "<span class='w-2 h-2 bg-green-500 rounded-full'></span>";
                                        $html .= "<strong>{$tenant->subdomain}</strong> ({$toolName})";
                                        $html .= "<span class='text-xs text-gray-500 font-mono'>{$tenant->tenant_id}</span>";
                                        $html .= "</li>";
                                    }
                                    $html .= "</ul>";
                                    $html .= "</div>";
                                } else if ($active > 0) {
                                    $html .= "<div class='p-3 bg-yellow-50 border border-yellow-200 rounded-lg'>";
                                    $html .= "⚠️ <strong>Active subscriptions found but no active tenants.</strong><br>";
                                    $html .= "<span class='text-sm'>Subscriptions may need to be synced to create tenants.</span>";
                                    $html .= "</div>";
                                    
                                    // List subscriptions without tenants
                                    $subsWithoutTenants = $subscriptions->where('status', 'active')
                                        ->whereNull('tenant_id');
                                    
                                    if ($subsWithoutTenants->count() > 0) {
                                        $html .= "<div class='space-y-2'>";
                                        $html .= "<strong class='text-sm'>Subscriptions needing tenant setup:</strong>";
                                        $html .= "<ul class='space-y-1 ml-4'>";
                                        foreach ($subsWithoutTenants as $sub) {
                                            $toolName = $sub->package->tool->name ?? 'Unknown Tool';
                                            $html .= "<li class='text-sm flex items-center gap-2'>";
                                            $html .= "<span class='w-2 h-2 bg-yellow-500 rounded-full'></span>";
                                            $html .= "{$sub->subdomain} ({$toolName}) - <em>Needs sync</em>";
                                            $html .= "</li>";
                                        }
                                        $html .= "</ul>";
                                        $html .= "</div>";
                                    }
                                } else {
                                    $html .= "<div class='p-3 bg-gray-50 border border-gray-200 rounded-lg'>";
                                    $html .= "ℹ️ No active tenants. Password will only be updated in main platform.";
                                    $html .= "</div>";
                                }
                                
                                // All subscriptions list
                                $html .= "<div class='mt-4'>";
                                $html .= "<details class='cursor-pointer'>";
                                $html .= "<summary class='text-sm font-semibold text-gray-700 hover:text-gray-900'>View All Subscriptions ({$total})</summary>";
                                $html .= "<div class='mt-2 space-y-1 ml-4'>";
                                foreach ($subscriptions as $sub) {
                                    $toolName = $sub->package->tool->name ?? 'Unknown';
                                    $statusIcon = match($sub->status) {
                                        'active' => '✅',
                                        'pending' => '⏳',
                                        'expired' => '⏰',
                                        'cancelled' => '❌',
                                        default => '❓'
                                    };
                                    $html .= "<div class='text-sm flex items-center gap-2'>";
                                    $html .= "<span>{$statusIcon}</span>";
                                    $html .= "<strong>{$sub->subdomain}</strong>";
                                    $html .= "<span class='text-gray-500'>- {$toolName} ({$sub->status})</span>";
                                    if ($sub->tenant_id) {
                                        $html .= "<span class='text-xs text-gray-400 font-mono'>{$sub->tenant_id}</span>";
                                    }
                                    $html .= "</div>";
                                }
                                $html .= "</div>";
                                $html .= "</details>";
                                $html .= "</div>";
                                
                                $html .= "</div>";
                                
                                return $html;
                            })
                            ->columnSpanFull(),
                    ])
                    ->collapsible()
                    ->visible(fn (string $operation): bool => $operation === 'edit'),
            ]);
    }

    /**
     * Sync password to all active tenants
     */
    protected static function syncPasswordToTenants(User $user, string $plainPassword): void
    {
        try {
            // Get all active subscriptions with tenants
            $subscriptions = Subscription::where('user_id', $user->id)
                ->where('status', 'active')
                ->where('is_tenant_active', true)
                ->whereNotNull('tenant_id')
                ->with('package.tool')
                ->get();

            if ($subscriptions->isEmpty()) {
                return;
            }

            Log::info('Starting password sync to tenants', [
                'user_id' => $user->id,
                'email' => $user->email,
                'tenant_count' => $subscriptions->count(),
            ]);

            foreach ($subscriptions as $subscription) {
                try {
                    $tool = $subscription->package->tool;

                    // Make API request to update password
                    $response = Http::timeout(10)
                        ->withHeaders([
                            'Authorization' => 'Bearer ' . $tool->api_token,
                            'Accept' => 'application/json',
                        ])
                        ->post($tool->api_url . '/api/tenants/' . $subscription->tenant_id . '/update-password', [
                            'email' => $user->email,
                            'password' => $plainPassword,
                        ]);

                    if ($response->successful()) {
                        Log::info('Password synced to tenant', [
                            'tenant_id' => $subscription->tenant_id,
                            'subdomain' => $subscription->subdomain,
                        ]);
                    } else {
                        Log::error('Failed to sync password to tenant', [
                            'tenant_id' => $subscription->tenant_id,
                            'status' => $response->status(),
                            'response' => $response->body(),
                        ]);
                    }

                } catch (\Exception $e) {
                    Log::error('Exception syncing password to tenant', [
                        'tenant_id' => $subscription->tenant_id,
                        'error' => $e->getMessage(),
                    ]);
                }
            }

            Log::info('Password sync completed', [
                'user_id' => $user->id,
                'synced_count' => $subscriptions->count(),
            ]);

        } catch (\Exception $e) {
            Log::error('Password sync failed', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}