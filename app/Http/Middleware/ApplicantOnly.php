<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApplicantOnly
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check() || !in_array(Auth::user()->role, ['applicant', 'admin', 'staff', 'superadmin'], true)) {
            abort(403, 'Access restricted to applicants and administrators.');
        }
        return $next($request);
    }
}
