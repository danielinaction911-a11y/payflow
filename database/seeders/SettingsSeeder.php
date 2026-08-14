<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [

            // ============ SITE IDENTITY ============
            ['key' => 'site_title', 'value' => 'NexVest', 'type' => 'text', 'group' => 'general', 'label' => 'Site Name', 'description' => 'The name of your platform, shown in header and browser tab.', 'sort_order' => 1],
            ['key' => 'site_tagline', 'value' => 'Invest Smarter, Grow Faster', 'type' => 'text', 'group' => 'general', 'label' => 'Site Tagline', 'description' => 'Short tagline shown near the logo or on the landing page.', 'sort_order' => 2],
            ['key' => 'site_logo', 'value' => null, 'type' => 'image', 'group' => 'general', 'label' => 'Site Logo (Light Mode)', 'description' => 'Main logo shown in light theme.', 'sort_order' => 3],
            ['key' => 'site_logo_dark', 'value' => null, 'type' => 'image', 'group' => 'general', 'label' => 'Site Logo (Dark Mode)', 'description' => 'Logo variant shown in dark theme.', 'sort_order' => 4],
            ['key' => 'site_favicon', 'value' => null, 'type' => 'image', 'group' => 'general', 'label' => 'Favicon', 'description' => 'Small icon shown in browser tabs.', 'sort_order' => 5],
            ['key' => 'site_description', 'value' => 'A premium investment and digital banking platform.', 'type' => 'textarea', 'group' => 'general', 'label' => 'Site Description', 'description' => 'Used for SEO meta description and About sections.', 'sort_order' => 6],
            ['key' => 'default_currency', 'value' => 'USD', 'type' => 'select', 'group' => 'general', 'label' => 'Default Currency', 'description' => 'Default currency shown platform-wide.', 'sort_order' => 7],
            ['key' => 'default_currency_symbol', 'value' => '$', 'type' => 'text', 'group' => 'general', 'label' => 'Default Currency Symbol', 'description' => 'Symbol for the default currency.', 'sort_order' => 7.1],
            ['key' => 'default_timezone', 'value' => 'UTC', 'type' => 'select', 'group' => 'general', 'label' => 'Default Timezone', 'description' => 'Default timezone for new accounts.', 'sort_order' => 8],
            ['key' => 'default_language', 'value' => 'en', 'type' => 'select', 'group' => 'general', 'label' => 'Default Language', 'description' => 'Default language for new accounts.', 'sort_order' => 9],
            ['key' => 'maintenance_mode', 'value' => '0', 'type' => 'boolean', 'group' => 'general', 'label' => 'Maintenance Mode', 'description' => 'Temporarily disable public access to the platform.', 'is_public' => false, 'sort_order' => 10],

            // ============ CONTACT INFO ============
            ['key' => 'contact_email', 'value' => 'support@nexvest.com', 'type' => 'email', 'group' => 'contact', 'label' => 'Support Email', 'description' => 'Primary support email address.', 'sort_order' => 1],
            ['key' => 'contact_phone', 'value' => '+1 234 567 8900', 'type' => 'text', 'group' => 'contact', 'label' => 'Support Phone', 'description' => 'Displayed on contact/support pages.', 'sort_order' => 2],
            ['key' => 'contact_address', 'value' => null, 'type' => 'textarea', 'group' => 'contact', 'label' => 'Company Address', 'description' => 'Physical/registered address shown in footer.', 'sort_order' => 3],
            ['key' => 'live_chat_enabled', 'value' => '1', 'type' => 'boolean', 'group' => 'contact', 'label' => 'Enable Live Chat', 'description' => 'Show live chat widget on Help & Support page.', 'sort_order' => 4],
            ['key' => 'chat_plugin_script', 'value' => null, 'type' => 'textarea', 'group' => 'contact', 'label' => 'Chat Plugin Script', 'description' => 'Custom script for integrating third-party chat plugins.', 'sort_order' => 5],

            // ============ SOCIAL LINKS ============
            ['key' => 'social_twitter', 'value' => null, 'type' => 'url', 'group' => 'social', 'label' => 'Twitter / X URL', 'sort_order' => 1],
            ['key' => 'social_facebook', 'value' => null, 'type' => 'url', 'group' => 'social', 'label' => 'Facebook URL', 'sort_order' => 2],
            ['key' => 'social_instagram', 'value' => null, 'type' => 'url', 'group' => 'social', 'label' => 'Instagram URL', 'sort_order' => 3],
            ['key' => 'social_linkedin', 'value' => null, 'type' => 'url', 'group' => 'social', 'label' => 'LinkedIn URL', 'sort_order' => 4],
            ['key' => 'social_telegram', 'value' => null, 'type' => 'url', 'group' => 'social', 'label' => 'Telegram URL', 'sort_order' => 5],
            ['key' => 'social_youtube', 'value' => null, 'type' => 'url', 'group' => 'social', 'label' => 'YouTube URL', 'sort_order' => 6],

            // ============ SEO ============
            ['key' => 'seo_meta_title', 'value' => 'NexVest — Premium Investment Platform', 'type' => 'text', 'group' => 'seo', 'label' => 'Meta Title', 'sort_order' => 1],
            ['key' => 'seo_meta_description', 'value' => 'Trade, invest, and grow your portfolio with NexVest.', 'type' => 'textarea', 'group' => 'seo', 'label' => 'Meta Description', 'sort_order' => 2],
            ['key' => 'seo_meta_keywords', 'value' => 'investment, crypto trading, fintech, portfolio', 'type' => 'text', 'group' => 'seo', 'label' => 'Meta Keywords', 'sort_order' => 3],
            ['key' => 'seo_og_image', 'value' => null, 'type' => 'image', 'group' => 'seo', 'label' => 'Social Share Image (OG Image)', 'sort_order' => 4],
            ['key' => 'google_analytics_id', 'value' => null, 'type' => 'text', 'group' => 'seo', 'label' => 'Google Analytics ID', 'is_public' => false, 'sort_order' => 5],

            // ============ FINANCIAL / PLATFORM RULES ============
            ['key' => 'min_deposit_amount', 'value' => '10', 'type' => 'number', 'group' => 'finance', 'label' => 'Minimum Deposit Amount', 'sort_order' => 1],
            ['key' => 'max_deposit_amount', 'value' => '100000', 'type' => 'number', 'group' => 'finance', 'label' => 'Maximum Deposit Amount', 'sort_order' => 2],
            ['key' => 'min_withdrawal_amount', 'value' => '20', 'type' => 'number', 'group' => 'finance', 'label' => 'Minimum Withdrawal Amount', 'sort_order' => 3],
            ['key' => 'max_withdrawal_amount', 'value' => '50000', 'type' => 'number', 'group' => 'finance', 'label' => 'Maximum Withdrawal Amount', 'sort_order' => 4],
            ['key' => 'deposit_fee_percentage', 'value' => '0', 'type' => 'number', 'group' => 'finance', 'label' => 'Deposit Fee (%)', 'sort_order' => 5],
            ['key' => 'withdrawal_fee_percentage', 'value' => '2', 'type' => 'number', 'group' => 'finance', 'label' => 'Withdrawal Fee (%)', 'sort_order' => 6],
            ['key' => 'transfer_fee_percentage', 'value' => '0', 'type' => 'number', 'group' => 'finance', 'label' => 'Internal Transfer Fee (%)', 'sort_order' => 7],
            ['key' => 'withdrawal_processing_time', 'value' => '24-48 hours', 'type' => 'text', 'group' => 'finance', 'label' => 'Withdrawal Processing Time', 'sort_order' => 8],
            ['key' => 'auto_approve_deposits', 'value' => '0', 'type' => 'boolean', 'group' => 'finance', 'label' => 'Auto-Approve Deposits', 'description' => 'If disabled, all deposits require manual admin approval.', 'is_public' => false, 'sort_order' => 9],
            ['key' => 'auto_approve_withdrawals', 'value' => '0', 'type' => 'boolean', 'group' => 'finance', 'label' => 'Auto-Approve Withdrawals', 'is_public' => false, 'sort_order' => 10],
            ['key' => 'deposits_enabled', 'value' => '1', 'type' => 'boolean', 'group' => 'finance', 'label' => 'Whether deposits are enabled on the platform', 'sort_order' => 11],
            ['key' => 'withdrawals_enabled', 'value' => '1', 'type' => 'boolean', 'group' => 'finance', 'label' => 'Whether withdrawals are enabled on the platform', 'sort_order' => 12],
            ['key' => 'trading_enabled', 'value' => '1', 'type' => 'boolean', 'group' => 'finance', 'label' => 'Whether trading is enabled on the platform', 'sort_order' => 13],
            ['key' => 'wallets_enabled', 'value' => '1', 'type' => 'boolean', 'group' => 'finance', 'label' => 'Whether wallets are enabled on the platform', 'sort_order' => 14],
            ['key' => 'wallet_creation_enabled', 'value' => '1', 'type' => 'boolean', 'group' => 'finance', 'label' => 'Whether wallet creation is enabled on the platform', 'sort_order' => 15],
            ['key' => 'investments_enabled', 'value' => '1', 'type' => 'boolean', 'group' => 'finance', 'label' => 'Whether investments are enabled on the platform', 'sort_order' => 16],
            ['key' => 'create_withdrawal_pin', 'value' => '1', 'type' => 'boolean', 'group' => 'finance', 'label' => 'Require users to create a transaction PIN for withdrawals.', 'sort_order' => 17],
            ['key' => 'cron_investment_returns', 'value' => '1', 'type' => 'boolean', 'group' => 'finance', 'label' => 'Enable Cron for Investment Returns', 'description' => 'If enabled, the system will automatically process investment returns based on the defined schedule.', 'sort_order' => 18],
            ['key' => 'cron_investment_returns_link', 'value' => '* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1', 'type' => 'text', 'group' => 'finance', 'label' => 'Cron Job Link for Investment Returns', 'description' => 'Use this link to set up a cron job for processing investment returns.', 'sort_order' => 19],
            ['key' => 'min_transfer_amount', 'value' => '1', 'type' => 'number', 'group' => 'finance', 'label' => 'Minimum Transfer Amount', 'sort_order' => 20],
            ['key' => 'max_transfer_amount', 'value' => '10000', 'type' => 'number', 'group' => 'finance', 'label' => 'Maximum Transfer Amount', 'sort_order' => 21],
            ['key' => 'trading_fee_percentage', 'value' => '0.5', 'type' => 'number', 'group' => 'finance', 'label' => 'Trading Fee (%)', 'sort_order' => 11],

            ['key' => 'first_deposit_bonus_enabled', 'value' => '1', 'type' => 'boolean', 'group' => 'finance', 'label' => 'Enable First Deposit Bonus', 'sort_order' => 12],
            ['key' => 'first_deposit_bonus_amount', 'value' => '10', 'type' => 'number', 'group' => 'finance', 'label' => 'First Deposit Bonus Amount', 'sort_order' => 13],
            ['key' => 'first_deposit_bonus_type', 'value' => 'fixed', 'type' => 'select', 'group' => 'finance', 'label' => 'Bonus Type (fixed or percentage)', 'sort_order' => 14],

            // ============ SECURITY ============
            ['key' => 'require_kyc', 'value' => '1', 'type' => 'boolean', 'group' => 'security', 'label' => 'Require KYC Verification', 'description' => 'Require users to complete KYC before withdrawing funds.', 'sort_order' => 1],
            ['key' => 'require_2fa_for_withdrawal', 'value' => '1', 'type' => 'boolean', 'group' => 'security', 'label' => 'Require 2FA for Withdrawals', 'sort_order' => 2],
            ['key' => 'require_pin_for_withdrawal', 'value' => '1', 'type' => 'boolean', 'group' => 'security', 'label' => 'Require Transaction PIN for Withdrawals', 'sort_order' => 3],
            ['key' => 'session_timeout_minutes', 'value' => '30', 'type' => 'number', 'group' => 'security', 'label' => 'Session Timeout (minutes)', 'is_public' => false, 'sort_order' => 4],
            ['key' => 'max_login_attempts', 'value' => '5', 'type' => 'number', 'group' => 'security', 'label' => 'Max Login Attempts', 'is_public' => false, 'sort_order' => 5],
            ['key' => 'two_factor_authentication', 'value' => '1', 'type' => 'boolean', 'group' => 'security', 'label' => 'Enable Two-Factor Authentication', 'sort_order' => 6],
            ['key' => 'require_email_verification', 'value' => '1', 'type' => 'boolean', 'group' => 'security', 'label' => 'Require Email Verification for New Users', 'sort_order' => 7],
            ['key' => 'registration_enabled', 'value' => '1', 'type' => 'boolean', 'group' => 'security', 'label' => 'Enable User Registration', 'sort_order' => 8],

            // ========== MAIL SETTINGS ========== 
            ['key' => 'mail_driver', 'value' => 'smtp', 'type' => 'select', 'group' => 'mail', 'label' => 'Mail Driver', 'sort_order' => 1],
            ['key' => 'mail_host', 'value' => null, 'type' => 'text', 'group' => 'mail', 'label' => 'Mail Host', 'sort_order' => 2],
            ['key' => 'mail_port', 'value' => null, 'type' => 'number', 'group' => 'mail', 'label' => 'Mail Port', 'sort_order' => 3],
            ['key' => 'mail_username', 'value' => null, 'type' => 'text', 'group' => 'mail', 'label' => 'Mail Username', 'sort_order' => 4],
            ['key' => 'mail_password', 'value' => null, 'type' => 'password', 'group' => 'mail', 'label' => 'Mail Password', 'sort_order' => 5],
            ['key' => 'mail_encryption', 'value' => null, 'type' => 'text', 'group' => 'mail', 'label' => 'Mail Encryption (tls/ssl)', 'sort_order' => 6],
            ['key' => 'mail_from_address', 'value' => null, 'type' => 'email', 'group' => 'mail', 'label' => '"From" Email Address for Outgoing Emails', 'sort_order' => 7],
            ['key' => 'mail_from_name', 'value' => null, 'type' => 'text', 'group' => 'mail', 'label' => '"From" Name for Outgoing Emails', 'sort_order' => 8],
            ['key' => 'mail_enabled', 'value' => '1', 'type' => 'boolean', 'group' => 'mail', 'label' => 'Enable Mail Notifications', 'sort_order' => 0],

            // ============ REFERRAL PROGRAM ============
            ['key' => 'referral_enabled', 'value' => '1', 'type' => 'boolean', 'group' => 'referral', 'label' => 'Enable Referral Program', 'sort_order' => 1],
            ['key' => 'referral_level_1_percentage', 'value' => '5', 'type' => 'number', 'group' => 'referral', 'label' => 'Direct Referral Commission (%)', 'sort_order' => 2],
            ['key' => 'referral_level_2_percentage', 'value' => '2', 'type' => 'number', 'group' => 'referral', 'label' => 'Indirect Referral Commission (%)', 'sort_order' => 3],
            ['key' => 'referral_bonus_signup', 'value' => '0', 'type' => 'number', 'group' => 'referral', 'label' => 'Signup Bonus Amount', 'sort_order' => 4],

            // ============ LEGAL / FOOTER ============
            ['key' => 'copyright_text', 'value' => '© 2026 NexVest. All rights reserved.', 'type' => 'text', 'group' => 'legal', 'label' => 'Copyright Text', 'sort_order' => 1],
            ['key' => 'terms_url', 'value' => '/terms-and-conditions', 'type' => 'text', 'group' => 'legal', 'label' => 'Terms & Conditions URL', 'sort_order' => 2],
            ['key' => 'privacy_url', 'value' => '/privacy-policy', 'type' => 'text', 'group' => 'legal', 'label' => 'Privacy Policy URL', 'sort_order' => 3],
            ['key' => 'risk_disclosure_text', 'value' => 'Investing involves risk, including potential loss of principal.', 'type' => 'textarea', 'group' => 'legal', 'label' => 'Risk Disclosure Notice', 'sort_order' => 4],

            // ============ APPEARANCE ============
            ['key' => 'primary_color', 'value' => '#22823A', 'type' => 'color', 'group' => 'appearance', 'label' => 'Primary Brand Color', 'sort_order' => 1],
            ['key' => 'secondary_color', 'value' => '#3B82F6', 'type' => 'color', 'group' => 'appearance', 'label' => 'Secondary Brand Color', 'sort_order' => 2],
            ['key' => 'default_theme', 'value' => 'dark', 'type' => 'select', 'group' => 'appearance', 'label' => 'Default Theme', 'description' => 'Theme shown to first-time visitors.', 'sort_order' => 3],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}/* php artisan db:seed --class=SettingsSeeder */
