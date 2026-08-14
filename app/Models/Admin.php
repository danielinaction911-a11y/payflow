<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Enums\AdminStatus;

class Admin extends Authenticatable implements FilamentUser
{
    use Notifiable;

    protected $fillable = ['name', 'email', 'password', 'status'];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'last_login_at' => 'datetime',
            'status' => AdminStatus::class,
        ];
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->status === AdminStatus::Active;
    }

    public function getFilamentName(): string
    {
        return $this->name;
    }

    protected static function booted(): void
    {
        static::deleting(function (Admin $admin) {
            if (auth('admin')->check() && auth('admin')->id() === $admin->id) {
                return false;
            }

            return true;
        });
    }
}
