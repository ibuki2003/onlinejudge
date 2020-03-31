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
     * @param  string    $permission
     * @return mixed
     */
    public function handle($request, Closure $next ,string $permission)
    {
        if(!auth()->check()) return redirect()->route('login');
        abort_unless(auth()->user()->has_permission($permission),403);
        return $next($request);
    }
}
