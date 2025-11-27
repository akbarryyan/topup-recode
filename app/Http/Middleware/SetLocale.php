<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Get locale from URL segment
        $locale = $request->segment(1);
        
        // Available locales
        $availableLocales = ['en', 'id'];
        
        // Paths that should NOT use locale prefix
        $excludedPaths = [
            'admin',           // Admin panel
            'payment',         // Payment callbacks
            'locale',          // Locale switching
            'invoices',        // Invoice redirect
            'api',             // API endpoints
        ];
        
        // Check if current path should be excluded from locale handling
        $firstSegment = $request->segment(1);
        if (in_array($firstSegment, $excludedPaths)) {
            // Don't apply locale for these paths
            return $next($request);
        }
        
        // If locale is in URL and is valid
        if (in_array($locale, $availableLocales)) {
            App::setLocale($locale);
            Session::put('locale', $locale);
        } else {
            // No locale in URL - redirect to locale-prefixed URL
            $sessionLocale = Session::get('locale', 'id'); // Default to Indonesian
            
            // Redirect to localized URL
            $path = $request->path();
            $query = $request->getQueryString();
            $redirectUrl = '/' . $sessionLocale . ($path !== '/' ? '/' . $path : '');
            
            if ($query) {
                $redirectUrl .= '?' . $query;
            }
            
            return redirect($redirectUrl);
        }
        
        return $next($request);
    }
}
