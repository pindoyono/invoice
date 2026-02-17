<?php

namespace App\Filament\Pages;

use App\Models\CompanySetting;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class Settings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static ?string $navigationLabel = 'Company Settings';
    protected static ?string $navigationGroup = 'Settings';
    protected static ?int $navigationSort = 100;

    protected static string $view = 'filament.pages.settings';

    public ?array $data = [];

    public function mount(): void
    {
        $settings = CompanySetting::first();
        
        $this->form->fill($settings ? $settings->toArray() : []);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Company Information')
                    ->schema([
                        Forms\Components\TextInput::make('company_name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Textarea::make('address')
                            ->rows(3),
                        Forms\Components\TextInput::make('phone')
                            ->tel(),
                        Forms\Components\TextInput::make('email')
                            ->email(),
                        Forms\Components\TextInput::make('website')
                            ->url(),
                        Forms\Components\FileUpload::make('logo')
                            ->image()
                            ->disk('public')
                            ->directory('company')
                            ->visibility('public')
                            ->imagePreviewHeight('100')
                            ->loadingIndicatorPosition('left')
                            ->removeUploadedFileButtonPosition('right')
                            ->downloadable(),
                    ])->columns(2),

                Forms\Components\Section::make('Bank Information')
                    ->schema([
                        Forms\Components\TextInput::make('bank_name')
                            ->label('Bank Name'),
                        Forms\Components\TextInput::make('bank_account_number')
                            ->label('Account Number'),
                        Forms\Components\TextInput::make('bank_account_name')
                            ->label('Account Holder Name'),
                    ])->columns(3),

                Forms\Components\Section::make('Invoice Settings')
                    ->schema([
                        Forms\Components\TextInput::make('invoice_prefix')
                            ->default('INV')
                            ->maxLength(10),
                        Forms\Components\FileUpload::make('signature')
                            ->image()
                            ->disk('public')
                            ->directory('company')
                            ->visibility('public')
                            ->imagePreviewHeight('100')
                            ->loadingIndicatorPosition('left')
                            ->removeUploadedFileButtonPosition('right')
                            ->downloadable()
                            ->label('Signature Image'),
                        Forms\Components\Textarea::make('terms_conditions')
                            ->label('Terms & Conditions')
                            ->rows(4)
                            ->columnSpanFull(),
                    ])->columns(2),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();
        
        CompanySetting::updateOrCreate(
            ['id' => CompanySetting::first()?->id ?? 0],
            $data
        );

        Notification::make()
            ->title('Settings saved successfully')
            ->success()
            ->send();
    }
}
