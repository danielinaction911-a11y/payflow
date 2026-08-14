<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Faq;

class FaqSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
     public function run(): void
    {
        $faqs = [
            [
                'question' => 'How do I create an account?',
                'answer' => 'Click the "Sign Up" button, enter your email, username, and password, then verify your email address to activate your account.',
            ],
            [
                'question' => 'How do I deposit funds into my account?',
                'answer' => 'Go to the Deposit page from your dashboard, choose a payment method (bank transfer, debit/credit card, or crypto), enter the amount, and follow the on-screen instructions.',
            ],
            [
                'question' => 'How long does a deposit take to reflect in my balance?',
                'answer' => 'Bank transfers and card deposits are typically processed within a few minutes to a few hours. Crypto deposits reflect after the required network confirmations.',
            ],
            [
                'question' => 'What is the minimum deposit amount?',
                'answer' => 'The minimum deposit amount depends on your selected payment method and is displayed on the Deposit page before you confirm your transaction.',
            ],
            [
                'question' => 'How do I withdraw my funds?',
                'answer' => 'Navigate to the Withdraw page, select your preferred withdrawal method, enter the amount, confirm with your transaction PIN, and submit your request for processing.',
            ],
            [
                'question' => 'How long do withdrawals take to process?',
                'answer' => 'Withdrawal requests are typically reviewed and processed within 24 to 48 hours, depending on the withdrawal method and account verification status.',
            ],
            [
                'question' => 'Why is my withdrawal pending?',
                'answer' => 'Withdrawals go through a manual security review before approval. This helps protect your account from unauthorized transactions.',
            ],
            [
                'question' => 'What is KYC and why do I need to complete it?',
                'answer' => 'KYC (Know Your Customer) is an identity verification process required by financial regulations. Completing KYC unlocks higher deposit and withdrawal limits and keeps your account secure.',
            ],
            [
                'question' => 'How do I enable Two-Factor Authentication (2FA)?',
                'answer' => 'Go to Privacy & Security in your account settings and follow the steps to enable 2FA using an authenticator app for an extra layer of account protection.',
            ],
            [
                'question' => 'How do investment plans work?',
                'answer' => 'Each investment plan has a minimum and maximum investment amount, an expected return on investment (ROI), and a fixed duration. Once you invest, your returns accumulate according to the plan terms until it matures.',
            ],
            [
                'question' => 'Can I withdraw my investment before it matures?',
                'answer' => 'This depends on the specific investment plan. Some plans allow early withdrawal with a fee, while others require the full duration to be completed. Check the plan details before investing.',
            ],
            [
                'question' => 'How do I send money to another user?',
                'answer' => 'Go to Send Money, search for the recipient by username, email, or user ID, enter the amount and an optional note, then confirm the transfer.',
            ],
            [
                'question' => 'Can I send money to someone outside the platform?',
                'answer' => 'No, the Send Money feature only supports transfers between registered users on the platform for security purposes.',
            ],
            [
                'question' => 'How does the referral program work?',
                'answer' => 'Share your unique referral link or code with others. When they sign up and make transactions, you earn a commission based on the referral program rates shown on your Referral page.',
            ],
            [
                'question' => 'Is trading available on the platform?',
                'answer' => 'Yes, you can trade supported assets directly from the Trade page, with options for market orders, limit orders, stop-loss, and take-profit settings.',
            ],
            [
                'question' => 'What fees does the platform charge?',
                'answer' => 'Fees vary by transaction type, such as deposits, withdrawals, and trades. All applicable fees are clearly displayed before you confirm any transaction.',
            ],
            [
                'question' => 'Is my money and personal information safe?',
                'answer' => 'Yes, the platform uses industry-standard encryption, two-factor authentication, and manual review processes to protect your funds and personal data.',
            ],
            [
                'question' => 'I forgot my password. How do I reset it?',
                'answer' => 'Click "Forgot Password" on the login page, enter your registered email, and follow the reset link sent to your inbox.',
            ],
            [
                'question' => 'How do I contact customer support?',
                'answer' => 'You can reach support through Live Chat, by submitting a support ticket from the Help & Support page, or by emailing our support team directly.',
            ],
            [
                'question' => 'Can I use the platform on my mobile device?',
                'answer' => 'Yes, the platform is fully responsive and optimized for mobile browsers, offering the same features and security as the desktop experience.',
            ],
        ];

        foreach ($faqs as $faq) {
            Faq::updateOrCreate(
                ['question' => $faq['question']],
                $faq
            );
        }
    }
}
