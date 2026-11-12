<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use App\Services\SettingsService;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class Settings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-cog';

    protected static ?string $title = 'Website Settings';

    protected static ?string $slug = 'settings';

    protected static string $view = 'filament.pages.settings';

    protected static bool $shouldRegisterNavigation = true;

    public ?array $data = [];

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Brand Details')
                    ->schema([
                        Forms\Components\TextInput::make('site_name')
                            ->label('Site Name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\FileUpload::make('logo')
                            ->image()
                            ->maxSize(2048)
                            ->directory('settings'),
                        Forms\Components\FileUpload::make('favicon')
                            ->image()
                            ->maxSize(2048)
                            ->directory('settings'),
                        Forms\Components\FileUpload::make('about_image')
                            ->label('About Page Image')
                            ->image()
                            ->maxSize(4096)
                            ->directory('settings'),
                    ])
                    ->columns(3),

                Forms\Components\Section::make('Contact Details')
                    ->schema([
                        Forms\Components\TextInput::make('phone')
                            ->label('Phone')
                            ->tel()
                            ->maxLength(20),
                        Forms\Components\TextInput::make('whatsapp_number')
                            ->label('WhatsApp Number')
                            ->tel()
                            ->maxLength(20),
                        Forms\Components\TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('address')
                            ->label('Address')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('map_link')
                            ->label('Map Link')
                            ->url()
                            ->maxLength(255),
                    ])
                    ->columns(3),

                Forms\Components\Section::make('Social Links')
                    ->schema([
                        Forms\Components\TextInput::make('social_facebook')
                            ->label('Facebook')
                            ->url()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('social_instagram')
                            ->label('Instagram')
                            ->url()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('social_whatsapp')
                            ->label('WhatsApp')
                            ->url()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('social_twitter')
                            ->label('Twitter / X')
                            ->url()
                            ->maxLength(255),
                    ])
                    ->columns(4),

                Forms\Components\Section::make('Footer')
                    ->schema([
                        Forms\Components\Textarea::make('footer_text')
                            ->label('Footer Text')
                            ->columnSpanFull(),
                    ]),
            ])
            ->statePath('data');
    }

    public function mount(): void
    {
        $this->form->fill([
            'site_name' => Setting::get('site_name'),
            'logo' => Setting::get('logo'),
            'favicon' => Setting::get('favicon'),
            'about_image' => Setting::get('about_image'),
            'phone' => Setting::get('phone'),
            'whatsapp_number' => Setting::get('whatsapp_number'),
            'email' => Setting::get('email'),
            'address' => Setting::get('address'),
            'map_link' => Setting::get('map_link'),
            'social_facebook' => Setting::get('social_facebook'),
            'social_instagram' => Setting::get('social_instagram'),
            'social_whatsapp' => Setting::get('social_whatsapp'),
            'social_twitter' => Setting::get('social_twitter'),
            'footer_text' => Setting::get('footer_text'),
        ]);
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('Save Settings')
                ->submit('save'),
        ];
    }

    public function save(SettingsService $settings): void
    {
        foreach ($this->form->getState() as $key => $value) {
            Setting::set($key, $value, 'general');
        }

        $settings->clear();

        Notification::make()
            ->title('Settings saved')
            ->success()
            ->send();
    }
}
