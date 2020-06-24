<?php

namespace App\Http\Middleware;

use Closure;

use Auth;
use App\Repositories\Facades\VariableFacade;

class SetSessionRoutes {
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
      session(['public_routes' => VariableFacade::getValueArray(NULL,'Route Access','routes_public')]);
      session(['user_routes' => Auth::user() ? VariableFacade::getLoggedInUserRoutes(Auth::user()->id) : []]);
      return $next($request);
    }
}
