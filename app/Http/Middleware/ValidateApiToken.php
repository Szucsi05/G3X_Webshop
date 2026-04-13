<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ValidateApiToken
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // If X-API-TOKEN header is present, convert it to Authorization: Bearer header
        // This allows the Sanctum middleware to validate it
        if ($request->hasHeader('X-API-TOKEN')) {
            $token = $request->header('X-API-TOKEN');
            $request->headers->set('Authorization', 'Bearer ' . $token);
        }

        return $next($request);
    }
}
