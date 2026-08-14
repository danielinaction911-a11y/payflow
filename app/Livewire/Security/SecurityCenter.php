<?php

namespace App\Livewire\Security;

use App\Models\LoginActivity;
use App\Models\SecurityAlert;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app')]
class SecurityCenter extends Component
{
    // PIN management
    public bool $showPinModal = false;
    public bool $currentPinVerified = false;
    public string $currentPin = '';
    public string $verifiedCurrentPin = '';
    public string $newPin = '';
    public string $newPinConfirmation = '';

    // Password management
    public bool $showPasswordModal = false;
    public bool $currentPasswordVerified = false;
    public string $currentPassword = '';
    public string $verifiedCurrentPassword = '';
    public string $newPassword = '';
    public string $newPasswordConfirmation = '';

    // Password-gated logout
    public bool $showLogoutModal = false;
    public ?int $pendingLogoutActivityId = null;
    public string $logoutPassword = '';

    #[Computed]
    public function userHasPin(): bool
    {
        return ! empty(auth()->user()->transaction_pin);
    }

    #[Computed]
    public function canCreatePin(): bool
    {
        return (bool) setting('create_withdrawal_pin', true);
    }

    #[Computed]
    public function loginActivities()
    {
        return LoginActivity::where('user_id', Auth::id())
            ->latest('logged_in_at')
            ->limit(10)
            ->get();
    }

    #[Computed]
    public function securityAlerts()
    {
        return SecurityAlert::where('user_id', Auth::id())
            ->latest()
            ->limit(20)
            ->get();
    }

    #[Computed]
    public function currentSessionIp()
    {
        return request()->ip();
    }

    // ===== PIN =====

    public function openPinModal(): void
    {
        $this->reset(['currentPin', 'verifiedCurrentPin', 'newPin', 'newPinConfirmation']);
        $this->currentPinVerified = false;
        $this->resetErrorBag();
        $this->showPinModal = true;
    }

    public function closePinModal(): void
    {
        $this->showPinModal = false;
        $this->currentPinVerified = false;
        $this->verifiedCurrentPin = '';
        $this->resetErrorBag();
    }

    public function verifyCurrentPin(): void
    {
        $user = auth()->user();

        $this->validate([
            'currentPin' => 'required|digits:4',
        ]);

        if (! Hash::check($this->currentPin, $user->transaction_pin)) {
            $this->addError('currentPin', 'Current PIN is incorrect.');
            return;
        }

        $this->verifiedCurrentPin = $this->currentPin;
        $this->currentPinVerified = true;
        $this->currentPin = '';
        $this->newPin = '';
        $this->newPinConfirmation = '';
    }

    public function savePin(): void
    {
        $user = auth()->user();
        $hasPin = $this->userHasPin;

        if ($hasPin) {
            if (! $this->currentPinVerified) {
                $this->addError('currentPin', 'Please verify your current PIN first.');
                return;
            }

            // FIX: "different:{$this->verifiedCurrentPin}" was comparing against a field
            // literally NAMED "1234" (the pin's digits), which never exists — Laravel's
            // `different` rule silently passes when it can't find the comparison field,
            // so this check never actually fired. Compare the values directly instead.
            $this->validate([
                'newPin' => 'required|digits:4',
                'newPinConfirmation' => 'required|digits:4|same:newPin',
            ], [
                'newPinConfirmation.same' => 'PIN confirmation does not match.',
            ]);

            if ($this->newPin === $this->verifiedCurrentPin) {
                $this->addError('newPin', 'New PIN must be different from your current PIN.');
                return;
            }
        } else {
            if (! $this->canCreatePin) {
                $this->addError('newPin', 'PIN creation is disabled. Please contact support.');
                return;
            }

            $this->validate([
                'newPin' => 'required|digits:4',
                'newPinConfirmation' => 'required|digits:4|same:newPin',
            ], [
                'newPinConfirmation.same' => 'PIN confirmation does not match.',
            ]);
        }

        $user->update([
            'transaction_pin' => Hash::make($this->newPin),
            'pin_update_at' => now(),
        ]);

        SecurityAlert::create([
            'user_id' => $user->id,
            'type' => $hasPin ? 'pin_changed' : 'pin_created',
            'description' => $hasPin ? 'Your transaction PIN was changed.' : 'A transaction PIN was created for your account.',
        ]);

        $this->showPinModal = false;
        unset($this->userHasPin, $this->securityAlerts);

        $this->dispatch(
            'notify',
            type: 'success',
            title: $hasPin ? 'PIN updated' : 'PIN created',
            message: $hasPin ? 'Your transaction PIN has been changed.' : 'Your transaction PIN has been set.'
        );
    }

    // ===== Password =====

    public function openPasswordModal(): void
    {
        $this->reset(['currentPassword', 'verifiedCurrentPassword', 'newPassword', 'newPasswordConfirmation']);
        $this->currentPasswordVerified = false;
        $this->resetErrorBag();
        $this->showPasswordModal = true;
    }

    public function closePasswordModal(): void
    {
        $this->showPasswordModal = false;
        $this->currentPasswordVerified = false;
        $this->verifiedCurrentPassword = '';
        $this->resetErrorBag();
    }

    public function verifyCurrentPassword(): void
    {
        $user = auth()->user();

        // FIX: previously ran both the `current_password` validation rule AND a manual
        // Hash::check() below it. If the password was wrong, the rule threw first, so
        // the custom "Current password is incorrect." message was unreachable dead code
        // — the user saw Laravel's generic default instead. Now there's one path.
        $this->validate([
            'currentPassword' => ['required', 'string'],
        ]);

        if (! Hash::check($this->currentPassword, $user->password)) {
            $this->addError('currentPassword', 'Current password is incorrect.');
            return;
        }

        $this->verifiedCurrentPassword = $this->currentPassword;
        $this->currentPasswordVerified = true;
        $this->currentPassword = '';
        $this->newPassword = '';
        $this->newPasswordConfirmation = '';
    }

    public function savePassword(): void
    {
        if (! $this->currentPasswordVerified) {
            $this->addError('currentPassword', 'Please verify your current password first.');
            return;
        }

        // FIX: `confirmed` on `newPassword` requires a sibling field literally named
        // `newPassword_confirmation` to exist. Ours is `newPasswordConfirmation`
        // (no underscore, matching the Livewire property name) — since that exact
        // field never exists, `confirmed` treated it as missing and failed on
        // every submission, regardless of whether the passwords actually matched.
        // The `same:newPassword` rule below already does this comparison correctly,
        // so `confirmed` was both redundant and the actual cause of the bug.
        $this->validate([
            'newPassword' => ['required', 'string', 'min:8'],
            'newPasswordConfirmation' => ['required', 'string', 'same:newPassword'],
        ], [
            'newPasswordConfirmation.same' => 'Password confirmation does not match.',
        ]);

        auth()->user()->update([
            'password' => Hash::make($this->newPassword),
        ]);

        SecurityAlert::create([
            'user_id' => auth()->id(),
            'type' => 'password_changed',
            'description' => 'Your account password was changed.',
        ]);

        $this->showPasswordModal = false;
        $this->reset(['currentPassword', 'verifiedCurrentPassword', 'newPassword', 'newPasswordConfirmation']);
        $this->currentPasswordVerified = false;
        unset($this->securityAlerts);

        $this->dispatch(
            'notify',
            type: 'success',
            title: 'Password updated',
            message: 'Your account password has been changed.'
        );
    }

    // ===== Remote logout (password-gated) =====

    public function confirmLogout(int $activityId): void
    {
        $this->pendingLogoutActivityId = $activityId;
        $this->logoutPassword = '';
        $this->resetErrorBag();
        $this->showLogoutModal = true;
    }

    public function closeLogoutModal(): void
    {
        $this->showLogoutModal = false;
        $this->pendingLogoutActivityId = null;
        $this->resetErrorBag();
    }

    public function revokeSession(): void
    {
        $this->validate([
            'logoutPassword' => 'required|string',
        ]);

        $user = auth()->user();

        if (! Hash::check($this->logoutPassword, $user->password)) {
            $this->addError('logoutPassword', 'Incorrect password.');
            return;
        }

        $activity = LoginActivity::where('id', $this->pendingLogoutActivityId)
            ->where('user_id', $user->id)
            ->first();

        if (! $activity) {
            $this->closeLogoutModal();
            return;
        }

        // Real device revocation: delete matching session(s) from the
        // actual `sessions` table for this user + IP, forcing that
        // browser to be logged out on its next request.
        DB::table('sessions')
            ->where('user_id', $user->id)
            ->where('ip_address', $activity->ip_address)
            ->delete();

        $device = $activity->device ?? 'That device';
        $activity->delete();

        SecurityAlert::create([
            'user_id' => $user->id,
            'type' => 'device_logged_out',
            'description' => "A session from {$device} ({$activity->ip_address}) was remotely logged out.",
        ]);

        $this->showLogoutModal = false;
        $this->pendingLogoutActivityId = null;
        $this->logoutPassword = '';

        unset($this->loginActivities, $this->securityAlerts);

        $this->dispatch(
            'notify',
            type: 'success',
            title: 'Device logged out',
            message: "{$device} has been signed out of your account."
        );
    }

    public function render()
    {
        return view('livewire.security.security-center')->layout('components.layouts.app', [
            'title' => 'Security Center',
        ]);
    }
}