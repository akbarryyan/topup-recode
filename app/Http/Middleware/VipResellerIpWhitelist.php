<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class VipResellerIpWhitelist
{
    /**
     * VIP Reseller whitelisted IPs
     */
    protected array $whitelistedIps = [
        '178.248.73.218',
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $clientIp = $request->ip();

        // Allow in local/development environment
        if (app()->environment('local', 'development')) {
            return $next($request);
        }

        // Check if IP is whitelisted
        if (!in_array($clientIp, $this->whitelistedIps)) {
            Log::warning('VIP Reseller Webhook - IP not whitelisted', [
                'ip' => $clientIp,
                'whitelisted' => $this->whitelistedIps,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'IP not authorized',
            ], 403);
        }

        return $next($request);
    }
}
