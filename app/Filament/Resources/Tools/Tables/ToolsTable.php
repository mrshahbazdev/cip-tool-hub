<?php

namespace App\Filament\Resources\Tools\Tables;

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
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;

class ToolsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('user.name')
                    ->label('User')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('package.tool.name')
                    ->label('Tool')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('primary'),

                TextColumn::make('package.name')
                    ->label('Package')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('subdomain')
                    ->label('Domain')
                    ->searchable()
                    ->formatStateUsing(fn (Subscription $record) => $record->full_domain)
                    ->copyable()
                    ->copyMessage('Domain copied to clipboard!')
                    ->tooltip('Click to copy'),

                TextColumn::make('package.price')
                    ->label('Amount')
                    ->formatStateUsing(fn ($state) => '€' . number_format($state, 2))
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
            ])
            ->recordActions([
                // Approve (Green Check) - Only for Pending
                Action::make('approve')
                    ->label('Approve')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (Subscription $record): bool => $record->status === 'pending')
                    ->requiresConfirmation()
                    ->modalHeading('Approve Subscription')
                    ->modalDescription(fn (Subscription $record) => "Approve subscription for {$record->user->name}?")
                    ->modalSubmitActionLabel('Yes, Approve')
                    ->action(function (Subscription $record) {
                        $record->update(['status' => 'active']);
                        
                        if ($record->transaction) {
                            $record->transaction->update(['status' => 'completed']);
                        }
                        
                        Notification::make()
                            ->title('Subscription Approved!')
                            ->body("Subscription for {$record->user->name} has been activated.")
                            ->success()
                            ->send();
                    }),

                // Activate (Refresh) - For Expired/Cancelled
                Action::make('activate')
                    ->label('Activate')
                    ->icon('heroicon-o-arrow-path')
                    ->color('success')
                    ->visible(fn (Subscription $record): bool => in_array($record->status, ['expired', 'cancelled']))
                    ->requiresConfirmation()
                    ->action(function (Subscription $record) {
                        $record->update(['status' => 'active']);
                        
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
                    ->visible(fn (Subscription $record): bool => $record->status === 'active')
                    ->requiresConfirmation()
                    ->modalHeading('Deactivate Subscription')
                    ->modalDescription('Mark this subscription as expired.')
                    ->action(function (Subscription $record) {
                        $record->update(['status' => 'expired']);
                        
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
                    ->visible(fn (Subscription $record): bool => $record->status !== 'cancelled')
                    ->requiresConfirmation()
                    ->modalHeading('Cancel Subscription')
                    ->modalDescription('This will cancel the subscription permanently.')
                    ->action(function (Subscription $record) {
                        $record->update(['status' => 'cancelled']);
                        
                        Notification::make()
                            ->title('Subscription Cancelled!')
                            ->success()
                            ->send();
                    }),

                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
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
                        ->modalDescription(fn (Collection $records) => "Approve {$records->count()} subscription(s)?")
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
}