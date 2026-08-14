<?php

namespace App\Filament\Admin\Resources\MailTemplateResource\Pages;

use App\Filament\Admin\Resources\MailTemplateResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Notifications\Notification; 
use App\Services\MailService;
use Filament\Forms;

class ListMailTemplates extends ListRecords
{
    protected static string $resource = MailTemplateResource::class;

    protected function getHeaderActions(): array
    {
        $mailService = app(MailService::class);
        return [
            Actions\CreateAction::make(),
            Actions\Action::make('send_mail_test')
                ->label('Send mail test')
                ->icon('heroicon-o-envelope')
                ->color('gray')
                ->form([
                    Forms\Components\TextInput::make('email')
                        ->label('To email')
                        ->email()
                        ->required()
                        ->default(auth('admin')->user()?->email ?? setting('mail_from_address', config('mail.from.address'))),

                    Forms\Components\TextInput::make('subject')
                        ->label('Subject')
                        ->required()
                        ->default('Test email from ' . setting('site_title', config('app.name'))),

                    Forms\Components\Textarea::make('message')
                        ->label('Message')
                        ->rows(6)
                        ->required()
                        ->default("Hello,\n\nThis is a test email from " . setting('site_title', config('app.name')) . ".\n\nRegards,\n" . setting('site_title', config('app.name'))),
                ])
                ->action(function (array $data) use ($mailService) {
                    try {
                        $sent = $mailService->sendTestMail(
                            email: $data['email'],
                            name: auth('admin')->user()?->name ?? 'Administrator',
                            subject: $data['subject'],
                            message: $data['message'],
                        );

                        if ($sent) {
                            Notification::make()
                                ->success()
                                ->title('Test email sent')
                                ->body('A test email was sent to ' . $data['email'] . '.')
                                ->send();

                            return;
                        }

                        Notification::make()
                            ->warning()
                            ->title('Mail test skipped')
                            ->body('Mail is disabled or no valid recipient address is configured.')
                            ->send();
                    } catch (\Throwable $e) {
                        Notification::make()
                            ->danger()
                            ->title('Mail test failed')
                            ->body($e->getMessage())
                            ->send();
                    }
                }),
        ];
    }
}
