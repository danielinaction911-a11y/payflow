# Started link
https://laravel.com/docs/13.x/starter-kits

# Core user extensions
php artisan make:model Kyc -m
php artisan make:model KycDocument -m 

# Investment
php artisan make:model InvestmentPlan -m
php artisan make:model Investment -m
php artisan make:model ProfitLog -m

# Wallet & money movement
php artisan make:model Currency -m
php artisan make:model Wallet -m
php artisan make:model Deposit -m
php artisan make:model Withdrawal -m
php artisan make:model Transfer -m
php artisan make:model MoneyRequest -m 
php artisan make:model Transaction -m

# Trading
php artisan make:model TradingPair -m
php artisan make:model Trade -m

# Referral
php artisan make:model Referral -m
php artisan make:model ReferralCommission -m

# Support & CMS
php artisan make:model SupportTicket -m
php artisan make:model TicketReply -m
php artisan make:model Faq -m 

# Notifications & security
php artisan make:model Notification -m
php artisan make:model LoginActivity -m
php artisan make:model SecurityAlert -m
php artisan make:model Setting -m
php artisan make:model Gateway -m
php artisan make:model WithdrawGateway -m
php artisan make:model CronLog -m
php artisan make:model MailTemplate -m
php artisan make:model Admin -m
php artisan make:model Policy -m

# Useful functions 
setting('site_name'); // "NexVest"
setting('min_deposit_amount', 10); // falls back to 10 if not set

money_format(1500.5); // "$1,500.50"
money_format(0.0045, 'BTC', 8); // "₿0.00450000"

percentage_format($plan->roi_percentage); // "+12.50%"

masked_account($card->number); // "•••• •••• •••• 4242"

transaction_reference('WD'); // "WD-K3F92XJ1"

status_color($deposit->status); // "green" / "orange" / "red"

time_ago($transaction->created_at); // "5 minutes ago"

<flux:badge color="{{ status_color($withdrawal->status) }}">
    {{ ucfirst($withdrawal->status) }}
</flux:badge>

<x-ui.checkbox wire:model="agree_to_terms" name="agree_to_terms" label="I agree to the Terms & Conditions" error="agree_to_terms" />
 

# Log logins automatically — a listener on Fortify's Login event
php artisan make:listener LogSuccessfulLogin

# install location
composer require stevebauman/location

# create admin
php artisan tinker
\App\Models\Admin::create([
    'name' => 'Super Admin',
    'email' => 'admin@yourapp.com',
    'password' => bcrypt('changeme123'),
    'status' => 'active',
]);

# Install Filament
composer require filament/filament:"^3.3" -W
php artisan filament:install --panels

# Create the Filament panel provider, wired to the admin guard + env path
php artisan make:filament-panel admin

# list of admin pages
admins → AdminResource
currencies → CurrencyResource
faqs → FaqResource
gateways → GatewayResource
investment_plans → InvestmentPlanResource
investments → InvestmentResource
kycs → KycResource
kyc_documents → KycDocumentResource
login_activities → LoginActivityResource
mail_templates → MailTemplateResource
notifications → NotificationResource
policies → PolicyResource
referrals → ReferralResource
referral_commissions → ReferralCommissionResource
security_alerts → SecurityAlertResource
settings → ManageSettings (page, not a resource)
trades → TradeResource
trading_pairs → TradingPairResource
transactions → TransactionResource
wallets → WalletResource
withdraw_gateways → WithdrawGatewayResource
cron_logs → CronLogResource