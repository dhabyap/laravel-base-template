<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

// Role guard. Usage in route: ->middleware('role:admin')
// Means: only users with admin role may pass.
class EnsureRole
{
    public function handle(Request $request, Closure $next, string $role)
    {
        // 1. Not logged in? Reject. (Usually auth:sanctum already ran first.)
        $user = $request->user();
        if (! $user) {
            return response()->json(['ok' => false, 'message' => 'Unauthenticated'], 401);
        }

        // 2. Role mismatch? Reject 403.
        if ($user->role !== $role) {
            return response()->json(['ok' => false, 'message' => 'Forbidden'], 403);
        }

        // 3. Pass, continue to controller.
        return $next($request);
    }
}
