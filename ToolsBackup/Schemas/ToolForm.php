<?php

namespace App\Filament\Resources\Tools\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class ToolForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Tool Name')
                    ->required()
                    ->maxLength(255)
                    ->live(onBlur: true)
                    ->afterStateUpdated(function ($state, $set) {
                        if (!empty($state)) {
                            // Generate slug domain without .com suffix for flexibility
                            $domain = Str::slug($state);
                            $set('domain', $domain);
                        }
                    })
                    ->placeholder('e.g., CRM Pro')
                    ->helperText('Enter tool name - domain will auto-generate')
                    ->columnSpan(1),

                TextInput::make('domain')
                    ->label('Domain')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255)
                    ->placeholder('my-tool')
                    ->helperText('Domain without https:// or www')
                    ->suffixIcon('heroicon-m-globe-alt')
                    ->columnSpan(1),

                TextInput::make('api_url')
                    ->label('API URL')
                    ->url()
                    ->required()
                    ->maxLength(255)
                    ->placeholder('http://127.0.0.1:8002')
                    ->helperText('Base URL for the tool API')
                    ->columnSpan(2),

                TextInput::make('api_token')
                    ->label('API Token')
                    ->required()
                    ->default(fn () => Str::random(32))
                    ->maxLength(255)
                    ->helperText('Auto-generated API token (you can edit if needed)')
                    ->dehydrated(true)
                    ->columnSpan(2),

                Textarea::make('description')
                    ->label('Description')
                    ->rows(3)
                    ->maxLength(500)
                    ->placeholder('Describe what this tool does...')
                    ->columnSpanFull(),

                FileUpload::make('logo')
                    ->label('Logo')
                    ->image()
                    ->directory('tools')
                    ->maxSize(2048)
                    ->helperText('Upload logo (max 2MB, recommended: square image)')
                    ->columnSpan(1),

                Toggle::make('status')
                    ->label('Active Status')
                    ->default(true)
                    ->inline(false)
                    ->helperText('Enable to make available for subscriptions')
                    ->required()
                    ->columnSpan(1),
            ])
            ->columns(2);
    }
}