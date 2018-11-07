<?php

namespace App\Http\Middleware;

use Closure;

class RequirePermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  int       $permission
     * @return mixed
     */
    public function handle($request, Closure $next ,int $permission)
    {
        abort_unless(auth()->user()->permission & $permission,403);

        return $next($request);
    }
}
