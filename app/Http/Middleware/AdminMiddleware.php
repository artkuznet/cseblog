<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    /**
     * @param Request $request
     * @param Closure $next
     */
    public function handle(Request $request, Closure $next): void
    {
        if ($request->input('password') === env('ADMIN_PASSWORD')) {
            return $next($request);
        }
        abort(403);
    }
}
