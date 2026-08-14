<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Services\MailService;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\URL;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'username',
        'phone',
        'city',
        'state',
        'country',
        'address',
        'avatar',
        'default_theme',
        'balance',
        'profit_balance',
        'kyc_status',
        'status',
        'deposit_status',
        'deposit_message',
        'transfer_status',
        'transfer_message',
        'withdrawal_status',
        'withdrawal_message',
        'investment_status',
        'investment_message',
        'trading_status',
        'trading_message',
        'withdrawal_fee_status',
        'withdrawal_fee',
        'withdrawal_fee_type',
        'daily_transfer_limit',
        'daily_withdrawal_limit',
        'weekly_transfer_limit',
        'weekly_withdrawal_limit',
        'monthly_transfer_limit',
        'monthly_withdrawal_limit',
        'referral_code',
        'referred_by',
        'transaction_pin',
        'pin_update_at',
        'biometric_enabled',
        'last_login_at',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'balance' => 'decimal:2',
            'profit_balance' => 'decimal:2',
            'withdrawal_fee' => 'decimal:2',
            'daily_transfer_limit' => 'decimal:2',
            'daily_withdrawal_limit' => 'decimal:2',
            'weekly_transfer_limit' => 'decimal:2',
            'weekly_withdrawal_limit' => 'decimal:2',
            'monthly_transfer_limit' => 'decimal:2',
            'monthly_withdrawal_limit' => 'decimal:2',
            'biometric_enabled' => 'boolean',
            'pin_update_at' => 'datetime',
            'last_login_at' => 'datetime',
            'two_factor_confirmed_at' => 'datetime',
        ];
    }

    public function profileImageUrl(): string
    {
        if ($this->avatar) {
            return asset($this->avatar);
        }

        return asset('images/user/user.png');
    }

    public function wallets()
    {
        return $this->hasMany(Wallet::class);
    }
    public function primaryWallet()
    {
        return $this->hasOne(Wallet::class)->where('is_primary', true);
    }

    public function kycDocuments()
    {
        return $this->hasMany(KycDocument::class);
    }

    public function investments()
    {
        return $this->hasMany(Investment::class);
    }

    public function deposits()
    {
        return $this->hasMany(Deposit::class);
    }
    public function withdrawals()
    {
        return $this->hasMany(Withdrawal::class);
    }
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function sentTransfers()
    {
        return $this->hasMany(Transfer::class, 'sender_id');
    }
    public function receivedTransfers()
    {
        return $this->hasMany(Transfer::class, 'recipient_id');
    }

    public function sentMoneyRequests()
    {
        return $this->hasMany(MoneyRequest::class, 'requester_id');
    }
    public function receivedMoneyRequests()
    {
        return $this->hasMany(MoneyRequest::class, 'recipient_id');
    }

    public function trades()
    {
        return $this->hasMany(Trade::class);
    }
    public function watchlist()
    {
        return $this->belongsToMany(TradingPair::class, 'watchlists');
    }

    // Referral: who referred this user (self-referencing)
    public function referredBy()
    {
        return $this->belongsTo(User::class, 'referred_by');
    }
    public function referredUsers()
    {
        return $this->hasMany(User::class, 'referred_by');
    }

    // Referral tracking table entries where this user is the referrer
    public function referrals()
    {
        return $this->hasMany(Referral::class, 'referrer_id');
    }
    public function referredAs()
    {
        return $this->hasOne(Referral::class, 'referred_id');
    }

    public function supportTickets()
    {
        return $this->hasMany(SupportTicket::class);
    }
    public function ticketReplies()
    {
        return $this->hasMany(TicketReply::class, 'sender_id');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function hasRelatedRecords(): bool
    {
        // Consider related if any important related records exist or user still holds funds
        $hasRelations = (
            $this->kycDocuments()->exists()
            || $this->investments()->exists()
            || $this->deposits()->exists()
            || $this->withdrawals()->exists()
            || $this->transactions()->exists()
            || $this->trades()->exists()
            || $this->supportTickets()->exists()
            || $this->ticketReplies()->exists()
            || $this->wallets()->exists()
            || $this->referrals()->exists()
            || $this->notifications()->exists()
            || $this->loginActivities()->exists()
        );

        $hasBalance = false;
        // Use bccomp for precise decimal comparison
        try {
            $hasBalance = bccomp((string) ($this->balance ?? '0'), '0', 18) === 1
                || bccomp((string) ($this->profit_balance ?? '0'), '0', 18) === 1;
        } catch (\Throwable $e) {
            // Fallback to simple comparison if bccomp isn't available for some reason
            $hasBalance = (float) ($this->balance ?? 0) > 0 || (float) ($this->profit_balance ?? 0) > 0;
        }

        return $hasRelations || $hasBalance;
    }

    /**
     * Return a short machine reason key for why deletion is blocked, or null.
     */
    public function deletionBlockReason(): ?string
    {
        // Balance first
        try {
            if (
                bccomp((string) ($this->balance ?? '0'), '0', 18) === 1
                || bccomp((string) ($this->profit_balance ?? '0'), '0', 18) === 1
            ) {
                return 'balance';
            }
        } catch (\Throwable $e) {
            if ((float) ($this->balance ?? 0) > 0 || (float) ($this->profit_balance ?? 0) > 0) {
                return 'balance';
            }
        }

        if ($this->investments()->exists()) {
            return 'investments';
        }

        if ($this->deposits()->exists()) {
            return 'deposits';
        }

        if ($this->withdrawals()->exists()) {
            return 'withdrawals';
        }

        if ($this->transactions()->exists()) {
            return 'transactions';
        }

        if ($this->trades()->exists()) {
            return 'trades';
        }

        if ($this->supportTickets()->exists()) {
            return 'support_tickets';
        }

        if ($this->ticketReplies()->exists()) {
            return 'ticket_replies';
        }

        if ($this->wallets()->exists()) {
            return 'wallets';
        }

        if ($this->referrals()->exists()) {
            return 'referrals';
        }

        if ($this->notifications()->exists()) {
            return 'notifications';
        }

        if ($this->loginActivities()->exists()) {
            return 'login_activities';
        }

        return null;
    }

    /**
     * Human readable label for deletionBlockReason()
     */
    public function deletionBlockReasonLabel(): string
    {
        return match ($this->deletionBlockReason()) {
            'balance' => 'has non-zero balance',
            'investments' => 'has investments',
            'deposits' => 'has deposits',
            'withdrawals' => 'has withdrawals',
            'transactions' => 'has transactions',
            'trades' => 'has trades',
            'support_tickets' => 'has support tickets',
            'ticket_replies' => 'has ticket replies',
            'wallets' => 'has wallets',
            'referrals' => 'has referrals',
            'notifications' => 'has notifications',
            'login_activities' => 'has login activities',
            default => 'has related records',
        };
    }

    protected static function booted(): void
    {
        static::deleting(function (User $user) {
            return ! $user->hasRelatedRecords();
        });
    }

    public function initials(): string
    {
        $parts = preg_split('/\s+/', trim($this->name ?? '')) ?: [];

        if (count($parts) >= 2) {
            return strtoupper(substr($parts[0], 0, 1) . substr($parts[count($parts) - 1], 0, 1));
        }

        if (! empty($parts[0])) {
            return strtoupper(substr($parts[0], 0, 2));
        }

        return strtoupper(substr((string) ($this->email ?? ''), 0, 2));
    }

    public function twoFactorQrCodeSvg(): string
    {
        $issuer = setting('site_title', config('app.name', 'Laravel'));
        $label = rawurlencode($issuer) . ':' . rawurlencode($this->email);
        $secret = $this->two_factor_secret ?? '';

        $otpauthUrl = sprintf(
            'otpauth://totp/%s?secret=%s&issuer=%s&algorithm=SHA1&digits=6&period=30',
            $label,
            rawurlencode($secret),
            rawurlencode($issuer)
        );

        return QrCode::size(220)->generate($otpauthUrl);
    }

    public function recoveryCodes(): array
    {
        if (empty($this->two_factor_recovery_codes)) {
            return [];
        }

        if (is_array($this->two_factor_recovery_codes)) {
            return $this->two_factor_recovery_codes;
        }

        $codes = json_decode($this->two_factor_recovery_codes, true);

        return is_array($codes) ? $codes : [];
    }

    public function loginActivities()
    {
        return $this->hasMany(LoginActivity::class);
    }
    public function securityAlerts()
    {
        return $this->hasMany(SecurityAlert::class);
    }

    // Admin-side: KYC docs this user (as admin) reviewed
    public function reviewedKycDocuments()
    {
        return $this->hasMany(KycDocument::class, 'reviewed_by');
    }
    public function approvedDeposits()
    {
        return $this->hasMany(Deposit::class, 'approved_by');
    }
    public function approvedWithdrawals()
    {
        return $this->hasMany(Withdrawal::class, 'approved_by');
    }

    /**
     * Send the email verification notification, routed through our own
     * MailService + MailTemplate system instead of Laravel's default
     * VerifyEmail notification/mail view.
     */
    public function sendEmailVerificationNotification(): void
    {
        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(config('auth.verification.expire', 60)),
            [
                'id' => $this->getKey(),
                'hash' => sha1($this->getEmailForVerification()),
            ]
        );

        app(MailService::class)->sendTemplate($this, 'email_verification', [
            'name' => $this->name ?: $this->email,
            'verification_url' => $verificationUrl,
        ]);
    }
}
