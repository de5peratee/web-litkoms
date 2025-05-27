<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EditorPanelMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->guest() || auth()->user()->role !== 'editor') {
            return back();
        }

        return $next($request);
    }
}
