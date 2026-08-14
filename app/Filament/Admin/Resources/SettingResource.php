<?php

namespace App\Filament\Admin\Resources;

use App\Models\Setting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use App\Filament\Admin\Resources\SettingResource\Pages;

class SettingResource extends Resource
{
    protected static ?string $model = Setting::class;
    protected static ?string $navigationIcon = 'heroicon-o-adjustments-horizontal';
    protected static ?string $navigationGroup = 'Settings';
    protected static ?string $navigationLabel = 'All Settings';
    protected static bool $shouldRegisterNavigation = false; // hide from menu, edit via URL if needed
 
    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('key')->required()->unique(ignoreRecord: true),
            Forms\Components\TextInput::make('label'),
            Forms\Components\Textarea::make('description')->rows(2),
            Forms\Components\Select::make('type')
                ->options([
                    'text' => 'Text', 'textarea' => 'Textarea', 'number' => 'Number',
                    'email' => 'Email', 'url' => 'URL', 'password' => 'Password',
                    'boolean' => 'Boolean', 'select' => 'Select', 'color' => 'Color',
                    'image' => 'Image',
                ])
                ->required(),
            Forms\Components\TextInput::make('group')->required(),
            Forms\Components\TextInput::make('value'),
            Forms\Components\Toggle::make('is_public'),
            Forms\Components\TextInput::make('sort_order')->numeric()->default(0),
        ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('key')->searchable(),
                Tables\Columns\TextColumn::make('group')->badge(),
                Tables\Columns\TextColumn::make('type')->badge(),
                Tables\Columns\IconColumn::make('is_public')->boolean(),
                Tables\Columns\TextColumn::make('sort_order'),
            ])
            ->actions([Tables\Actions\EditAction::make(), Tables\Actions\DeleteAction::make()])
            ->reorderable('sort_order');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSettings::route('/'),
            'edit' => Pages\EditSetting::route('/{record}/edit'),
        ];
    }
}