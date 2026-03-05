<?php

namespace App\Filament\Resources\Subscriptions\Tables;

use App\Console\Commands\SyncToolSubscriptions;
use App\Models\Subscription;
use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Artisan;

class SubscriptionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->searchable(),

                IconColumn::make('is_external')
                    ->label('Source')
                    ->boolean()
                    ->trueIcon('heroicon-o-arrow-down-tray')
                    ->falseIcon('heroicon-o-home')
                    ->trueColor('warning')
                    ->falseColor('success')
                    ->tooltip(fn(Subscription $record) => $record->is_external ? 'External (Tool)' : 'Hub'),

                TextColumn::make('user.name')
                    ->label('User')
                    ->searchable()
                    ->sortable()
                    ->default(fn(Subscription $record) => $record->admin_email ?? '—')
                    ->description(fn(Subscription $record) => $record->is_external ? '[External]' : null),

                TextColumn::make('package.tool.name')
                    ->label('Tool')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('primary')
                    ->default(fn(Subscription $record) => $record->tool?->name ?? '—'),

                TextColumn::make('package.name')
                    ->label('Package')
                    ->searchable()
                    ->sortable()
                    ->default(fn(Subscription $record) => $record->external_package_name ?? '—'),

                TextColumn::make('subdomain')
                    ->label('Domain')
                    ->searchable()
                    ->formatStateUsing(fn(Subscription $record) => $record->full_domain)
                    ->copyable()
                    ->copyMessage('Domain copied to clipboard!')
                    ->tooltip('Click to copy'),

                TextColumn::make('package.price')
                    ->label('Amount')
                    ->formatStateUsing(fn($state) => '€' . number_format($state, 2))
                    ->sortable(),

                BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'active',
                        'danger' => 'expired',
                        'secondary' => 'cancelled',
                    ])
                    ->sortable(),

                TextColumn::make('starts_at')
                    ->label('Start Date')
                    ->dateTime('M d, Y')
                    ->sortable(),

                TextColumn::make('expires_at')
                    ->label('Expires')
                    ->dateTime('M d, Y')
                    ->default('Lifetime')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('M d, Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->label('Updated')
                    ->dateTime('M d, Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'active' => 'Active',
                        'expired' => 'Expired',
                        'cancelled' => 'Cancelled',
                    ])
                    ->multiple(),

                SelectFilter::make('package')
                    ->relationship('package', 'name')
                    ->searchable()
                    ->preload()
                    ->multiple(),

                TernaryFilter::make('is_external')
                    ->label('Source')
                    ->trueLabel('External (Tool)')
                    ->falseLabel('Hub Only')
                    ->placeholder('All Subscriptions'),
            ])
            ->recordActions([
                // Approve (Green Check) - Only for Pending
                Action::make('approve')
                    ->label('Approve')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn(Subscription $record): bool => $record->status === 'pending')
                    ->requiresConfirmation()
                    ->modalHeading('Approve Subscription')
                    ->modalDescription(fn(Subscription $record) => "Approve subscription for " . ($record->user?->name ?? $record->admin_email) . "?")
                    ->modalSubmitActionLabel('Yes, Approve')
                    ->action(function (Subscription $record) {
                        $record->update(['status' => 'active']);

                        if ($record->transaction) {
                            $record->transaction->update(['status' => 'completed']);
                        }

                        static::pushStatusToTool($record, 'active');

                        Notification::make()
                            ->title('Subscription Approved!')
                            ->body("Subscription activated successfully.")
                            ->success()
                            ->send();
                    }),

                // Activate (Refresh) - For Expired/Cancelled
                Action::make('activate')
                    ->label('Activate')
                    ->icon('heroicon-o-arrow-path')
                    ->color('success')
                    ->visible(fn(Subscription $record): bool => in_array($record->status, ['expired', 'cancelled']))
                    ->requiresConfirmation()
                    ->action(function (Subscription $record) {
                        $record->update(['status' => 'active']);

                        static::pushStatusToTool($record, 'active');

                        Notification::make()
                            ->title('Subscription Activated!')
                            ->success()
                            ->send();
                    }),

                // Deactivate (Pause) - Only for Active
                Action::make('deactivate')
                    ->label('Deactivate')
                    ->icon('heroicon-o-pause')
                    ->color('warning')
                    ->visible(fn(Subscription $record): bool => $record->status === 'active')
                    ->requiresConfirmation()
                    ->modalHeading('Deactivate Subscription')
                    ->modalDescription('Mark this subscription as expired.')
                    ->action(function (Subscription $record) {
                        $record->update(['status' => 'expired']);

                        static::pushStatusToTool($record, 'inactive');

                        Notification::make()
                            ->title('Subscription Deactivated!')
                            ->success()
                            ->send();
                    }),

                // Cancel (X) - For all except cancelled
                Action::make('cancel')
                    ->label('Cancel')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn(Subscription $record): bool => $record->status !== 'cancelled')
                    ->requiresConfirmation()
                    ->modalHeading('Cancel Subscription')
                    ->modalDescription('This will cancel the subscription permanently.')
                    ->action(function (Subscription $record) {
                        $record->update(['status' => 'cancelled']);

                        static::pushStatusToTool($record, 'suspended');

                        Notification::make()
                            ->title('Subscription Cancelled!')
                            ->success()
                            ->send();
                    }),

                ViewAction::make(),
                Action::make('edit')
                    ->label('Edit')
                    ->icon('heroicon-o-pencil')
                    ->color('gray')
                    ->url(fn(Subscription $record): string => \App\Filament\Resources\Subscriptions\SubscriptionResource::getUrl('edit', ['record' => $record])),
                DeleteAction::make(),
            ])
            ->headerActions([
                Action::make('sync_subscriptions')
                    ->label('Sync Tool Subscriptions')
                    ->icon('heroicon-o-arrow-path')
                    ->color('info')
                    ->requiresConfirmation()
                    ->modalHeading('Sync Tool Subscriptions')
                    ->modalDescription('This will fetch the latest subscriptions from all connected tools and update the list.')
                    ->modalSubmitActionLabel('Yes, Sync Now')
                    ->action(function () {
                        Artisan::call(SyncToolSubscriptions::class);
                        Notification::make()
                            ->title('Subscriptions Synced!')
                            ->body('Tool subscriptions have been successfully synced.')
                            ->success()
                            ->send();
                    }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    // Bulk Approve
                    BulkAction::make('bulk_approve')
                        ->label('Approve Selected')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->requiresConfirmation()
                        ->modalHeading('Approve Selected Subscriptions')
                        ->modalDescription(fn(Collection $records) => "Approve {$records->count()} subscription(s)?")
                        ->action(function (Collection $records) {
                            $approved = 0;

                            $records->each(function (Subscription $record) use (&$approved) {
                                if ($record->status === 'pending') {
                                    $record->update(['status' => 'active']);

                                    if ($record->transaction) {
                                        $record->transaction->update(['status' => 'completed']);
                                    }

                                    $approved++;
                                }
                            });

                            Notification::make()
                                ->title("Approved {$approved} subscription(s)!")
                                ->success()
                                ->send();
                        })
                        ->deselectRecordsAfterCompletion(),

                    // Bulk Activate
                    BulkAction::make('bulk_activate')
                        ->label('Activate Selected')
                        ->icon('heroicon-o-arrow-path')
                        ->color('success')
                        ->requiresConfirmation()
                        ->action(function (Collection $records) {
                            $count = $records->count();
                            $records->each->update(['status' => 'active']);

                            Notification::make()
                                ->title("Activated {$count} subscription(s)!")
                                ->success()
                                ->send();
                        })
                        ->deselectRecordsAfterCompletion(),

                    // Bulk Deactivate
                    BulkAction::make('bulk_deactivate')
                        ->label('Deactivate Selected')
                        ->icon('heroicon-o-pause')
                        ->color('warning')
                        ->requiresConfirmation()
                        ->action(function (Collection $records) {
                            $count = $records->count();
                            $records->each->update(['status' => 'expired']);

                            Notification::make()
                                ->title("Deactivated {$count} subscription(s)!")
                                ->success()
                                ->send();
                        })
                        ->deselectRecordsAfterCompletion(),

                    // Bulk Cancel
                    BulkAction::make('bulk_cancel')
                        ->label('Cancel Selected')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->modalHeading('Cancel Selected Subscriptions')
                        ->modalDescription('This action cannot be undone.')
                        ->action(function (Collection $records) {
                            $count = $records->count();
                            $records->each->update(['status' => 'cancelled']);

                            Notification::make()
                                ->title("Cancelled {$count} subscription(s)!")
                                ->success()
                                ->send();
                        })
                        ->deselectRecordsAfterCompletion(),

                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc')
            ->striped()
            ->paginated([10, 25, 50, 100]);
    }

    /**
     * Push a status change to the external tool's API
     */
    protected static function pushStatusToTool(Subscription $record, string $toolStatus): void
    {
        if (!$record->external_subscription_id || !$record->tool) {
            return;
        }

        $tool = $record->tool;

        if (!$tool->api_url || !$tool->api_token) {
            return;
        }

        try {
            Http::timeout(10)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $tool->api_token,
                    'Accept' => 'application/json',
                ])
                ->post(
                    $tool->api_url . '/api/tenants/' . $record->external_subscription_id . '/update-status',
                    ['status' => $toolStatus]
                );
        } catch (\Exception $e) {
            Log::error('Failed to push subscription status to tool', [
                'tool' => $tool->name,
                'subscription_id' => $record->external_subscription_id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}