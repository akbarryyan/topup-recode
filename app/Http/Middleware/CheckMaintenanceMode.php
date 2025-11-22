<?php

namespace App\Http\Middleware;

use App\Models\MaintenanceSetting;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckMaintenanceMode
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($this->shouldBypass($request)) {
            return $next($request);
        }

        $setting = MaintenanceSetting::current();

        if ($setting->is_active) {
            return response()->view('maintenance', ['setting' => $setting], 503);
        }

        return $next($request);
    }

    protected function shouldBypass(Request $request): bool
    {
        if ($request->is('admin') || $request->is('admin/*')) {
            return true;
        }

        if ($request->is('auth') || $request->is('auth/*')) {
            return true;
        }

        if ($request->is('storage/*')) {
            return true;
        }

        return false;
    }
}
