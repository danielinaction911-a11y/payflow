<?php

namespace Database\Seeders;

use App\Models\Policy;
use Illuminate\Database\Seeder;

class PolicySeeder extends Seeder
{
    public function run(): void
    {
        $policies = [
            [
                'title' => 'Terms of Service',
                'slug' => 'terms-of-service',
                'type' => 'terms',
                'sort_order' => 1,
                'effective_date' => now(),
                'content' => '<h2>1. Acceptance of Terms</h2>
<p>By accessing or using this platform, you agree to be bound by these Terms of Service. If you do not agree with any part of these terms, you must not use our services.</p>

<h2>2. Eligibility</h2>
<p>You must be at least 18 years old and legally capable of entering into binding contracts to use this platform. By registering, you confirm that you meet these requirements.</p>

<h2>3. Account Responsibilities</h2>
<p>You are responsible for maintaining the confidentiality of your account credentials and for all activities that occur under your account. Notify us immediately of any unauthorized use.</p>

<h2>4. Platform Use</h2>
<p>You agree to use the platform only for lawful purposes and in accordance with these Terms. Any fraudulent, abusive, or illegal activity may result in immediate account suspension.</p>

<h2>5. Investment and Trading Risks</h2>
<p>All investments and trades carried out on this platform involve risk. Past performance does not guarantee future results. Please review our Risk Disclosure for full details.</p>

<h2>6. Fees</h2>
<p>Applicable fees for deposits, withdrawals, trades, and other transactions are disclosed before you confirm any action. Fees may be updated from time to time.</p>

<h2>7. Termination</h2>
<p>We reserve the right to suspend or terminate your account at our discretion if these Terms are violated or if required by law.</p>

<h2>8. Changes to Terms</h2>
<p>We may update these Terms periodically. Continued use of the platform after changes take effect constitutes acceptance of the revised Terms.</p>

<h2>9. Contact</h2>
<p>For questions regarding these Terms, please contact our support team.</p>',
            ],

            [
                'title' => 'Privacy Policy',
                'slug' => 'privacy-policy',
                'type' => 'privacy',
                'sort_order' => 2,
                'effective_date' => now(),
                'content' => '<h2>1. Information We Collect</h2>
<p>We collect information you provide directly, such as your name, email, phone number, address, and identity documents submitted for KYC verification, as well as data generated through your use of the platform, including transaction history and device information.</p>

<h2>2. How We Use Your Information</h2>
<p>Your information is used to provide and improve our services, process transactions, verify your identity, communicate with you, prevent fraud, and comply with legal obligations.</p>

<h2>3. Information Sharing</h2>
<p>We do not sell your personal information. We may share data with trusted service providers, payment processors, and regulatory authorities as required to operate the platform and comply with applicable laws.</p>

<h2>4. Data Security</h2>
<p>We implement industry-standard security measures, including encryption and access controls, to protect your personal information from unauthorized access, alteration, or disclosure.</p>

<h2>5. Data Retention</h2>
<p>We retain your information for as long as necessary to provide our services and to comply with legal, regulatory, and accounting requirements.</p>

<h2>6. Your Rights</h2>
<p>You may request access to, correction of, or deletion of your personal data, subject to applicable law and our regulatory recordkeeping obligations.</p>

<h2>7. Cookies</h2>
<p>We use cookies to enhance your experience on the platform. See our Cookie Policy for more details.</p>

<h2>8. Changes to This Policy</h2>
<p>We may revise this Privacy Policy periodically. Updates will be posted on this page with a new effective date.</p>',
            ],

            [
                'title' => 'Anti-Money Laundering (AML) Policy',
                'slug' => 'aml-policy',
                'type' => 'aml',
                'sort_order' => 3,
                'effective_date' => now(),
                'content' => '<h2>1. Our Commitment</h2>
<p>We are committed to preventing the platform from being used for money laundering, terrorist financing, or any other illicit financial activity, in accordance with applicable laws and regulations.</p>

<h2>2. Customer Due Diligence</h2>
<p>All users are required to complete identity verification (KYC) before performing certain transactions. Additional due diligence may be requested for high-value transactions or suspicious activity.</p>

<h2>3. Transaction Monitoring</h2>
<p>We monitor account activity and transactions for patterns that may indicate money laundering or other financial crimes, using both automated systems and manual review.</p>

<h2>4. Reporting Obligations</h2>
<p>Where required by law, we report suspicious activity to relevant regulatory and law enforcement authorities. We may be required to freeze or restrict accounts under investigation.</p>

<h2>5. Record Keeping</h2>
<p>We maintain records of customer identification and transaction history in accordance with applicable regulatory retention requirements.</p>

<h2>6. User Responsibilities</h2>
<p>Users must provide accurate information during registration and verification, and must not use the platform to facilitate illegal financial activity of any kind.</p>',
            ],

            [
                'title' => 'KYC (Know Your Customer) Policy',
                'slug' => 'kyc-policy',
                'type' => 'kyc',
                'sort_order' => 4,
                'effective_date' => now(),
                'content' => '<h2>1. Purpose of KYC</h2>
<p>Know Your Customer (KYC) verification helps us confirm the identity of our users, prevent fraud, and comply with regulatory requirements.</p>

<h2>2. Required Documentation</h2>
<p>Users may be required to submit a government-issued ID (passport, national ID, or driver\'s license) and proof of address to complete verification.</p>

<h2>3. Verification Levels</h2>
<p>Certain platform features, including higher withdrawal limits, may only be unlocked once KYC verification has been successfully completed and approved.</p>

<h2>4. Review Process</h2>
<p>Submitted documents are reviewed by our compliance team. Verification status will be updated on your account, and you will be notified of approval or rejection, including reasons for any rejection.</p>

<h2>5. Data Handling</h2>
<p>Documents submitted for KYC purposes are stored securely and used solely for identity verification and regulatory compliance, in accordance with our Privacy Policy.</p>

<h2>6. Re-Verification</h2>
<p>We may periodically request updated documentation to ensure your account information remains accurate and compliant with applicable regulations.</p>',
            ],

            [
                'title' => 'Risk Disclosure',
                'slug' => 'risk-disclosure',
                'type' => 'risk',
                'sort_order' => 5,
                'effective_date' => now(),
                'content' => '<h2>1. General Risk Warning</h2>
<p>Investing and trading involve substantial risk, including the potential loss of some or all of your invested capital. You should not invest funds you cannot afford to lose.</p>

<h2>2. Market Volatility</h2>
<p>The value of investments and assets, including cryptocurrencies, can fluctuate significantly and unpredictably due to market conditions beyond our control.</p>

<h2>3. No Guaranteed Returns</h2>
<p>Expected returns displayed on investment plans are estimates based on historical or projected performance and are not guaranteed. Past performance is not indicative of future results.</p>

<h2>4. Platform Risk</h2>
<p>While we implement strong security measures, no platform can guarantee complete protection against technical failures, cyberattacks, or third-party service disruptions.</p>

<h2>5. Your Responsibility</h2>
<p>You are solely responsible for evaluating the risks associated with any investment or trading decision made on this platform. We recommend consulting an independent financial advisor before investing.</p>',
            ],

            [
                'title' => 'Cookie Policy',
                'slug' => 'cookie-policy',
                'type' => 'cookie',
                'sort_order' => 6,
                'effective_date' => now(),
                'content' => '<h2>1. What Are Cookies</h2>
<p>Cookies are small text files stored on your device that help us recognize your browser, remember preferences, and improve your experience on the platform.</p>

<h2>2. Types of Cookies We Use</h2>
<p>We use essential cookies required for core functionality (such as staying logged in), performance cookies to analyze site usage, and preference cookies to remember settings like theme and language.</p>

<h2>3. Managing Cookies</h2>
<p>You can control or disable cookies through your browser settings. Please note that disabling essential cookies may affect platform functionality.</p>

<h2>4. Third-Party Cookies</h2>
<p>Some cookies may be set by third-party services we use for analytics or security purposes. These providers have their own privacy and cookie policies.</p>

<h2>5. Updates to This Policy</h2>
<p>We may update this Cookie Policy from time to time. Continued use of the platform indicates acceptance of the updated policy.</p>',
            ],

            [
                'title' => 'Refund Policy',
                'slug' => 'refund-policy',
                'type' => 'refund',
                'sort_order' => 7,
                'effective_date' => now(),
                'content' => '<h2>1. General Policy</h2>
<p>Due to the nature of financial transactions, deposits, investments, and completed trades are generally non-refundable once processed.</p>

<h2>2. Erroneous Transactions</h2>
<p>If you believe a transaction was processed in error, please contact support immediately. We will investigate and, where appropriate, issue a correction or refund.</p>

<h2>3. Failed Transactions</h2>
<p>If a deposit or withdrawal fails due to a technical error on our end, the amount will be refunded or corrected within a reasonable timeframe after investigation.</p>

<h2>4. Investment Plans</h2>
<p>Refunds or early exits from investment plans are subject to the specific terms of each plan, which are disclosed before you confirm your investment.</p>

<h2>5. Processing Time</h2>
<p>Approved refunds are processed back to the original payment method or platform wallet within the timeframes disclosed at the time of approval.</p>',
            ],

            [
                'title' => 'Accessibility Statement',
                'slug' => 'accessibility',
                'type' => 'accessibility',
                'sort_order' => 8,
                'effective_date' => now(),
                'content' => '<h2>1. Our Commitment</h2>
<p>We are committed to ensuring our platform is accessible to all users, including those with disabilities, and strive to meet recognized web accessibility standards.</p>

<h2>2. Accessibility Features</h2>
<p>Our platform is designed with readable typography, sufficient color contrast, keyboard navigation support, and responsive layouts across devices.</p>

<h2>3. Ongoing Improvements</h2>
<p>We continuously review and improve the accessibility of our platform as part of our regular design and development process.</p>

<h2>4. Feedback</h2>
<p>If you experience any difficulty accessing content or features on our platform, please contact our support team so we can address the issue.</p>',
            ],
        ];

        foreach ($policies as $policy) {
            Policy::updateOrCreate(
                ['slug' => $policy['slug']],
                $policy
            );
        }
    }
}
