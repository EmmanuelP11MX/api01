<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        if ($request->is('api/*')) {
            abort(
                response()->json([
                    'status' => 401,
                    'message' => 'Unauthorized'
                ], 400)
            );
        }
        if (!$request->expectsJson()) {
            route('login');
        } else {
            abort(
                response()->json([
                    'status' => 401,
                    'message' => 'Unauthorized'
                ], 400)
            );
        }
    }
}
