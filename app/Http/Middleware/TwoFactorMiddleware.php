<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class TwoFactorMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if (!$user) {
            return $next($request);
        }

        // 1. Enforced Setup Check
        if ($user->is_2fa_enforced && is_null($user->google2fa_secret)) {
            if (!$request->routeIs(['2fa.setup', '2fa.enable', 'logout'])) {
                return redirect()->route('2fa.setup')
                    ->with('status', 'Two-Factor Authentication is required for your account. Please set it up to continue.');
            }
        }

        // 2. 2FA Challenge Check
        if (!is_null($user->google2fa_secret)) {
            if (!$request->session()->get('2fa:verified')) {
                if (!$request->routeIs(['2fa.challenge', '2fa.verify_challenge', 'logout'])) {
                    return redirect()->route('2fa.challenge');
                }
            }
        }

        return $next($request);
    }
}
