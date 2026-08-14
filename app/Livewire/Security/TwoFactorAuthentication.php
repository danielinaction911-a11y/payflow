<?php

namespace App\Livewire\Security;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class TwoFactorAuthentication extends Component
{
    public bool $enabling = false;
    public string $confirmationCode = '';
    public ?string $qrCodeSvg = null;
    public ?array $recoveryCodes = null;
    public ?string $error = null;

    public function enable($enable = null): void
    {
        $action = $enable ?: app(\Laravel\Fortify\Actions\EnableTwoFactorAuthentication::class);
        $action(Auth::user());

        $this->enabling = true;
        $this->qrCodeSvg = Auth::user()->twoFactorQrCodeSvg();
    }

    public function confirm($confirm = null): void
    {
        $this->error = null;

        try {
            $action = $confirm ?: app(\Laravel\Fortify\Actions\ConfirmTwoFactorAuthentication::class);
            $action(Auth::user(), $this->confirmationCode);

            $this->enabling = false;
            $this->confirmationCode = '';
            $this->recoveryCodes = Auth::user()->recoveryCodes();
        } catch (\Exception $e) {
            $this->error = 'Invalid verification code. Please try again.';
        }
    }

    public function disable($disable = null): void
    {
        $action = $disable ?: app(\Laravel\Fortify\Actions\DisableTwoFactorAuthentication::class);
        $action(Auth::user());

        $this->enabling = false;
        $this->qrCodeSvg = null;
        $this->recoveryCodes = null;
    }

    public function render()
    {
        return view('livewire.security.two-factor-authentication');
    }
}