<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\MailTemplate;

class MailsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        MailTemplate::firstOrCreate(['name' => 'withdraw_approved'], [
            'name' => 'withdraw_approved',
            'subject' => 'Withdrawal Successful - {{ $amount }}',
            'body' => <<<'BLADE'
<div style="margin:0;padding:0;background:#f4f6f9;font-family:'Segoe UI', Arial, sans-serif;">

    <table width="100%" cellpadding="0" cellspacing="0" style="padding:30px 0;">
        <tr>
            <td align="center">

                <table width="100%" cellpadding="0" cellspacing="0" style="max-width:600px;background:#ffffff;border-radius:14px;overflow:hidden;box-shadow:0 8px 20px rgba(0,0,0,0.05);">

                    <tr>
                        <td style="background:#2563eb;padding:25px;text-align:center;color:#fff;">
                            <h1 style="margin:0;font-size:22px;">Withdrawal Approved</h1>
                            <p style="margin:6px 0 0;font-size:13px;opacity:0.9;">Your withdrawal has been successfully processed</p>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:30px 25px;color:#333;">

                            <h2 style="margin-top:0;font-size:20px;color:#2c3e50;">Hello {{ $name }},</h2>

                            <p style="font-size:15px;color:#555;line-height:1.6;">
                                Good news! Your withdrawal request has been approved and processed successfully.
                            </p>

                            <div style="margin:20px 0;padding:18px;background:#eff6ff;border-left:5px solid #2563eb;border-radius:8px;text-align:center;">
                                <p style="margin:0;font-size:13px;color:#888;">AMOUNT WITHDRAWN</p>
                                <p style="margin:6px 0 0;font-size:22px;font-weight:bold;color:#2563eb;">{{ $amount }}</p>
                            </div>

                            <table width="100%" cellpadding="0" cellspacing="0" style="margin-top:10px;font-size:14px;">
                                <tr>
                                    <td style="padding:10px 0;border-bottom:1px solid #eee;color:#555;"><strong>Reference</strong></td>
                                    <td style="padding:10px 0;border-bottom:1px solid #eee;color:#2c3e50;">{{ $reference }}</td>
                                </tr>
                                <tr>
                                    <td style="padding:10px 0;border-bottom:1px solid #eee;color:#555;"><strong>Date</strong></td>
                                    <td style="padding:10px 0;border-bottom:1px solid #eee;color:#2c3e50;">{{ $date }}</td>
                                </tr>
                            </table>

                            <p style="margin-top:20px;font-size:14px;color:#666;line-height:1.6;">
                                You can check your account for the updated balance and transaction record.
                            </p>

                            <div style="margin-top:25px;text-align:center;">
                                <a href="{{ $dashboard_url ?? '#' }}" style="display:inline-block;padding:12px 22px;background:#2563eb;color:#fff;text-decoration:none;border-radius:6px;font-size:14px;">View Account</a>
                            </div>

                        </td>
                    </tr>

                    <tr>
                        <td style="background:#f1f3f5;padding:18px;text-align:center;font-size:12px;color:#777;">
                            <p style="margin:0;">This transaction was processed by <strong>{{ config('app.name') }}</strong></p>
                            <p style="margin:5px 0 0;">© {{ date('Y') }} All rights reserved</p>
                        </td>
                    </tr>

                </table>

            </td>
        </tr>
    </table>

</div>
BLADE,
            'is_active' => true,
        ]);

        MailTemplate::firstOrCreate(['name' => 'withdraw_failed'], [
            'name' => 'withdraw_failed',
            'subject' => 'Withdrawal Failed - Action Required',
            'body' => <<<'BLADE'
<div style="margin:0;padding:0;background:#fef2f2;font-family:'Segoe UI', Arial, sans-serif;">

    <table width="100%" cellpadding="0" cellspacing="0" style="padding:30px 0;">
        <tr>
            <td align="center">

                <table width="100%" cellpadding="0" cellspacing="0" style="max-width:600px;background:#ffffff;border-radius:14px;overflow:hidden;box-shadow:0 8px 20px rgba(0,0,0,0.05);">

                    <tr>
                        <td style="background:#dc2626;padding:25px;text-align:center;color:#fff;">
                            <h1 style="margin:0;font-size:22px;">Withdrawal Failed</h1>
                            <p style="margin:6px 0 0;font-size:13px;opacity:0.9;">Your withdrawal could not be processed</p>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:30px 25px;color:#333;">

                            <h2 style="margin-top:0;font-size:20px;color:#2c3e50;">Hello {{ $name }},</h2>

                            <p style="font-size:15px;color:#555;line-height:1.6;">
                                Unfortunately, your withdrawal request could not be completed.
                            </p>

                            <div style="margin:20px 0;padding:18px;background:#fef2f2;border-left:5px solid #dc2626;border-radius:8px;text-align:center;">
                                <p style="margin:0;font-size:13px;color:#888;">AMOUNT REQUESTED</p>
                                <p style="margin:6px 0 0;font-size:22px;font-weight:bold;color:#dc2626;">{{ $amount }}</p>
                            </div>

                            <table width="100%" cellpadding="0" cellspacing="0" style="margin-top:10px;font-size:14px;">
                                <tr>
                                    <td style="padding:10px 0;border-bottom:1px solid #eee;color:#555;"><strong>Reference</strong></td>
                                    <td style="padding:10px 0;border-bottom:1px solid #eee;color:#2c3e50;">{{ $reference }}</td>
                                </tr>
                                <tr>
                                    <td style="padding:10px 0;border-bottom:1px solid #eee;color:#555;"><strong>Reason</strong></td>
                                    <td style="padding:10px 0;border-bottom:1px solid #eee;color:#2c3e50;">{{ $reason ?? 'Unknown error' }}</td>
                                </tr>
                            </table>

                            <p style="margin-top:20px;font-size:14px;color:#666;line-height:1.6;">
                                Please try again or contact support if you need help.
                            </p>

                        </td>
                    </tr>

                    <tr>
                        <td style="background:#f1f3f5;padding:18px;text-align:center;font-size:12px;color:#777;">
                            <p style="margin:0;">This transaction was processed by <strong>{{ config('app.name') }}</strong></p>
                            <p style="margin:5px 0 0;">© {{ date('Y') }} All rights reserved</p>
                        </td>
                    </tr>

                </table>

            </td>
        </tr>
    </table>

</div>
BLADE,
            'is_active' => true,
        ]);

        MailTemplate::firstOrCreate(['name' => 'investment_profit_added'], [
            'name' => 'investment_profit_added',
            'subject' => 'New Profit Added - {{ $profit_amount }}',
            'body' => <<<'BLADE'
<div style="margin:0;padding:0;background:#f4f6f9;font-family:'Segoe UI', Arial, sans-serif;">

    <table width="100%" cellpadding="0" cellspacing="0" style="padding:30px 0;">
        <tr>
            <td align="center">

                <table width="100%" cellpadding="0" cellspacing="0" style="max-width:600px;background:#ffffff;border-radius:14px;overflow:hidden;box-shadow:0 8px 20px rgba(0,0,0,0.05);">

                    <tr>
                        <td style="background:#16a34a;padding:25px;text-align:center;color:#fff;">
                            <h1 style="margin:0;font-size:22px;">Profit Added</h1>
                            <p style="margin:6px 0 0;font-size:13px;opacity:0.9;">A new profit has been credited to your investment</p>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:30px 25px;color:#333;">

                            <h2 style="margin-top:0;font-size:20px;color:#2c3e50;">Hello {{ $name }},</h2>

                            <p style="font-size:15px;color:#555;line-height:1.6;">
                                We have added a new profit record to your investment account.
                            </p>

                            <div style="margin:20px 0;padding:18px;background:#f0fdf4;border-left:5px solid #16a34a;border-radius:8px;text-align:center;">
                                <p style="margin:0;font-size:13px;color:#888;">PROFIT AMOUNT</p>
                                <p style="margin:6px 0 0;font-size:22px;font-weight:bold;color:#16a34a;">{{ $profit_amount }}</p>
                            </div>

                            <table width="100%" cellpadding="0" cellspacing="0" style="margin-top:10px;font-size:14px;">
                                <tr>
                                    <td style="padding:10px 0;border-bottom:1px solid #eee;color:#555;"><strong>Investment</strong></td>
                                    <td style="padding:10px 0;border-bottom:1px solid #eee;color:#2c3e50;">{{ $investment_name ?? 'Investment' }}</td>
                                </tr>
                                <tr>
                                    <td style="padding:10px 0;border-bottom:1px solid #eee;color:#555;"><strong>Percentage</strong></td>
                                    <td style="padding:10px 0;border-bottom:1px solid #eee;color:#2c3e50;">{{ $percentage ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td style="padding:10px 0;border-bottom:1px solid #eee;color:#555;"><strong>Source</strong></td>
                                    <td style="padding:10px 0;border-bottom:1px solid #eee;color:#2c3e50;">{{ $source ?? 'System' }}</td>
                                </tr>
                                <tr>
                                    <td style="padding:10px 0;border-bottom:1px solid #eee;color:#555;"><strong>Description</strong></td>
                                    <td style="padding:10px 0;border-bottom:1px solid #eee;color:#2c3e50;">{{ $description ?? 'Profit added' }}</td>
                                </tr>
                            </table>

                            <p style="margin-top:20px;font-size:14px;color:#666;line-height:1.6;">
                                You can review this update in your account dashboard.
                            </p>

                            <div style="margin-top:25px;text-align:center;">
                                <a href="{{ $dashboard_url ?? '#' }}" style="display:inline-block;padding:12px 22px;background:#16a34a;color:#fff;text-decoration:none;border-radius:6px;font-size:14px;">View Account</a>
                            </div>

                        </td>
                    </tr>

                    <tr>
                        <td style="background:#f1f3f5;padding:18px;text-align:center;font-size:12px;color:#777;">
                            <p style="margin:0;">This update was processed by <strong>{{ config('app.name') }}</strong></p>
                            <p style="margin:5px 0 0;">© {{ date('Y') }} All rights reserved</p>
                        </td>
                    </tr>

                </table>

            </td>
        </tr>
    </table>

</div>
BLADE,
            'is_active' => true,
        ]);

        MailTemplate::firstOrCreate(['name' => 'kyc_approved'], [
            'name' => 'kyc_approved',
            'subject' => 'KYC Approved - Verification Successful',
            'body' => <<<'BLADE'
<div style="margin:0;padding:0;background:#f4f7fb;font-family:Segoe UI,Arial,sans-serif;">

<table width="100%" cellpadding="0" cellspacing="0" style="padding:40px 15px;">
<tr>
<td align="center">

<table width="100%" cellpadding="0" cellspacing="0" style="max-width:650px;background:#ffffff;border-radius:18px;overflow:hidden;box-shadow:0 10px 30px rgba(0,0,0,0.08);">

<tr>
<td style="background:linear-gradient(135deg,#16a34a,#22c55e);padding:35px 30px;text-align:center;color:#fff;">

<h1 style="margin:0;font-size:28px;font-weight:700;">KYC Approved</h1>

<p style="margin:10px 0 0;font-size:15px;opacity:.9;">Your identity verification has been successfully approved</p>

</td>
</tr>

<tr>
<td style="padding:40px 35px;">

<h2 style="margin-top:0;color:#1e293b;font-size:22px;">Hello {{ $name }},</h2>

<p style="font-size:15px;line-height:1.8;color:#475569;">
We are pleased to inform you that your KYC submission has been reviewed and approved.
</p>

<div style="margin-top:30px;background:#f8fafc;border:1px solid #e2e8f0;border-radius:14px;padding:25px;">

<h3 style="margin-top:0;color:#0f172a;font-size:18px;">Verification Details</h3>

<table width="100%" cellpadding="0" cellspacing="0" style="margin-top:15px;">
<tr>
<td style="padding:10px 0;color:#64748b;font-size:14px;">Status</td>
<td align="right" style="padding:10px 0;">
<span style="background:#dcfce7;color:#166534;padding:6px 14px;border-radius:50px;font-size:12px;font-weight:700;">APPROVED</span>
</td>
</tr>
<tr>
<td style="padding:10px 0;color:#64748b;font-size:14px;">Date</td>
<td align="right" style="padding:10px 0;color:#0f172a;font-weight:700;font-size:15px;">{{ $date }}</td>
</tr>
</table>

</div>

<div style="margin-top:35px;text-align:center;">

<a href="{{ $dashboard_url }}" style="display:inline-block;background:linear-gradient(135deg,#16a34a,#22c55e);color:#fff;padding:14px 30px;text-decoration:none;border-radius:10px;font-size:15px;font-weight:600;">View Dashboard</a>

</div>

<p style="margin-top:35px;font-size:14px;line-height:1.7;color:#64748b;">
Thank you for completing your verification. If you have any questions, please contact support.
</p>

</td>
</tr>

<tr>
<td style="background:#f8fafc;padding:25px;text-align:center;border-top:1px solid #e2e8f0;">

<p style="margin:0;color:#94a3b8;font-size:13px;">© {{ date("Y") }} {{ config("app.name") }}. All rights reserved.</p>

</td>
</tr>

</table>

</td>
</tr>
</table>

</div>
BLADE,
            'is_active' => true,
        ]);

        MailTemplate::firstOrCreate(['name' => 'kyc_rejected'], [
            'name' => 'kyc_rejected',
            'subject' => 'KYC Rejected - Action Required',
            'body' => <<<'BLADE'
<div style="margin:0;padding:0;background:#fef2f2;font-family:Segoe UI,Arial,sans-serif;">

<table width="100%" cellpadding="0" cellspacing="0" style="padding:40px 15px;">
<tr>
<td align="center">

<table width="100%" cellpadding="0" cellspacing="0" style="max-width:650px;background:#ffffff;border-radius:18px;overflow:hidden;box-shadow:0 10px 30px rgba(0,0,0,0.08);">

<tr>
<td style="background:linear-gradient(135deg,#dc2626,#ef4444);padding:35px 30px;text-align:center;color:#fff;">

<h1 style="margin:0;font-size:28px;font-weight:700;">KYC Rejected</h1>

<p style="margin:10px 0 0;font-size:15px;opacity:.9;">Your identity verification needs attention</p>

</td>
</tr>

<tr>
<td style="padding:40px 35px;">

<h2 style="margin-top:0;color:#1e293b;font-size:22px;">Hello {{ $name }},</h2>

<p style="font-size:15px;line-height:1.8;color:#475569;">
Unfortunately, your KYC submission was not approved.
</p>

<div style="margin-top:30px;background:#f8fafc;border:1px solid #e2e8f0;border-radius:14px;padding:25px;">

<h3 style="margin-top:0;color:#0f172a;font-size:18px;">Review Details</h3>

<table width="100%" cellpadding="0" cellspacing="0" style="margin-top:15px;">
<tr>
<td style="padding:10px 0;color:#64748b;font-size:14px;">Status</td>
<td align="right" style="padding:10px 0;">
<span style="background:#fee2e2;color:#991b1b;padding:6px 14px;border-radius:50px;font-size:12px;font-weight:700;">REJECTED</span>
</td>
</tr>
<tr>
<td style="padding:10px 0;color:#64748b;font-size:14px;">Reason</td>
<td align="right" style="padding:10px 0;color:#0f172a;font-weight:700;font-size:15px;">{{ $reason ?? 'Not specified' }}</td>
</tr>
<tr>
<td style="padding:10px 0;color:#64748b;font-size:14px;">Date</td>
<td align="right" style="padding:10px 0;color:#0f172a;font-weight:700;font-size:15px;">{{ $date }}</td>
</tr>
</table>

</div>

<div style="margin-top:35px;text-align:center;">

<a href="{{ $dashboard_url }}" style="display:inline-block;background:linear-gradient(135deg,#dc2626,#ef4444);color:#fff;padding:14px 30px;text-decoration:none;border-radius:10px;font-size:15px;font-weight:600;">View Dashboard</a>

</div>

<p style="margin-top:35px;font-size:14px;line-height:1.7;color:#64748b;">
Please review the reason above and update your KYC submission if needed.
</p>

</td>
</tr>

<tr>
<td style="background:#fff1f2;padding:25px;text-align:center;border-top:1px solid #e2e8f0;">

<p style="margin:0;color:#94a3b8;font-size:13px;">© {{ date("Y") }} {{ config("app.name") }}. All rights reserved.</p>

</td>
</tr>

</table>

</td>
</tr>
</table>

</div>
BLADE,
            'is_active' => true,
        ]);

        MailTemplate::firstOrCreate(['name' => 'subscriber_notification'], [
            'name' => 'subscriber_notification',
            'subject' => '{{ $subject ?? "Notification" }}',
            'body' => <<<'BLADE'
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ config('app.name') }}</title>
</head>
<body style="margin:0; padding:0; background:#f4f6f9; font-family:Arial, Helvetica, sans-serif;">

    <div style="padding:40px 20px;">
        <div style="max-width:600px; margin:0 auto; background:#ffffff; border-radius:12px; overflow:hidden; box-shadow:0 2px 10px rgba(0,0,0,.08);">

            <div style="background:#2563eb; padding:30px; text-align:center;">
                <h1 style="margin:0; color:#ffffff; font-size:24px;">
                    {{ config('app.name') }}
                </h1>
            </div>

            <div style="padding:35px; color:#374151; line-height:1.8;">

                <h2 style="margin-top:0; color:#111827; font-size:22px;">
                    Hello {{ $name }},
                </h2>

                <div style="font-size:15px;">
                    {!! nl2br(e($message)) !!}
                </div>

                <div style="margin-top:30px;">
                    <p style="margin:0;">Best regards,</p>
                    <p style="margin:5px 0 0; font-weight:bold; color:#111827;">{{ config('app.name') }} Team</p>
                </div>
            </div>

            <div style="height:1px; background:#e5e7eb;"></div>

            <div style="padding:25px; background:#f9fafb; text-align:center;">
                <p style="margin:0; color:#6b7280; font-size:13px;">This email was sent by {{ config('app.name') }}.</p>
                <p style="margin:8px 0 0; color:#9ca3af; font-size:12px;">© {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
            </div>

        </div>
    </div>

</body>
</html>
BLADE,
            'is_active' => true,
        ]);

        MailTemplate::firstOrCreate(['name' => 'balance_updated'], [
            'name' => 'balance_updated',
            'subject' => 'Balance Updated - {{ $amount }}',
            'body' => <<<'BLADE'
<div style="margin:0;padding:0;background:#f4f6f9;font-family:'Segoe UI', Arial, sans-serif;">

    <table width="100%" cellpadding="0" cellspacing="0" style="padding:30px 0;">
        <tr>
            <td align="center">

                <table width="100%" cellpadding="0" cellspacing="0" style="max-width:600px;background:#ffffff;border-radius:14px;overflow:hidden;box-shadow:0 8px 20px rgba(0,0,0,0.05);">

                    <tr>
                        <td style="background:{{ $action === 'add' ? '#16a34a' : '#dc2626' }};padding:25px;text-align:center;color:#fff;">
                            <h1 style="margin:0;font-size:22px;">Balance {{ $action === 'add' ? 'Credited' : 'Debited' }}</h1>
                            <p style="margin:6px 0 0;font-size:13px;opacity:0.9;">
                                Your {{ $wallet }} balance was {{ $action_label ?? 'updated' }} successfully
                            </p>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:30px 25px;color:#333;">

                            <h2 style="margin-top:0;font-size:20px;color:#2c3e50;">
                                Hello {{ $name }},
                            </h2>

                            <p style="font-size:15px;color:#555;line-height:1.6;">
                                Your account balance has been {{ $action_label ?? 'updated' }} by the admin team.
                            </p>

                            <div style="margin:20px 0;padding:18px;background:#f8fafc;border-left:5px solid {{ $action === 'add' ? '#16a34a' : '#dc2626' }};border-radius:8px;text-align:center;">
                                <p style="margin:0;font-size:13px;color:#888;">AMOUNT</p>
                                <p style="margin:6px 0 0;font-size:22px;font-weight:bold;color:{{ $action === 'add' ? '#16a34a' : '#dc2626' }};">
                                    {{ $action === 'add' ? '+' : '-' }}{{ $amount }}
                                </p>
                            </div>

                            <table width="100%" cellpadding="0" cellspacing="0" style="margin-top:10px;font-size:14px;">
                                <tr>
                                    <td style="padding:10px 0;border-bottom:1px solid #eee;color:#555;"><strong>Wallet</strong></td>
                                    <td align="right" style="padding:10px 0;border-bottom:1px solid #eee;color:#2c3e50;">{{ $wallet }}</td>
                                </tr>
                                <tr>
                                    <td style="padding:10px 0;border-bottom:1px solid #eee;color:#555;"><strong>Reference</strong></td>
                                    <td align="right" style="padding:10px 0;border-bottom:1px solid #eee;color:#2c3e50;">{{ $reference }}</td>
                                </tr>
                                <tr>
                                    <td style="padding:10px 0;border-bottom:1px solid #eee;color:#555;"><strong>New Balance</strong></td>
                                    <td align="right" style="padding:10px 0;border-bottom:1px solid #eee;color:#2c3e50;">{{ $balance }}</td>
                                </tr>
                                <tr>
                                    <td style="padding:10px 0;border-bottom:1px solid #eee;color:#555;"><strong>Note</strong></td>
                                    <td align="right" style="padding:10px 0;border-bottom:1px solid #eee;color:#2c3e50;">{{ $remark ?? 'Admin balance update' }}</td>
                                </tr>
                            </table>

                            <div style="margin-top:25px;text-align:center;">
                                <a href="{{ $dashboard_url ?? '#' }}" style="display:inline-block;padding:12px 22px;background:#2563eb;color:#fff;text-decoration:none;border-radius:6px;font-size:14px;">View Dashboard</a>
                            </div>

                        </td>
                    </tr>

                    <tr>
                        <td style="background:#f1f3f5;padding:18px;text-align:center;font-size:12px;color:#777;">
                            <p style="margin:0;">This update was processed by <strong>{{ config('app.name') }}</strong></p>
                            <p style="margin:5px 0 0;">© {{ date('Y') }} All rights reserved</p>
                        </td>
                    </tr>

                </table>

            </td>
        </tr>
    </table>

</div>
BLADE,
            'is_active' => true,
        ]);

        MailTemplate::firstOrCreate(['name' => 'email_verification'], [
            'name' => 'email_verification',
            'subject' => 'Verify Your Email Address',
            'body' => <<<'BLADE'
<div style="margin:0;padding:0;background:#f4f6f9;font-family:'Segoe UI', Arial, sans-serif;">
    <table width="100%" cellpadding="0" cellspacing="0" style="padding:30px 0;">
        <tr>
            <td align="center">
                <table width="100%" cellpadding="0" cellspacing="0" style="max-width:600px;background:#ffffff;border-radius:14px;overflow:hidden;box-shadow:0 8px 20px rgba(0,0,0,0.05);">
                    <tr>
                        <td style="background:#2563eb;padding:25px;text-align:center;color:#fff;">
                            <h1 style="margin:0;font-size:22px;">Verify Your Email</h1>
                            <p style="margin:6px 0 0;font-size:13px;opacity:0.9;">Confirm your email address to activate your account</p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:30px 25px;color:#333;">
                            <h2 style="margin-top:0;font-size:20px;color:#2c3e50;">Hello {{ $name }},</h2>
                            <p style="font-size:15px;color:#555;line-height:1.6;">Thanks for registering with us. Please verify your email address by clicking the button below.</p>
                            <div style="margin-top:25px;text-align:center;">
                                <a href="{{ $verification_url }}" style="display:inline-block;padding:12px 22px;background:#2563eb;color:#fff;text-decoration:none;border-radius:6px;font-size:14px;">Verify Email Address</a>
                            </div>
                            <p style="margin-top:20px;font-size:14px;color:#666;line-height:1.6;">If you did not create this account, you can safely ignore this email.</p>
                        </td>
                    </tr>
                    <tr>
                        <td style="background:#f1f3f5;padding:18px;text-align:center;font-size:12px;color:#777;">
                            <p style="margin:0;">This email was sent by <strong>{{ config('app.name') }}</strong></p>
                            <p style="margin:5px 0 0;">© {{ date('Y') }} All rights reserved</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</div>
BLADE,
            'is_active' => true,
        ]);

        MailTemplate::firstOrCreate(['name' => 'account_created'], [
            'name' => 'account_created',
            'subject' => 'Welcome to {{ config("app.name") }} 🎉',
            'body' => <<<'BLADE'
<div style="margin:0;padding:0;background:#f4f6f9;font-family:'Segoe UI', Arial, sans-serif;">
    <table width="100%" cellpadding="0" cellspacing="0" style="padding:30px 0;">
        <tr>
            <td align="center">
                <table width="100%" cellpadding="0" cellspacing="0" style="max-width:600px;background:#ffffff;border-radius:14px;overflow:hidden;box-shadow:0 8px 20px rgba(0,0,0,0.05);">
                    <tr>
                        <td style="background:#16a34a;padding:25px;text-align:center;color:#fff;">
                            <h1 style="margin:0;font-size:22px;">🎉 Welcome Aboard!</h1>
                            <p style="margin:6px 0 0;font-size:13px;opacity:0.9;">Your account has been successfully created</p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:30px 25px;color:#333;">
                            <h2 style="margin-top:0;font-size:20px;color:#2c3e50;">Hello {{ $name }},</h2>
                            <p style="font-size:15px;color:#555;line-height:1.6;">We’re excited to have you on board! Your account has been created successfully. You can now start exploring and enjoying all the features available on our platform.</p>
                            <table width="100%" cellpadding="0" cellspacing="0" style="margin-top:10px;font-size:14px;">
                                <tr>
                                    <td style="padding:10px 0;border-bottom:1px solid #eee;color:#555;"><strong>Email</strong></td>
                                    <td align="right" style="padding:10px 0;border-bottom:1px solid #eee;color:#2c3e50;">{{ $email }}</td>
                                </tr>
                                <tr>
                                    <td style="padding:10px 0;border-bottom:1px solid #eee;color:#555;"><strong>Username</strong></td>
                                    <td align="right" style="padding:10px 0;border-bottom:1px solid #eee;color:#2c3e50;">{{ $username ?? '-' }}</td>
                                </tr>
                            </table>
                            <div style="margin-top:25px;text-align:center;">
                                <a href="{{ $login_url ?? route('login') }}" style="display:inline-block;padding:12px 22px;background:#16a34a;color:#fff;text-decoration:none;border-radius:6px;font-size:14px;">Login to Your Account</a>
                            </div>
                            <p style="margin-top:20px;font-size:14px;color:#666;line-height:1.6;">If you did not create this account, please contact our support team immediately.</p>
                        </td>
                    </tr>
                    <tr>
                        <td style="background:#f1f3f5;padding:18px;text-align:center;font-size:12px;color:#777;">
                            <p style="margin:0;">© {{ date('Y') }} <strong>{{ config('app.name') }}</strong>. All rights reserved.</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</div>
BLADE,
            'is_active' => true,
        ]);

        MailTemplate::firstOrCreate(['name' => 'referral_bonus'], [
            'name' => 'referral_bonus',
            'subject' => 'Referral Bonus Credited - {{ $amount }}',
            'body' => <<<'BLADE'
<div style="margin:0;padding:0;background:#f4f6f9;font-family:'Segoe UI', Arial, sans-serif;">
    <table width="100%" cellpadding="0" cellspacing="0" style="padding:30px 0;">
        <tr>
            <td align="center">
                <table width="100%" cellpadding="0" cellspacing="0" style="max-width:600px;background:#ffffff;border-radius:14px;overflow:hidden;box-shadow:0 8px 20px rgba(0,0,0,0.05);">
                    <tr>
                        <td style="background:#0f766e;padding:25px;text-align:center;color:#fff;">
                            <h1 style="margin:0;font-size:22px;">Referral Bonus Added</h1>
                            <p style="margin:6px 0 0;font-size:13px;opacity:0.9;">Your referral reward has been credited</p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:30px 25px;color:#333;">
                            <h2 style="margin-top:0;font-size:20px;color:#2c3e50;">Hello {{ $name }},</h2>
                            <p style="font-size:15px;color:#555;line-height:1.6;">Thank you for referring a new user. Your referral bonus has been added to your balance.</p>
                            <div style="margin:20px 0;padding:18px;background:#ecfeff;border-left:5px solid #0f766e;border-radius:8px;text-align:center;">
                                <p style="margin:0;font-size:13px;color:#888;">BONUS CREDITED</p>
                                <p style="margin:6px 0 0;font-size:22px;font-weight:bold;color:#0f766e;">{{ $amount }}</p>
                            </div>
                            <table width="100%" cellpadding="0" cellspacing="0" style="margin-top:10px;font-size:14px;">
                                <tr>
                                    <td style="padding:10px 0;border-bottom:1px solid #eee;color:#555;"><strong>Referred User</strong></td>
                                    <td align="right" style="padding:10px 0;border-bottom:1px solid #eee;color:#2c3e50;">{{ $referred_user_email }}</td>
                                </tr>
                                <tr>
                                    <td style="padding:10px 0;border-bottom:1px solid #eee;color:#555;"><strong>New Balance</strong></td>
                                    <td align="right" style="padding:10px 0;border-bottom:1px solid #eee;color:#2c3e50;">{{ $new_balance }}</td>
                                </tr>
                            </table>
                            <div style="margin-top:25px;text-align:center;">
                                <a href="{{ $dashboard_url ?? '#' }}" style="display:inline-block;padding:12px 22px;background:#0f766e;color:#fff;text-decoration:none;border-radius:6px;font-size:14px;">View Account</a>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td style="background:#f1f3f5;padding:18px;text-align:center;font-size:12px;color:#777;">
                            <p style="margin:0;">This update was processed by <strong>{{ config('app.name') }}</strong></p>
                            <p style="margin:5px 0 0;">© {{ date('Y') }} All rights reserved</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</div>
BLADE,
            'is_active' => true,
        ]);

        MailTemplate::firstOrCreate(['name' => 'investment_created'], [
            'name' => 'investment_created',
            'subject' => 'Investment Created - {{ $amount }}',
            'body' => <<<'BLADE'
<div style="margin:0;padding:0;background:#f4f6f9;font-family:'Segoe UI', Arial, sans-serif;">
    <table width="100%" cellpadding="0" cellspacing="0" style="padding:30px 0;">
        <tr>
            <td align="center">
                <table width="100%" cellpadding="0" cellspacing="0" style="max-width:600px;background:#ffffff;border-radius:14px;overflow:hidden;box-shadow:0 8px 20px rgba(0,0,0,0.05);">
                    <tr>
                        <td style="background:#1d4ed8;padding:25px;text-align:center;color:#fff;">
                            <h1 style="margin:0;font-size:22px;">Investment Started</h1>
                            <p style="margin:6px 0 0;font-size:13px;opacity:0.9;">Your investment has been created successfully</p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:30px 25px;color:#333;">
                            <h2 style="margin-top:0;font-size:20px;color:#2c3e50;">Hello {{ $name }},</h2>
                            <p style="font-size:15px;color:#555;line-height:1.6;">Your investment in <strong>{{ $plan_name }}</strong> is now active. We’ve saved the details below for your records.</p>

                            <table width="100%" cellpadding="0" cellspacing="0" style="margin-top:10px;font-size:14px;">
                                <tr>
                                    <td style="padding:10px 0;border-bottom:1px solid #eee;color:#555;"><strong>Plan</strong></td>
                                    <td align="right" style="padding:10px 0;border-bottom:1px solid #eee;color:#2c3e50;">{{ $plan_name }}</td>
                                </tr>
                                <tr>
                                    <td style="padding:10px 0;border-bottom:1px solid #eee;color:#555;"><strong>Amount</strong></td>
                                    <td align="right" style="padding:10px 0;border-bottom:1px solid #eee;color:#2c3e50;">{{ $amount }}</td>
                                </tr>
                                <tr>
                                    <td style="padding:10px 0;border-bottom:1px solid #eee;color:#555;"><strong>Expected Profit</strong></td>
                                    <td align="right" style="padding:10px 0;border-bottom:1px solid #eee;color:#2c3e50;">{{ $expected_profit }}</td>
                                </tr>
                                <tr>
                                    <td style="padding:10px 0;border-bottom:1px solid #eee;color:#555;"><strong>Maturity Date</strong></td>
                                    <td align="right" style="padding:10px 0;border-bottom:1px solid #eee;color:#2c3e50;">{{ $maturity_date }}</td>
                                </tr>
                                <tr>
                                    <td style="padding:10px 0;border-bottom:1px solid #eee;color:#555;"><strong>Reference</strong></td>
                                    <td align="right" style="padding:10px 0;border-bottom:1px solid #eee;color:#2c3e50;">{{ $reference }}</td>
                                </tr>
                            </table>

                            <div style="margin-top:25px;text-align:center;">
                                <a href="{{ $dashboard_url ?? '#' }}" style="display:inline-block;padding:12px 22px;background:#1d4ed8;color:#fff;text-decoration:none;border-radius:6px;font-size:14px;">View Investments</a>
                            </div>

                            <p style="margin-top:20px;font-size:14px;color:#666;line-height:1.6;">You will also receive updates as your investment progresses, depending on your notification settings.</p>
                        </td>
                    </tr>
                    <tr>
                        <td style="background:#f1f3f5;padding:18px;text-align:center;font-size:12px;color:#777;">
                            <p style="margin:0;">This email was sent by <strong>{{ config('app.name') }}</strong></p>
                            <p style="margin:5px 0 0;">© {{ date('Y') }} All rights reserved</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</div>
BLADE,
            'is_active' => true,
        ]);

        MailTemplate::firstOrCreate(['name' => 'password_reset_link'], [
            'name' => 'password_reset_link',
            'subject' => 'Password Reset Request',
            'body' => <<<'BLADE'
<div style="margin:0;padding:0;background:#f4f6f9;font-family:'Segoe UI', Arial, sans-serif;">
    <table width="100%" cellpadding="0" cellspacing="0" style="padding:30px 0;">
        <tr>
            <td align="center">
                <table width="100%" cellpadding="0" cellspacing="0" style="max-width:600px;background:#ffffff;border-radius:14px;overflow:hidden;box-shadow:0 8px 20px rgba(0,0,0,0.05);">
                    <tr>
                        <td style="background:#7c3aed;padding:25px;text-align:center;color:#fff;">
                            <h1 style="margin:0;font-size:22px;">Reset Your Password</h1>
                            <p style="margin:6px 0 0;font-size:13px;opacity:0.9;">Use the link below to create a new password</p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:30px 25px;color:#333;">
                            <h2 style="margin-top:0;font-size:20px;color:#2c3e50;">Hello {{ $name }},</h2>
                            <p style="font-size:15px;color:#555;line-height:1.6;">We received a request to reset your password. Click the button below to continue.</p>
                            <div style="margin-top:25px;text-align:center;">
                                <a href="{{ $reset_url }}" style="display:inline-block;padding:12px 22px;background:#7c3aed;color:#fff;text-decoration:none;border-radius:6px;font-size:14px;">Reset Password</a>
                            </div>
                            <p style="margin-top:20px;font-size:14px;color:#666;line-height:1.6;">If you did not request a password reset, you can safely ignore this email.</p>
                        </td>
                    </tr>
                    <tr>
                        <td style="background:#f1f3f5;padding:18px;text-align:center;font-size:12px;color:#777;">
                            <p style="margin:0;">This email was sent by <strong>{{ config('app.name') }}</strong></p>
                            <p style="margin:5px 0 0;">© {{ date('Y') }} All rights reserved</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</div>
BLADE,
            'is_active' => true,
        ]);

        MailTemplate::firstOrCreate(['name' => 'new_ip_login_alert'], [
            'name' => 'new_ip_login_alert',
            'subject' => 'New Login Detected From {{ $current_ip }}',
            'body' => <<<'BLADE'
<div style="margin:0;padding:0;background:#f4f6f9;font-family:'Segoe UI', Arial, sans-serif;">
    <table width="100%" cellpadding="0" cellspacing="0" style="padding:30px 0;">
        <tr>
            <td align="center">
                <table width="100%" cellpadding="0" cellspacing="0" style="max-width:600px;background:#ffffff;border-radius:14px;overflow:hidden;box-shadow:0 8px 20px rgba(0,0,0,0.05);">
                    <tr>
                        <td style="background:#dc2626;padding:25px;text-align:center;color:#fff;">
                            <h1 style="margin:0;font-size:22px;">New Login Alert</h1>
                            <p style="margin:6px 0 0;font-size:13px;opacity:0.9;">A login was detected from a new IP address</p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:30px 25px;color:#333;">
                            <h2 style="margin-top:0;font-size:20px;color:#2c3e50;">Hello {{ $name }},</h2>
                            <p style="font-size:15px;color:#555;line-height:1.6;">We noticed a login to your account from a new IP address. If this was you, you can ignore this message. If not, please review your account security immediately.</p>

                            <table width="100%" cellpadding="0" cellspacing="0" style="margin-top:18px;font-size:14px;">
                                <tr>
                                    <td style="padding:10px 0;border-bottom:1px solid #eee;color:#555;"><strong>Current IP</strong></td>
                                    <td align="right" style="padding:10px 0;border-bottom:1px solid #eee;color:#2c3e50;">{{ $current_ip }}</td>
                                </tr>
                                <tr>
                                    <td style="padding:10px 0;border-bottom:1px solid #eee;color:#555;"><strong>Previous IP</strong></td>
                                    <td align="right" style="padding:10px 0;border-bottom:1px solid #eee;color:#2c3e50;">{{ $previous_ip }}</td>
                                </tr>
                                <tr>
                                    <td style="padding:10px 0;border-bottom:1px solid #eee;color:#555;"><strong>Device</strong></td>
                                    <td align="right" style="padding:10px 0;border-bottom:1px solid #eee;color:#2c3e50;">{{ $device_type }}</td>
                                </tr>
                                <tr>
                                    <td style="padding:10px 0;border-bottom:1px solid #eee;color:#555;"><strong>Location</strong></td>
                                    <td align="right" style="padding:10px 0;border-bottom:1px solid #eee;color:#2c3e50;">{{ $city }}, {{ $country }}</td>
                                </tr>
                                <tr>
                                    <td style="padding:10px 0;border-bottom:1px solid #eee;color:#555;"><strong>Login Time</strong></td>
                                    <td align="right" style="padding:10px 0;border-bottom:1px solid #eee;color:#2c3e50;">{{ $login_time }}</td>
                                </tr>
                            </table>

                            <div style="margin-top:25px;text-align:center;">
                                <a href="{{ $dashboard_url ?? '#' }}" style="display:inline-block;padding:12px 22px;background:#dc2626;color:#fff;text-decoration:none;border-radius:6px;font-size:14px;">Open Dashboard</a>
                            </div>

                            <p style="margin-top:20px;font-size:14px;color:#666;line-height:1.6;">For better security, consider changing your password if you do not recognize this activity.</p>
                        </td>
                    </tr>
                    <tr>
                        <td style="background:#f1f3f5;padding:18px;text-align:center;font-size:12px;color:#777;">
                            <p style="margin:0;">This alert was sent by <strong>{{ config('app.name') }}</strong></p>
                            <p style="margin:5px 0 0;">© {{ date('Y') }} All rights reserved</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</div>
BLADE,
            'is_active' => true,
        ]);
    }
}
