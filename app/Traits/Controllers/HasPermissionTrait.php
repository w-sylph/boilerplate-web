<?php

namespace App\Traits\Controllers;

use App\Helpers\AuthHelper;

trait HasPermissionTrait
{
    protected function getPermissions() {
        return [];
    }

    protected function checkPermissions() {
        $auth = new AuthHelper;
        $routes = $this->getPermissions();

        foreach ($routes as $route => $permissions) {
            $middleware = $this->middleware(function ($request, $next) use ($auth, $permissions) {
                if (! $auth->hasAnyPermission($permissions)) {
                    if ($request->ajax()) {
                        return response()->json([
                            'message' => 'Permission denied.',
                        ], 401);
                    }

                    return abort(401);
                }
                return $next($request);
            });

            if (is_string($route)) {
                $middleware->only(explode('|', $route));
            }
        }
    }
}