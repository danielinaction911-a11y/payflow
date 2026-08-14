<?php

namespace App\Livewire\Legal;

use App\Models\Policy;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;

#[Layout('components.layouts.app')]
class LegalCenter extends Component
{
    #[Url]
    public ?string $slug = null;

    public function mount(?string $slug = null): void
    {
        if ($slug) {
            $this->slug = $slug;
        }

        if (! $this->slug) {
            $this->slug = $this->policies->first()?->slug;
        }
    }

    #[Computed]
    public function policies()
    {
        return Policy::where('is_active', true)
            ->orderBy('sort_order')
            ->get();
    }

    #[Computed]
    public function activePolicy()
    {
        return $this->policies->firstWhere('slug', $this->slug)
            ?? $this->policies->first();
    }

    public function selectPolicy(string $slug): void
    {
        $this->slug = $slug;
    }

    public function render()
    {
        return view('livewire.legal.legal-center')->layout('components.layouts.app', [
            'title' => 'Legal Center',
        ]);
    }
}