<?php

namespace App\Filament\Admin\Resources;

use App\Enums\KycStatus;
use App\Models\KycDocument;
use App\Models\Notification;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification as FilamentNotification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Filament\Admin\Resources\KycDocumentResource\Pages;

class KycDocumentResource extends Resource
{
    protected static ?string $model = KycDocument::class;
    protected static ?string $navigationIcon = 'heroicon-o-identification';
    protected static ?string $navigationGroup = 'Compliance';
    protected static ?int $navigationSort = 2;
    protected static ?string $navigationLabel = 'KYC Submissions';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('status')
                ->options([
                    'unverified' => 'Unverified',
                    'pending' => 'Pending',
                    'verified' => 'Verified',
                    'rejected' => 'Rejected',
                ])
                ->required(),

            Forms\Components\Textarea::make('rejection_reason')
                ->visible(fn (Forms\Get $get) => $get('status') === 'rejected')
                ->required(fn (Forms\Get $get) => $get('status') === 'rejected'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(KycDocument::query()->with(['user', 'kyc'])->latest())
            ->columns([
                 Tables\Columns\ImageColumn::make('user.avatar')
                    ->label('')
                    ->getStateUsing(fn ($record) => $record->user?->avatar
                        ? asset($record->user->avatar)
                        : null)
                    ->defaultImageUrl(function ($record) {
                        $name = $record->user?->name ?: 'N';
                        $initials = Str::of($name)
                            ->trim()
                            ->explode(' ')
                            ->take(2)
                            ->map(fn ($word) => Str::substr($word, 0, 1))
                            ->implode('');

                        return 'https://ui-avatars.com/api/?name=' . urlencode($initials ?: 'N') . '&background=e5e7eb&color=6b7280';
                    })
                    ->circular(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('User')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('kyc.type')
                    ->label('Document type')
                    ->badge(),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn ($state) => match ($state->value ?? $state) {
                        'verified' => 'success',
                        'pending' => 'warning',
                        'rejected' => 'danger',
                        'unverified' => 'gray',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Submitted')
                    ->dateTime('M j, Y g:i A')
                    ->sortable(), 
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'verified' => 'Verified',
                        'rejected' => 'Rejected',
                    ]),

                Tables\Filters\SelectFilter::make('kyc_id')
                    ->label('Document type')
                    ->relationship('kyc', 'type'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),

                Tables\Actions\Action::make('approve')
                    ->label('Approve')
                    ->icon('heroicon-m-check-circle')
                    ->color('success')
                    ->visible(fn (KycDocument $record) => $record->status === KycStatus::Pending)
                    ->requiresConfirmation()
                    ->modalDescription('Approve this KYC submission and mark the user as verified?')
                    ->action(fn (KycDocument $record) => static::approve($record)),

                Tables\Actions\Action::make('reject')
                    ->label('Reject')
                    ->icon('heroicon-m-x-circle')
                    ->color('danger')
                    ->visible(fn (KycDocument $record) => $record->status === KycStatus::Pending)
                    ->form([
                        Forms\Components\Textarea::make('rejection_reason')
                            ->label('Reason for rejection')
                            ->required(),
                    ])
                    ->action(fn (KycDocument $record, array $data) => static::reject($record, $data['rejection_reason'])),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function approve(KycDocument $document): void
    {
        DB::transaction(function () use ($document) {
            $document->update([
                'status' => KycStatus::Verified,
                'verified_at' => now(),
                'rejection_reason' => null,
            ]);

            $document->user->update(['kyc_status' => 'approved']);

            Notification::create([
                'user_id' => $document->user_id,
                'title' => 'Identity verified',
                'body' => 'Your ' . ($document->kyc->type ?? 'identity') . ' submission has been approved. Your account is now verified.',
                'type' => 'success',
                'is_read' => false,
            ]);
        });

        FilamentNotification::make()
            ->success()
            ->title('KYC approved')
            ->body('The ' . ($document->kyc->type ?? 'identity') . ' submission was approved successfully.')
            ->send();
    }

    public static function reject(KycDocument $document, string $reason): void
    {
        DB::transaction(function () use ($document, $reason) {
            $document->update([
                'status' => KycStatus::Rejected,
                'rejection_reason' => $reason,
                'verified_at' => null,
            ]);

            $document->user->update(['kyc_status' => 'rejected']);

            Notification::create([
                'user_id' => $document->user_id,
                'title' => 'Identity verification rejected',
                'body' => 'Your ' . ($document->kyc->type ?? 'identity') . " submission was rejected. Reason: {$reason}. You may resubmit with corrected information.",
                'type' => 'error',
                'is_read' => false,
            ]);
        });

        FilamentNotification::make()
            ->danger()
            ->title('KYC rejected')
            ->body('The ' . ($document->kyc->type ?? 'identity') . ' submission was rejected successfully.')
            ->send();
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListKycDocuments::route('/'),
            'view' => Pages\ViewKycDocument::route('/{record}'),
        ];
    }
}