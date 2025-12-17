<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Placeholder;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Hash;
use App\Models\Subscription;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Full Name')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('John Doe')
                    ->helperText('Enter user full name')
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
                    ->columnSpan(1),

                TextInput::make('password')
                    ->label('Password')
                    ->password()
                    ->dehydrateStateUsing(fn ($state) => filled($state) ? Hash::make($state) : null)
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

                Placeholder::make('subscriptions_info')
                    ->label('Subscriptions & Tenant Sync Info')
                    ->content(function ($record) {
                        if (!$record) {
                            return '📋 No subscriptions yet. User will be able to create subscriptions after registration.';
                        }
                        
                        $total = Subscription::where('user_id', $record->id)->count();
                        $active = Subscription::where('user_id', $record->id)
                            ->where('status', 'active')
                            ->count();
                        $activeTenants = Subscription::where('user_id', $record->id)
                            ->where('status', 'active')
                            ->where('is_tenant_active', true)
                            ->count();
                        
                        if ($total === 0) {
                            return '📋 No subscriptions. This user has not created any subscriptions yet.';
                        }
                        
                        $text = "📊 Total: {$total} | Active: {$active} | Active Tenants: {$activeTenants}";
                        
                        if ($activeTenants > 0) {
                            $text .= "\n\n🔄 PASSWORD SYNC ACTIVE: When you change this user's password, it will automatically sync to {$activeTenants} active tenant database(s).";
                        } else {
                            $text .= "\n\nℹ️ No active tenants. Password will only be updated in main platform.";
                        }
                        
                        return $text;
                    })
                    ->columnSpanFull()
                    ->visible(fn (string $operation): bool => $operation === 'edit'),
            ])
            ->columns(2);
    }
}