<?php

namespace App\Filament\Admin\Pages;

use App\Models\Setting;
use App\Services\MailService;
use Filament\Actions;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Actions\Contracts\HasActions;
use Filament\Pages\Concerns\InteractsWithHeaderActions;
use Filament\Pages\Page;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ManageSettings extends Page implements HasForms, HasActions
{
    use InteractsWithForms;
    use InteractsWithHeaderActions;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static ?string $navigationLabel = 'Settings';
    protected static ?string $title = 'Platform Settings';
    protected static ?string $navigationGroup = 'Settings';

    protected static ?int $navigationSort = 4;
    protected static string $view = 'filament.admin.pages.manage-settings';

    public array $data = [];

    public function mount(): void
    {
        $this->data = Setting::query()
            ->pluck('value', 'key')
            ->toArray();

        $this->form->fill($this->data);
    }

    protected function fillFormData(): void
    {
        $this->data = Setting::query()
            ->pluck('value', 'key')
            ->toArray();

        $this->form->fill($this->data);
    }

    protected function getHeaderActions(): array
    {
        $mailService = app(MailService::class);

        return [
            Actions\Action::make('create_setting')
                ->label('New setting')
                ->icon('heroicon-m-plus')
                ->form([
                    Forms\Components\TextInput::make('key')
                        ->required()
                        ->unique('settings', 'key')
                        ->helperText('Unique identifier, e.g. "site_title", "max_deposit_amount".')
                        ->rules(['alpha_dash']),

                    Forms\Components\TextInput::make('label')
                        ->required()
                        ->helperText('Human-readable name shown on the form.'),

                    Forms\Components\Textarea::make('description')
                        ->rows(2),

                    Forms\Components\Select::make('type')
                        ->options([
                            'text' => 'Text',
                            'textarea' => 'Textarea',
                            'number' => 'Number',
                            'email' => 'Email',
                            'url' => 'URL',
                            'password' => 'Password',
                            'boolean' => 'Boolean (toggle)',
                            'select' => 'Select',
                            'color' => 'Color',
                            'image' => 'Image',
                        ])
                        ->required()
                        ->live(),

                    Forms\Components\Select::make('group')
                        ->options(
                            Setting::query()
                                ->distinct()
                                ->pluck('group', 'group')
                                ->toArray()
                        )
                        ->createOptionForm([
                            Forms\Components\TextInput::make('value')->label('New group name')->required(),
                        ])
                        ->createOptionUsing(fn(array $data) => $data['value'])
                        ->searchable()
                        ->required()
                        ->helperText('Which tab this setting appears under.'),

                    Forms\Components\TextInput::make('value')
                        ->label('Default value')
                        ->visible(fn(Forms\Get $get) => ! in_array($get('type'), ['boolean', 'image'])),

                    Forms\Components\Toggle::make('value')
                        ->label('Default value')
                        ->visible(fn(Forms\Get $get) => $get('type') === 'boolean')
                        ->dehydrateStateUsing(fn($state) => $state ? '1' : '0'),

                    Forms\Components\Toggle::make('is_public')
                        ->default(true)
                        ->helperText('Whether this setting is exposed publicly (e.g. via API/frontend).'),

                    Forms\Components\TextInput::make('sort_order')
                        ->numeric()
                        ->default(0),
                ])
                ->action(function (array $data) {
                    Setting::create($data);

                    Notification::make()
                        ->success()
                        ->title('Setting created')
                        ->send();

                    $this->fillFormData();
                }), 
        ];
    }

    protected function getFormSchema(): array
    {
        $groups = Setting::query()
            ->orderBy('group')
            ->orderBy('sort_order')
            ->get()
            ->groupBy('group');

        $tabs = [];

        foreach ($groups as $group => $settings) {
            $tabs[] = Forms\Components\Tabs\Tab::make(Str::headline($group))
                ->schema(
                    $settings->map(fn(Setting $setting) => $this->buildField($setting))->toArray()
                )
                ->columns([
                    'default' => 1, // mobile: single column, full width
                    'md' => 2,      // tablet and up: two columns
                ]);
        }

        return [
            Forms\Components\Tabs::make('Settings')
                ->tabs($tabs)
                ->persistTabInQueryString()
                ->columnSpanFull(),
        ];
    }

    protected function buildField(Setting $setting): Forms\Components\Component
    {
        $field = match ($setting->type) {
            'boolean' => Forms\Components\Toggle::make($setting->key)
                ->formatStateUsing(fn($state) => (bool) $state)
                ->dehydrateStateUsing(fn($state) => $state ? '1' : '0'),

            'textarea' => Forms\Components\Textarea::make($setting->key)
                ->rows(4),

            'number' => Forms\Components\TextInput::make($setting->key)
                ->numeric(),

            'email' => Forms\Components\TextInput::make($setting->key)
                ->email(),

            'url' => Forms\Components\TextInput::make($setting->key)
                ->url(),

            'password' => Forms\Components\TextInput::make($setting->key)
                ->password()
                ->revealable(),

            'color' => Forms\Components\ColorPicker::make($setting->key),

            'select' => Forms\Components\Select::make($setting->key)
                ->options($this->selectOptions($setting->key))
                ->searchable(),

            'image' => Forms\Components\FileUpload::make($setting->key)
                ->image()
                ->disk('settings_images')
                ->directory('images/settings')
                ->visibility('public')
                ->saveUploadedFileUsing(function ($file) use ($setting) {
                    $filename = $setting->key . '_' . time() . '.' . $file->getClientOriginalExtension();
                    $file->storeAs('images/settings', $filename, ['disk' => 'settings_images']);

                    return 'images/settings/' . $filename;
                })
                ->imagePreviewHeight('100'),

            default => Forms\Components\TextInput::make($setting->key),
        };

        return $field
            ->label($setting->label ?? Str::headline($setting->key))
            ->helperText($setting->description)
            ->columnSpan([
                'default' => 1, // always full width on mobile
                'md' => in_array($setting->type, ['textarea', 'image']) ? 2 : 1,
            ]);
    }

    protected function selectOptions(string $key): array
    {
        return match ($key) {
            'default_currency' => ['USD' => 'USD', 'EUR' => 'EUR', 'GBP' => 'GBP', 'NGN' => 'NGN'],
            'default_timezone' => collect(timezone_identifiers_list())->mapWithKeys(fn($tz) => [$tz => $tz])->toArray(),
            'default_language' => ['en' => 'English', 'fr' => 'French', 'es' => 'Spanish', 'de' => 'German'],
            'default_theme' => ['light' => 'Light', 'dark' => 'Dark'],
            'first_deposit_bonus_type' => ['fixed' => 'Fixed amount', 'percentage' => 'Percentage'],
            'mail_driver' => ['smtp' => 'SMTP', 'sendmail' => 'Sendmail', 'log' => 'Log'],
            default => [],
        };
    }

    public function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema($this->getFormSchema())
            ->statePath('data');
    }

    public function save(): void
    {
        $state = $this->form->getState();

        $imageKeys = Setting::where('type', 'image')->pluck('key');

        foreach ($imageKeys as $key) {
            $old = Setting::where('key', $key)->value('value');
            $new = $state[$key] ?? null;

            if ($old && $new && $old !== $new) {
                $path = public_path($old);
                if (File::exists($path)) {
                    File::delete($path);
                }
            }
        }

        foreach ($state as $key => $value) {
            Setting::where('key', $key)->update(['value' => $value]);
        }

        Notification::make()
            ->success()
            ->title('Settings saved')
            ->send();
    }
}
