<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SharedLogoutController extends Controller
{
    /**
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function handleSharedLogout(Request $request)
    {

        if (Auth::guard('web')->check()) {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        } else {
            Log::info('ecommerce-app: No active local session found by SharedLogoutController, or already logged out.');
        }

        // The final redirect after the entire SLO chain.
        $finalRedirectUrl = $request->query('redirect_after_ecommerce_logout');

        // Basic validation for redirect URL - only allow local redirects
        if ($finalRedirectUrl && filter_var($finalRedirectUrl, FILTER_VALIDATE_URL) && str_starts_with($finalRedirectUrl, url('/'))) {
             return redirect()->to($finalRedirectUrl);
        }
        
        Log::info('ecommerce-app: Redirecting to default login page.');
        return redirect()->route('login'); // Default to ecommerce-app's own login page
    }
}