<?php

use App\Livewire\Actions\Logout;
use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Component;

new class extends Component {
    public string $password = '';

    /**
     * Delete the currently authenticated user.
     */
    public function deleteUser(Logout $logout): void
    {
        $this->validate([
            'password' => ['required', 'string', 'current_password'],
        ]);

        tap(Auth::user(), $logout(...))->delete();

        $this->redirect('/', navigate: true);
    }
}; ?>

<section class="mt-10 space-y-6">
    <div class="relative mb-5">
        <flux:heading>{{ __('Delete Account') }}</flux:heading>
        <flux:subheading>{{ __('Delete your account and all of its resources') }}</flux:subheading>
    </div>

    <flux:modal.trigger name="confirm-user-deletion">
        <flux:button  class="w-full rounded-xl py-2.5 text-sm font-semibold !bg-rose-500 !text-white hover:!bg-rose-400 disabled:cursor-not-allowed disabled:opacity-70" x-data="" x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')">
            {{ __('Delete Account') }}
        </flux:button>
    </flux:modal.trigger>

    <flux:modal name="confirm-user-deletion" :show="$errors->isNotEmpty()" focusable class="max-w-lg w-full max-w-sm rounded-2xl border p-6 shadow-2xl border-slate-200 bg-white dark:border-white/[.08] dark:bg-[#111a2d]">
        <form wire:submit="deleteUser" class="space-y-6 ">
            <div>
                <flux:heading size="lg">{{ __('Are you sure you want to delete your account?') }}</flux:heading>

                <flux:subheading>
                    {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
                </flux:subheading>
            </div>

             <x-ui.password label="{{ __('Password') }}" wire:model="password" name="password" id="password" placeholder="Enter your password" error="password" />

            <div class="flex justify-end space-x-2">
                <flux:modal.close>
                    <flux:button class="btn btn-outline-secondary">{{ __('Cancel') }}</flux:button>
                </flux:modal.close>

                <flux:button class="btn btn-danger" type="submit">{{ __('Delete Account') }}</flux:button>
            </div>
        </form>
    </flux:modal> 
</section>
