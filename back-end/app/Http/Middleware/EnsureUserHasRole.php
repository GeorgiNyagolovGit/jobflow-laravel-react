<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        abort_unless(
            $request->user()?->roles()->where('name', $role)->exists(),
            403,
        );

        return $next($request);
    }
}
