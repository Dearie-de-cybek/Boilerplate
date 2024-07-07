<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
{
    $token = $request->header('Authorization');
    if (!$token || !auth()->authenticate($token)) {
        return response()->json(['error' => 'Unauthorized'], 401);
    }
    $user = auth()->user();
    if (!$user->is_admin) {
        return response()->json(['error' => 'Forbidden'], 403);
    }
    return $next($request);
}
}
