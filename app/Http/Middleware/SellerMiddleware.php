<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SellerMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth('web')->check()) {
            return redirect()->route('seller.login');
        }

        if (!auth('web')->user()->isSeller()) {
            abort(403, 'Unauthorized. Hanya akun Seller yang dapat mengakses halaman ini.');
        }

        return $next($request);
    }
}
