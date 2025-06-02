<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthMiddleware
{
    /**
     * Обработка входящего запроса.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->guest()) {
            return $this->handleRedirect($request, route('auth'));
        }

        $allowedRoutes = [
            'verification.notice',
            'verification.send',
            'verification.verify',
            'logout',
        ];

        if (!auth()->user()->hasVerifiedEmail() && !in_array($request->route()?->getName(), $allowedRoutes)) {
            return $this->handleRedirect($request, route('verification.notice'));
        }

        return $next($request);
    }

    protected function handleRedirect(Request $request, string $url): Response
    {
        if ($request->expectsJson()) {
            return response()->json(['redirect_url' => $url], 401);
        }

        return redirect($url);
    }

}
