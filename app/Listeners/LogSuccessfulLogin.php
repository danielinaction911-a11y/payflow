<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\LoginActivity;
use Illuminate\Auth\Events\Login;
use Jenssegers\Agent\Agent;
use Stevebauman\Location\Facades\Location;
use App\Models\User;

class LogSuccessfulLogin
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(Login $event): void
    {
        if (! $event->user instanceof User) {
            return;
        }
        $agent = new Agent();
        $agent->setUserAgent(request()->userAgent());

        $ip = request()->ip();
        $device = trim(($agent->platform() ?: 'Unknown OS') . ' · ' . ($agent->browser() ?: 'Unknown browser'));
        $deviceType = $agent->isTablet() ? 'tablet' : ($agent->isMobile() ? 'mobile' : 'desktop');

        // Same user + same IP → update the existing row (bumps logged_in_at,
        // refreshes device string in case the UA changed) instead of piling
        // up a new row every time they log in from the same place.
        LoginActivity::updateOrCreate(
            [
                'user_id' => $event->user->id,
                'ip_address' => $ip,
            ],
            [
                'device' => $device,
                'device_type' => $deviceType,
                'location' => $this->resolveLocation($ip),
                'successful' => true,
                'logged_in_at' => now(),
            ]
        );

        $event->user->update(['last_login_at' => now()]);
    }

    /**
     * Resolve a human-readable "City, Region, Country" string from an IP.
     * Returns null for local/private IPs (which can't be geolocated) or if
     * the lookup fails/times out — never lets a geo-IP hiccup break login.
     */
    protected function resolveLocation(string $ip): ?string
    {
        if (in_array($ip, ['127.0.0.1', '::1']) || filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) === false) {
            return null;
        }

        try {
            $position = Location::get($ip);

            if (! $position) {
                return null;
            }

            return collect([$position->cityName, $position->regionName, $position->countryName])
                ->filter()
                ->implode(', ') ?: null;
        } catch (\Throwable $e) {
            report($e);
            return null;
        }
    }
}
