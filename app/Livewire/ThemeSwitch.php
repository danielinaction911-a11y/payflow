<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ThemeSwitch extends Component
{
    public string $class = '';

    public function toggleTheme(): void
    {
        if (! Auth::check()) {
            return;
        }

        $user = Auth::user();
        $theme = $user->default_theme === 'dark' ? 'light' : 'dark';

        $user->update(['default_theme' => $theme]);
    }

    public function render()
    {
        return view('livewire.theme-switch');
    }
}
