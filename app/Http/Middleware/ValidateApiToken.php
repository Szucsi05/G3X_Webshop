<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ValidateApiToken
{
    
    public function handle(Request $request, Closure $next): Response
    {
        
        
        if ($request->hasHeader('X-API-TOKEN')) {
            $token = $request->header('X-API-TOKEN');
            $request->headers->set('Authorization', 'Bearer ' . $token);
        }

        return $next($request);
    }
}
