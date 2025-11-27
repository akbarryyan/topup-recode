<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class LocaleController extends Controller
{
    /**
     * Switch the application locale
     *
     * @param  string  $locale
     * @return \Illuminate\Http\RedirectResponse
     */
    public function switch($locale)
    {
        // Available locales
        $availableLocales = ['en', 'id'];
        
        if (!in_array($locale, $availableLocales)) {
            abort(404);
        }
        
        // Store locale in session
        Session::put('locale', $locale);
        
        // Get the previous URL (where user came from)
        $previousUrl = url()->previous();
        $parsedUrl = parse_url($previousUrl);
        $currentPath = isset($parsedUrl['path']) ? trim($parsedUrl['path'], '/') : '';
        
        // Remove existing locale prefix if present
        foreach ($availableLocales as $availableLocale) {
            if (str_starts_with($currentPath, $availableLocale . '/')) {
                $currentPath = substr($currentPath, strlen($availableLocale) + 1);
                break;
            } elseif ($currentPath === $availableLocale) {
                $currentPath = '';
                break;
            }
        }
        
        // Build new URL with locale prefix (both id and en use prefixes)
        $newPath = '/' . $locale . ($currentPath ? '/' . $currentPath : '');
        
        return redirect($newPath);
    }
}
