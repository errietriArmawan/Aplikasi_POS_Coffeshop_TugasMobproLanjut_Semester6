<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Cek user sudah login dan role-nya sesuai
        if (!$request->user() || !in_array($request->user()->role, $roles)) {
            return response()->json(['message' => 'Unauthorized or Forbidden'], 403);
        }

        return $next($request);
    }
}
