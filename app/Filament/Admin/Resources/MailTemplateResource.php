<?php

namespace App\Filament\Admin\Resources;

use App\Models\MailTemplate;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use App\Filament\Admin\Resources\MailTemplateResource\Pages;

class MailTemplateResource extends Resource
{
    protected static ?string $model = MailTemplate::class;
    protected static ?string $navigationIcon = 'heroicon-o-envelope';
    protected static ?string $navigationGroup = 'Settings';
    protected static ?int $navigationSort = 3;
    protected static ?string $navigationLabel = 'Mail Templates';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Template details')
                ->schema([
                    Forms\Components\TextInput::make('name')
                        ->required()
                        ->maxLength(255)
                        ->unique(ignoreRecord: true)
                        ->helperText('Internal reference name, e.g. "welcome_email", "kyc_approved".'),

                    Forms\Components\TextInput::make('subject')
                        ->required()
                        ->maxLength(255)
                        ->helperText('You can use placeholders like {{ name }} if your mailer supports it.'),

                    Forms\Components\Toggle::make('is_active')
                        ->label('Active')
                        ->default(true)
                        ->helperText('Inactive templates won\'t be used for sending mail.'),
                ])
                ->columns(2),

            Forms\Components\Section::make('Body')
                ->schema([
                    Forms\Components\RichEditor::make('body')
                        ->label('')
                        ->required()
                        ->fileAttachmentsDirectory('mail-template-attachments')
                        ->columnSpanFull(),
                ]),
        ]);
    }

    public static function previewAction(): \Filament\Actions\Action
    {
        return \Filament\Actions\Action::make('preview')
            ->label('Preview')
            ->icon('heroicon-o-eye')
            ->color('gray')
            ->modalHeading('Email preview')
            ->modalSubmitAction(false)
            ->modalCancelActionLabel('Close')
            ->modalWidth('2xl')
            ->action(fn() => null) // no-op, modal just displays content
            ->modalContent(function ($livewire) {
                $data = $livewire->data ?? [];

                return view('filament.admin.mail-preview', [
                    'subject' => $data['subject'] ?? '(No subject)',
                    'body' => $data['body'] ?? '<p>(No content)</p>',
                ]);
            });
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(MailTemplate::query()->latest())
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('subject')
                    ->searchable()
                    ->limit(50),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('M j, Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Updated')
                    ->dateTime('M j, Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMailTemplates::route('/'),
            'create' => Pages\CreateMailTemplate::route('/create'),
            'edit' => Pages\EditMailTemplate::route('/{record}/edit'),
        ];
    }
}
