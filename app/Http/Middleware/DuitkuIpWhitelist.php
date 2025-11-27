<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class DuitkuIpWhitelist
{
    /**
     * Duitku whitelisted IPs for callback
     * Source: docs/payment-gateway/create-transactions/duitku/callback-redirect.md
     */
    protected $whitelistedIps = [
        // Production IPs
        '182.23.85.8',
        '182.23.85.9',
        '182.23.85.10',
        '182.23.85.13',
        '182.23.85.14',
        '103.177.101.184',
        '103.177.101.185',
        '103.177.101.186',
        '103.177.101.189',
        '103.177.101.190',
        
        // Sandbox IPs
        '182.23.85.11',
        '182.23.85.12',
        '103.177.101.187',
        '103.177.101.188',
        
        // Allow local testing
        '127.0.0.1',
        '::1',
        'localhost',
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Skip IP validation in local environment for testing
        if (app()->environment(['local', 'testing'])) {
            Log::info('Duitku Callback - Local Environment, IP check skipped', [
                'ip' => $request->ip(),
            ]);
            return $next($request);
        }

        $clientIp = $request->ip();

        // Check if IP is whitelisted
        if (!in_array($clientIp, $this->whitelistedIps)) {
            Log::warning('Duitku Callback - Unauthorized IP', [
                'ip' => $clientIp,
                'url' => $request->fullUrl(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Unauthorized IP address',
            ], 403);
        }

        Log::info('Duitku Callback - IP Validated', [
            'ip' => $clientIp,
        ]);

        return $next($request);
    }
}
