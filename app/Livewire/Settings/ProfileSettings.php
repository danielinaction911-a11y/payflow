<?php

namespace App\Livewire\Settings;

use App\Enums\KycStatus;
use App\Models\Kyc;
use App\Models\KycDocument;
use App\Traits\HandlesFileUploads;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('components.layouts.app')]
class ProfileSettings extends Component
{
    use WithFileUploads, HandlesFileUploads;

    public string $name = '';
    public string $email = '';
    public string $username = '';
    public string $phone = '';
    public string $city = '';
    public string $state = '';
    public string $country = '';
    public string $address = '';
    public string $default_theme = 'dark';
    public $newAvatar = null;

    public bool $showKycModal = false;
    public ?int $selectedKycId = null;
    public array $kycFields = [];
    public array $kycUploads = [];

    public bool $showDeleteModal = false;
    public string $deletePassword = '';

    public function mount(): void
    {
        $user = auth()->user();

        $this->name = $user->name;
        $this->email = $user->email;
        $this->username = $user->username ?? '';
        $this->phone = $user->phone ?? '';
        $this->city = $user->city ?? '';
        $this->state = $user->state ?? '';
        $this->country = $user->country ?? '';
        $this->address = $user->address ?? '';
        $this->default_theme = $user->default_theme ?? setting('default_theme', 'dark');
    }

    #[Computed]
    public function kycTypes()
    {
        return Kyc::where('status', 'enabled')->get();
    }

    #[Computed]
    public function latestKycDocument()
    {
        return KycDocument::with('kyc')
            ->where('user_id', Auth::id())
            ->latest()
            ->first();
    }

    #[Computed]
    public function kycStatusMeta(): ?array
    {
        $doc = $this->latestKycDocument;

        if (! $doc) {
            return null;
        }

        return match ($doc->status) {
            KycStatus::Verified => [
                'icon_class' => 'bg-emerald-500/12 text-emerald-500',
                'badge_class' => 'bg-emerald-500/12 text-emerald-600 dark:text-emerald-400',
                'label' => $doc->status->label(),
            ],
            KycStatus::Rejected => [
                'icon_class' => 'bg-rose-500/12 text-rose-500',
                'badge_class' => 'bg-rose-500/12 text-rose-600 dark:text-rose-400',
                'label' => $doc->status->label(),
            ],
            KycStatus::Pending => [
                'icon_class' => 'bg-amber-500/12 text-amber-500',
                'badge_class' => 'bg-amber-500/12 text-amber-600 dark:text-amber-400',
                'label' => $doc->status->label(),
            ],
            default => [
                'icon_class' => 'bg-slate-500/12 text-slate-500',
                'badge_class' => 'bg-slate-500/12 text-slate-600 dark:text-slate-400',
                'label' => $doc->status->label(),
            ],
        };
    }

    #[Computed]
    public function selectedKyc()
    {
        return $this->selectedKycId ? Kyc::find($this->selectedKycId) : null;
    }

    #[Computed]
    public function restrictions(): array
    {
        $user = auth()->user();
        $items = [];

        $map = [
            'deposit' => 'Deposits',
            'transfer' => 'Transfers',
            'withdrawal' => 'Withdrawals',
            'investment' => 'Investments',
            'trading' => 'Trading',
        ];

        foreach ($map as $key => $label) {
            $status = $user->{"{$key}_status"};
            $message = $user->{"{$key}_message"};

            // Adjust this condition to match your actual status convention
            // (e.g. 'active'/'disabled', boolean, or an enum).
            if ($status && ! in_array($status, ['active', 'enabled', 1, true], true)) {
                $items[] = [
                    'label' => $label,
                    'message' => $message ?: 'This feature is currently restricted on your account.',
                ];
            }
        }

        return $items;
    }

    public function saveProfile(): void
    {
        $user = auth()->user();

        $this->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($user->id)],
            'username' => ['required', 'string', 'max:255', Rule::unique('users', 'username')->ignore($user->id)],
            'phone' => 'nullable|string|max:20',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'default_theme' => ['required', Rule::in(['light', 'dark'])],
            'newAvatar' => 'nullable|image|max:2048',
        ]);

        $emailChanged = $this->email !== $user->email;

        $data = [
            'name' => $this->name,
            'email' => $this->email,
            'username' => $this->username,
            'phone' => $this->phone,
            'city' => $this->city,
            'state' => $this->state,
            'country' => $this->country,
            'address' => $this->address,
        ];

        if ($emailChanged) {
            $data['email_verified_at'] = null;
        }

        if ($this->newAvatar) {
            $defaultAvatar = setting('default_avatar', 'images/user/user.png');

            $oldAvatarToDelete = ($user->avatar && $user->avatar !== $defaultAvatar)
                ? $user->avatar
                : null;

            $data['avatar'] = $this->uploadFile(
                $this->newAvatar,
                'images/user',
                $oldAvatarToDelete,
                'avatar_' . $user->id
            );
        }

        $data['default_theme'] = $this->default_theme;

        $user->update($data);

        if ($emailChanged) {
            $user->sendEmailVerificationNotification();
        }

        $this->newAvatar = null;

        $this->dispatch(
            'notify',
            type: 'success',
            title: 'Profile Updated',
            message: 'Your profile information has been updated successfully.'
        );
    }

    public function updateTheme(string $theme): void
    {
        if (! in_array($theme, ['light', 'dark'], true)) {
            return;
        }

        $user = auth()->user();
        $user->update(['default_theme' => $theme]);
        $this->default_theme = $theme;

        $this->dispatch(
            'notify',
            type: 'success',
            title: 'Theme Updated',
            message: 'Your theme preference has been saved.'
        );
    }

    public function resendVerification(): void
    {
        if (! auth()->user()->hasVerifiedEmail()) {
            auth()->user()->sendEmailVerificationNotification(); 
            $this->dispatch(
                'notify',
                type: 'success',
                title: 'Verification Email Sent',
                message: 'A new verification link has been sent to your email address.'
            );
        }
    }

    public function openKycModal(int $kycId): void
    {
        $this->selectedKycId = $kycId;
        $this->kycFields = [];
        $this->kycUploads = [];
        $this->showKycModal = true;
    }

    public function closeKycModal(): void
    {
        $this->showKycModal = false;
    }

    public function submitKyc(): void
    {
        $kyc = $this->selectedKyc;

        if (! $kyc) {
            return;
        }

        $rules = [];

        if ($kyc->required_fields) {
            foreach ($kyc->required_fields as $field) {
                $required = ! empty($field['required']) ? 'required' : 'nullable';

                if ($field['type'] === 'file') {
                    $rules["kycUploads.{$field['name']}"] = "$required|file|mimes:jpg,jpeg,png,pdf|max:2048";
                } else {
                    $rules["kycFields.{$field['name']}"] = "$required|string|max:255";
                }
            }
        }

        $this->validate($rules);

        $submittedData = $this->kycFields;

        if ($kyc->required_fields) {
            foreach ($kyc->required_fields as $field) {
                if ($field['type'] === 'file' && isset($this->kycUploads[$field['name']])) {
                    $submittedData[$field['name']] = $this->uploadFile(
                        $this->kycUploads[$field['name']],
                        'images/kyc',
                        null,
                        'kyc_' . Auth::id() . '_' . $field['name']
                    );
                }
            }
        }

        KycDocument::create([
            'user_id' => Auth::id(),
            'kyc_id' => $kyc->id,
            'required_fields' => $submittedData,
            'status' => KycStatus::Pending,
        ]);

        auth()->user()->update(['kyc_status' => 'pending']);

        $this->showKycModal = false;
        unset($this->latestKycDocument, $this->kycStatusMeta);
        $this->dispatch(
            'notify',
            type: 'success',
            title: 'KYC submitted',
            message: 'Your KYC document has been submitted for review.'
        );
    }

    #[On('open-delete-account-modal')]
    public function openDeleteModal(): void
    {
        $this->deletePassword = '';
        $this->showDeleteModal = true;
    }

    public function closeDeleteModal(): void
    {
        $this->showDeleteModal = false;
    }

    public function deleteAccount(): void
    {
        $this->validate(['deletePassword' => 'required|string']);

        $user = auth()->user();

        if (! Hash::check($this->deletePassword, $user->password)) {
            $this->addError('deletePassword', 'Incorrect password.');
            return;
        }

        Auth::logout();
        $user->delete();

        request()->session()->invalidate();
        request()->session()->regenerateToken();

        $this->redirect(route('login'), navigate: true);
    }

    public function render()
    {
        return view('livewire.settings.profile-settings')->layout('components.layouts.app', [
            'title' => 'Profile Settings',
        ]);
    }
}
