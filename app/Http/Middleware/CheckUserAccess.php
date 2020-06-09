<?php

namespace App\Http\Middleware;

use Closure;

use App\Repositories\Facades\UserTypeFacade;
use App\Repositories\Facades\VariableFacade;

class CheckUserAccess {
    /**
     * Handle an incoming request.
     * See also Authenticate middleware
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
      // The current top level route name
      $current_route = explode('.',$request->route()->getName())[0];

      // The routes that are available publicly
      $accessible_routes = array_merge(VariableFacade::getValueArrayByKey('routes_protected'),VariableFacade::getValueArrayByKey('routes_public'));

      // The empty route should always be accessible.
      array_push($accessible_routes,'');

      // Routes for logged in users
      if($request->user() != null) {

        // Logged In Routes
        $accessible_routes = array_merge($accessible_routes,VariableFacade::getValueArrayByKey('routes_logged_in'));

        // Admin Routes - an admin has access to all routes
        if($request->user()->is_admin) {
          $accessible_routes = array_merge($accessible_routes,VariableFacade::getValueArrayByKey('routes_admin'),VariableFacade::getSystemRoutes());
        }

        // User Routes
        if($request->user()->userType) {
          $accessible_routes = array_merge($accessible_routes,explode(',',$request->user()->userType->route_access));
        }

      }

      // Direct the client if they have access
      if(in_array($current_route,$accessible_routes)) {
        return $next($request);
      } else if($request->user() != null) {
        return redirect('home');
      } else {
        return redirect('login');
      }
    }
}
