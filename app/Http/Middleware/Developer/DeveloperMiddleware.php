<?php

namespace App\Http\Middleware\Developer;

use Closure;

use App\Helpers\EnvHelper;

class DeveloperMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (! EnvHelper::isDev()) {
            abort(404);
        }

        return $next($request);
    }
}
