<?php

namespace Tests\Feature;

use App\Models\MailTemplate;
use App\Models\User;
use App\Services\MailService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class MailServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_sends_a_test_email_through_the_mail_service(): void
    {
        Mail::fake();

        MailTemplate::create([
            'name' => 'test_mail',
            'subject' => 'Welcome {{ $name }}',
            'body' => 'Hello {{ $name }}, this is a test email.',
            'is_active' => true,
        ]);

        $user = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
        ]);

        $service = app(MailService::class);
        $sent = $service->sendTestMail($user->email, $user->name, 'array');

        $this->assertTrue($sent);
    }
}
