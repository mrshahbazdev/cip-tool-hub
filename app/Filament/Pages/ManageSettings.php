<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\RichEditor;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Pages\Page;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use UnitEnum;
use BackedEnum;

class ManageSettings extends Page implements HasForms
{
    use InteractsWithForms;

    /**
     * Navigation icons and groups remain static in Filament v4.
     */
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-cog-6-tooth';

    /**
     * The $view property must remain non-static (instance property) 
     * to match the parent Filament Page class definition.
     */
    protected string $view = 'filament.pages.manage-settings';

    /**
     * Navigation group remains static.
     */
    protected static string|UnitEnum|null $navigationGroup = 'System Management';

    /**
     * The $title property is static in the base Filament Page class for this version.
     */
    protected static ?string $title = 'General Settings';

    public ?array $data = [];

    public function mount(): void
    {
        // Load the first record (we only ever use one)
        $settings = Setting::first();
        if ($settings) {
            $this->form->fill($settings->toArray());
        }
    }

    /**
     * FIX: Updated signature to use Schema instead of Form to resolve TypeError.
     * Updated: Grid import corrected to Filament\Schemas\Components\Grid.
     */
    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Branding')
                    ->description('Manage your site identity and logos.')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('site_name')
                                ->label('Site Name')
                                ->required(),
                            TextInput::make('contact_email')
                                ->label('Contact Email')
                                ->email(),
                        ]),
                        Grid::make(2)->schema([
                            FileUpload::make('site_logo')
                                ->label('Site Logo')
                                ->image()
                                ->disk('public')
                                ->directory('settings'),
                            FileUpload::make('site_favicon')
                                ->label('Favicon')
                                ->image()
                                ->disk('public')
                                ->directory('settings'),
                        ]),
                    ]),

                Section::make('Content & SEO')
                    ->description('Define descriptions for search engines and the footer.')
                    ->schema([
                        Textarea::make('site_description')
                            ->label('Meta Description')
                            ->rows(3),
                        RichEditor::make('footer_description')
                            ->label('Footer Description Text')
                            ->columnSpanFull(),
                    ]),

                Section::make('Social Connectivity')
                    ->description('Manage links to your social media profiles.')
                    ->schema([
                        Grid::make(3)->schema([
                            TextInput::make('facebook_url')->label('Facebook')->url(),
                            TextInput::make('twitter_url')->label('Twitter (X)')->url(),
                            TextInput::make('linkedin_url')->label('LinkedIn')->url(),
                        ]),
                    ]),
            ])
            ->statePath('data');
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('Update Settings')
                ->submit('save')
                ->color('primary'),
        ];
    }

    public function save(): void
    {
        $settings = Setting::first();
        if ($settings) {
            $settings->update($this->form->getState());

            Notification::make()
                ->title('Settings updated successfully!')
                ->success()
                ->send();
        }
    }
}