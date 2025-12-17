<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\HtmlString;
use App\Models\Subscription;
use App\Models\User;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Forms\Components\TextInput::make('name')
                    ->label('Full Name')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('Enter user full name')
                    ->helperText('User\'s full name for display and identification')
                    ->columnSpan(1),
                
                Forms\Components\TextInput::make('email')
                    ->label('Email Address')
                    ->email()
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255)
                    ->placeholder('user@example.com')
                    ->helperText('User email for login and tenant access')
                    ->columnSpan(1),

                Forms\Components\Select::make('role')
                    ->label('User Role')
                    ->required()
                    ->options([
                        'admin' => 'Administrator',
                        'user' => 'Regular User',
                    ])
                    ->default('user')
                    ->helperText('Select user access level')
                    ->searchable()
                    ->columnSpan(1),

                Forms\Components\TextInput::make('password')
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
                
                Forms\Components\TextInput::make('password_confirmation')
                    ->label('Confirm Password')
                    ->password()
                    ->same('password')
                    ->requiredWith('password')
                    ->maxLength(255)
                    ->placeholder('Repeat password')
                    ->dehydrated(false)
                    ->revealable()
                    ->columnSpan(1),

                Forms\Components\Placeholder::make('subscriptions_info')
                    ->label('Subscriptions & Tenant Sync Info')
                    ->content(function ($record) {
                        if (!$record || !$record->exists) {
                            return new HtmlString('📋 No subscriptions yet. User will be able to create subscriptions after registration.');
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
                            return new HtmlString('📋 No subscriptions. This user has not created any subscriptions yet.');
                        }
                        
                        $html = "<div class='space-y-3'>";
                        
                        // Stats Row
                        $html .= "<div class='flex items-center gap-4 p-3 bg-gray-50 rounded-lg border border-gray-200'>";
                        $html .= "<div class='text-center'>";
                        $html .= "<div class='text-2xl font-bold text-blue-600'>{$total}</div>";
                        $html .= "<div class='text-xs text-gray-600'>Total</div>";
                        $html .= "</div>";
                        $html .= "<div class='text-center'>";
                        $html .= "<div class='text-2xl font-bold text-green-600'>{$active}</div>";
                        $html .= "<div class='text-xs text-gray-600'>Active</div>";
                        $html .= "</div>";
                        $html .= "<div class='text-center'>";
                        $html .= "<div class='text-2xl font-bold text-purple-600'>{$activeTenants}</div>";
                        $html .= "<div class='text-xs text-gray-600'>Active Tenants</div>";
                        $html .= "</div>";
                        $html .= "</div>";
                        
                        // Password Sync Status
                        if ($activeTenants > 0) {
                            $html .= "<div class='p-3 bg-green-50 border border-green-200 rounded-lg'>";
                            $html .= "<div class='flex items-start gap-2'>";
                            $html .= "<span class='text-green-600'>✅</span>";
                            $html .= "<div>";
                            $html .= "<strong class='text-green-800'>PASSWORD SYNC ACTIVE</strong><br>";
                            $html .= "<span class='text-sm text-green-700'>Password hash will automatically sync to <strong>{$activeTenants}</strong> active tenant(s).</span>";
                            $html .= "</div>";
                            $html .= "</div>";
                            $html .= "</div>";
                            
                            // Active Tenants List
                            $activeTenantsList = $subscriptions->where('status', 'active')
                                ->where('is_tenant_active', true)
                                ->whereNotNull('tenant_id');
                            
                            if ($activeTenantsList->count() > 0) {
                                $html .= "<div class='p-3 bg-white border border-gray-200 rounded-lg'>";
                                $html .= "<strong class='text-sm text-gray-700'>Active Tenants:</strong>";
                                $html .= "<ul class='mt-2 space-y-1'>";
                                foreach ($activeTenantsList as $tenant) {
                                    $toolName = $tenant->package->tool->name ?? 'Unknown Tool';
                                    $html .= "<li class='text-sm flex items-center gap-2 text-gray-600'>";
                                    $html .= "<span class='w-2 h-2 bg-green-500 rounded-full flex-shrink-0'></span>";
                                    $html .= "<strong class='text-gray-900'>{$tenant->subdomain}</strong>";
                                    $html .= "<span>({$toolName})</span>";
                                    $html .= "<code class='text-xs bg-gray-100 px-2 py-0.5 rounded'>{$tenant->tenant_id}</code>";
                                    $html .= "</li>";
                                }
                                $html .= "</ul>";
                                $html .= "</div>";
                            }
                        } else if ($active > 0) {
                            $html .= "<div class='p-3 bg-yellow-50 border border-yellow-200 rounded-lg'>";
                            $html .= "<div class='flex items-start gap-2'>";
                            $html .= "<span class='text-yellow-600'>⚠️</span>";
                            $html .= "<div>";
                            $html .= "<strong class='text-yellow-800'>Active subscriptions found but no active tenants.</strong><br>";
                            $html .= "<span class='text-sm text-yellow-700'>Subscriptions may need to be synced to create tenants.</span>";
                            $html .= "</div>";
                            $html .= "</div>";
                            $html .= "</div>";
                        } else {
                            $html .= "<div class='p-3 bg-gray-50 border border-gray-200 rounded-lg'>";
                            $html .= "<span class='text-gray-600'>ℹ️ No active tenants. Password will only be updated in main platform.</span>";
                            $html .= "</div>";
                        }
                        
                        // All Subscriptions Collapsible
                        $html .= "<details class='group'>";
                        $html .= "<summary class='cursor-pointer p-2 bg-gray-50 hover:bg-gray-100 rounded-lg transition'>";
                        $html .= "<span class='text-sm font-semibold text-gray-700'>View All Subscriptions ({$total})</span>";
                        $html .= "</summary>";
                        $html .= "<div class='mt-2 space-y-1 p-3 bg-white border border-gray-200 rounded-lg'>";
                        foreach ($subscriptions as $sub) {
                            $toolName = $sub->package->tool->name ?? 'Unknown';
                            $statusIcon = match($sub->status) {
                                'active' => '✅',
                                'pending' => '⏳',
                                'expired' => '⏰',
                                'cancelled' => '❌',
                                default => '❓'
                            };
                            $statusColor = match($sub->status) {
                                'active' => 'text-green-600',
                                'pending' => 'text-yellow-600',
                                'expired' => 'text-red-600',
                                'cancelled' => 'text-gray-600',
                                default => 'text-gray-400'
                            };
                            
                            $html .= "<div class='flex items-center gap-2 text-sm py-1'>";
                            $html .= "<span>{$statusIcon}</span>";
                            $html .= "<strong class='text-gray-900'>{$sub->subdomain}</strong>";
                            $html .= "<span class='text-gray-500'>- {$toolName}</span>";
                            $html .= "<span class='px-2 py-0.5 rounded text-xs {$statusColor} bg-gray-50'>{$sub->status}</span>";
                            if ($sub->tenant_id) {
                                $html .= "<code class='text-xs bg-gray-100 px-2 py-0.5 rounded text-gray-600'>{$sub->tenant_id}</code>";
                            }
                            $html .= "</div>";
                        }
                        $html .= "</div>";
                        $html .= "</details>";
                        
                        $html .= "</div>";
                        
                        return new HtmlString($html);
                    })
                    ->columnSpanFull()
                    ->visible(fn (string $operation): bool => $operation === 'edit'),
            ])
            ->columns(2);
    }

    /**
     * Sync password hash to all active tenants
     */
    protected static function syncPasswordToTenants(User $user, string $plainPassword): void
    {
        try {
            $subscriptions = Subscription::where('user_id', $user->id)
                ->where('status', 'active')
                ->where('is_tenant_active', true)
                ->whereNotNull('tenant_id')
                ->with('package.tool')
                ->get();

            if ($subscriptions->isEmpty()) {
                return;
            }

            Log::info('Starting password hash sync to tenants', [
                'user_id' => $user->id,
                'email' => $user->email,
                'tenant_count' => $subscriptions->count(),
            ]);

            // Hash the new password
            $hashedPassword = Hash::make($plainPassword);

            foreach ($subscriptions as $subscription) {
                try {
                    $tool = $subscription->package->tool;

                    $response = Http::timeout(10)
                        ->withHeaders([
                            'Authorization' => 'Bearer ' . $tool->api_token,
                            'Accept' => 'application/json',
                        ])
                        ->post($tool->api_url . '/api/tenants/' . $subscription->tenant_id . '/update-password', [
                            'email' => $user->email,
                            'password_hash' => $hashedPassword,
                        ]);

                    if ($response->successful()) {
                        Log::info('Password hash synced to tenant', [
                            'tenant_id' => $subscription->tenant_id,
                            'subdomain' => $subscription->subdomain,
                        ]);
                    }

                } catch (\Exception $e) {
                    Log::error('Exception syncing password hash', [
                        'tenant_id' => $subscription->tenant_id,
                        'error' => $e->getMessage(),
                    ]);
                }
            }

        } catch (\Exception $e) {
            Log::error('Password hash sync failed', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}