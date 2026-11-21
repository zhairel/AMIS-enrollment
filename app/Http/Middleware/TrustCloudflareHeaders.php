<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class TrustCloudflareHeaders
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->headers->has('CF-Connecting-IP')) {
            $realIp = $request->header('CF-Connecting-IP');
            if (is_string($realIp) && trim($realIp) !== '' && filter_var(trim($realIp), FILTER_VALIDATE_IP)) {
                $request->server->set('REMOTE_ADDR', trim($realIp));
            }
        }

        return $next($request);
    }
}
