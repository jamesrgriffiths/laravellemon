<?php

namespace App\Http\Middleware;

use Closure;

class CheckUserAccess {
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {

      // The current top level route name
      $current_route = explode('.',$request->route()->getName())[0];

      // The array of routes the given user has access to - this is built up.
      $allowed_routes = ['','/','home','logout','login','register','password'];

      // Routes that can only be accessed by administrators
      $admin_routes = ['logs','users','user_types'];

      // If a user is logged in, check if they have appropriate access for the given requested route.
      if($request->user() != null) {

        // If the user is an admin allow those routes
        if($request->user()->is_admin) {
          $allowed_routes = array_merge($allowed_routes,$admin_routes);
        }

        // If the user has a user type allow the associated routes
        if($request->user()->userType) {
          $allowed_routes = array_merge($allowed_routes,explode(',',$request->user()->userType->route_access));
        }

        // Direct the user if they have access
        if(in_array($current_route,$allowed_routes)) {
          return $next($request);
        } else {
          return redirect('home');
        }
      }

      // A logged out user is already restricted.
      return $next($request);
    }
}
