<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Response;

class RedirectIfAuthenticated
{
    /**
     * @param  array<int, string|null>  $guards
     */
    public function handle(Request $request, Closure $next, ...$guards): Response|RedirectResponse
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $destination = '/';

                if ($request->is('admin') || $request->is('admin/*')) {
                    $destination = Auth::user()?->role === 'admin'
                        ? route('admin.dashboard')
                        : '/';
                }

                return redirect()->intended($destination);
            }
        }

        return $next($request);
    }
}
