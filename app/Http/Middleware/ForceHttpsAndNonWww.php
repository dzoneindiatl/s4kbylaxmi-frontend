<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ForceHttpsAndNonWww
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        // if (!$request->secure() || str_starts_with($request->getHost(), 'www.')) {
        //     return redirect()->secure(
        //         ltrim(str_replace('www.', '', $request->getRequestUri()), '/')
        //     );
        // }

        // return $next($request);
    }
}
