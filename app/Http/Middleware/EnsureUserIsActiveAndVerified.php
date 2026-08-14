<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsActiveAndVerified
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if (! $user) {
            return $next($request);
        }

        // ===== 1. Account status check =====
        if ($user->status !== 'active') {
            $message = match ($user->status) {
                'suspended' => 'Your account has been suspended. Please contact support for assistance.',
                'banned' => 'Your account has been banned. Please contact support if you believe this is a mistake.',
                default => 'Your account is not active. Please contact support.',
            };

            Auth::logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->withErrors(['email' => $message]);
        }

        // ===== 2. Email verification check =====
        if (
            setting('require_email_verification', false)
            && ! $user->hasVerifiedEmail()
            && ! $request->routeIs('verification.*')
            && ! $request->routeIs('logout')
        ) {
            return redirect()->route('verification.notice');
        }

        return $next($request);
    }
}