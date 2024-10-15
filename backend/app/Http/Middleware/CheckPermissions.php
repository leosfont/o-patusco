<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Route;

class CheckPermissions
{
    public function handle($request, Closure $next)
    {
        $routeName = Route::currentRouteName();

        if ($routeName && !$request->user()->can($routeName)) {
            abort(403, 'Você não tem permissão para acessar esta rota.');
        }

        return $next($request);
    }
}
