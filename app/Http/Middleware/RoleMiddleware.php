<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $role): Response
    {
        $user = Auth::guard('api')->user();

        \Log::info('User role in middleware: ' . ($user->role->name ?? 'null'));

        if (!$user || !$user->role || strtolower($user->role->name ?? '') !== strtolower($role)) {
            return response()->json([
                'message' => 'Unauthorized in ' . $role,
                'status_code' => 403,
                'data' => null
            ], 403);
        }

        return $next($request);
    }
}
