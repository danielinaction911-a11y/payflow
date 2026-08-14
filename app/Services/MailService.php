<?php

namespace App\Services;

use App\Models\MailTemplate;
use App\Models\User;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Mail;

class MailService
{
    public function sendTemplate(User $user, MailTemplate|string $template, array $data = [], ?string $mailer = null): bool
    {
        $recipientName = $user->name ?? trim(($user->firstname ?? '') . ' ' . ($user->lastname ?? '')) ?: $user->email;

        return $this->sendTemplateToRecipient($user->email, $recipientName, $template, $data, $mailer);
    }

    public function sendTestMail(
        ?string $email = null,
        ?string $name = null,
        ?string $subject = null,
        ?string $message = null,
        ?string $mailer = null
    ): bool {
        $email = $email ?: auth()->user()?->email ?: setting('mail_from_address', config('mail.from.address'));
        $name = $name ?: auth()->user()?->name ?: 'Administrator';

        if (! $email || ! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return false;
        }

        if (! filter_var(setting('mail_enabled', 1), FILTER_VALIDATE_BOOL)) {
            return false;
        }

        $mailer = $mailer ?: $this->resolveMailSetting('mail_driver', 'smtp');
        $this->applyMailerSettings($mailer);

        $template = MailTemplate::query()
            ->where('name', 'test_mail')
            ->where('is_active', true)
            ->first();

        $payload = [
            'name' => $name,
            'email' => $email,
            'dashboard_url' => route('dashboard'),
            'app_name' => setting('site_title', config('app.name')),
            'message' => $message,
            'subject' => $subject,
        ];

        $subject = $subject ?: ($template
            ? Blade::render($template->subject, $payload)
            : sprintf('%s test email', setting('site_title', config('app.name'))));

        $body = $message ?: ($template
            ? Blade::render($template->body, $payload)
            : "Hello {$name},\n\nThis is a test email from " . setting('site_title', config('app.name')) . ". If you received this message, the mail configuration is working correctly.\n\nRegards,\n" . setting('site_title', config('app.name')));

        $this->sendRenderedMail($email, $name, $subject, $body, $mailer);

        return true;
    }

    public function sendTemplateToRecipient(string $email, ?string $name, MailTemplate|string $template, array $data = [], ?string $mailer = null): bool
    {
        if (!filter_var(setting('mail_enabled', 1), FILTER_VALIDATE_BOOL)) {
            return false;
        }

        $template = $template instanceof MailTemplate
            ? $template
            : MailTemplate::where('name', $template)->where('is_active', true)->first();

        if (! $template) {
            return false;
        }

        $mailer = $mailer ?: $this->resolveMailSetting('mail_driver', 'smtp');

        $this->applyMailerSettings($mailer);

        $payload = array_merge([
            'name' => $name ?: $email,
            'email' => $email,
            'dashboard_url' => route('dashboard'),
        ], $data);

        $subject = Blade::render($template->subject, $payload);
        $body = Blade::render($template->body, $payload);

        $this->sendRenderedMail($email, $name, $subject, $body, $mailer);

        return true;
    }

    protected function sendRenderedMail(string $email, ?string $name, string $subject, string $body, string $mailer): void
    {
        Mail::mailer($mailer)->send('emails.template', ['body' => $body], function ($message) use ($email, $name, $subject) {
            $message->to($email, $name)
                ->subject($subject)
                ->from(
                    $this->resolveMailSetting('mail_from_address', config('mail.from.address')),
                    $this->resolveMailSetting('mail_from_name', config('mail.from.name'))
                );

            $replyToAddress = $this->resolveMailSetting('mail_reply_to_address', null);
            if ($replyToAddress) {
                $message->replyTo(
                    $replyToAddress,
                    $this->resolveMailSetting('mail_reply_to_name', $this->resolveMailSetting('mail_from_name', config('mail.from.name')))
                );
            }
        });
    }

    protected function applyMailerSettings(string $mailer): void
    {
        config([
            'mail.default' => $mailer,
            'mail.from.address' => $this->resolveMailSetting('mail_from_address', config('mail.from.address')),
            'mail.from.name' => $this->resolveMailSetting('mail_from_name', config('mail.from.name')),
        ]);

        if ($mailer !== 'smtp') {
            return;
        }

        config([
            'mail.mailers.smtp.host' => $this->resolveMailSetting('mail_host', config('mail.mailers.smtp.host')),
            'mail.mailers.smtp.port' => $this->resolveMailSetting('mail_port', config('mail.mailers.smtp.port')),
            'mail.mailers.smtp.scheme' => $this->resolveMailSetting('mail_encryption', config('mail.mailers.smtp.scheme')),
            'mail.mailers.smtp.username' => $this->resolveMailSetting('mail_username', config('mail.mailers.smtp.username')),
            'mail.mailers.smtp.password' => $this->resolveMailSetting('mail_password', config('mail.mailers.smtp.password')),
        ]);
    }

    protected function resolveMailSetting(string $key, mixed $default = null): mixed
    {
        $value = setting($key, $default);

        return $value === null || $value === '' ? $default : $value;
    }
}