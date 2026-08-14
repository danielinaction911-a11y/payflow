<?php

namespace App\Enums;

enum SupportTicketStatus: string
{
    case Open = 'open';
    case Pending = 'in_progress';
    case Resolved = 'resolved';
    case Closed = 'closed';

    /**
     * Bootstrap contextual color name (bg-primary, bg-warning, etc.),
     * useful anywhere you're using stock Bootstrap badge/button classes.
     */
    public function badgeColor(): string
    {
        return match ($this) {
            self::Open => 'primary',
            self::Pending => 'warning',
            self::Resolved => 'success',
            self::Closed => 'secondary',
        };
    }

    /**
     * CSS custom property reference, for use with the app's design-token
     * based styling (color-mix, inline style bindings, etc.).
     */
    public function color(): string
    {
        return match ($this) {
            self::Open => 'var(--info)',
            self::Pending => 'var(--warning)',
            self::Resolved => 'var(--success)',
            self::Closed => 'var(--muted)',
        };
    }

    /**
     * Human-readable label. Needed because the case name (Pending) and
     * the underlying value ('in_progress') don't match — this is the
     * single source of truth for what the user actually sees.
     */
    public function label(): string
    {
        return match ($this) {
            self::Open => 'Open',
            self::Pending => 'In Progress',
            self::Resolved => 'Resolved',
            self::Closed => 'Closed',
        };
    }
}