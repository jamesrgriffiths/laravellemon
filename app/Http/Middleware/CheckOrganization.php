<?php

namespace App\Http\Middleware;

use App\Repositories\Facades\OrganizationFacade;
use Auth;
use Closure;

class CheckOrganization
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
      $subdomain = '';
      $organization = '';

      // Get the url sub domain - the numbers here may need to be adjusted based
      // on your root domain. This assumes a domain.ext type configuration.
      $host_array = explode('.', $request->getHost());
      if(count($host_array) > 2) {
        $subdomain = $host_array[count($host_array)-3];
        $subdomain = $subdomain == 'www' ? '' : $subdomain;
      }

      // Determine if the sub domain exists.
      $subdomain_exists = OrganizationFacade::whereFirst(['slug' => $subdomain]) ? true : false;

      // If the subdomain exists or this is the root domain.
      // ---
      // - A logged in user with an organization can only access their subdomain
      // (or the root as an alias to theirs.), if they try to access another
      // subdomain they will be logged out and brought to that subdomain's login page.
      // - A logged out user or a logged in user with global access (no
      // organization) can access any legitimate subdomain.
      // ---
      // If the subdomain does not exist this will throw a 404.
      if($subdomain_exists || $subdomain == '') {
        if(Auth::user() && Auth::user()->organization_id) {
          $organization = OrganizationFacade::find(Auth::user()->organization_id);
          if($subdomain != '' && $subdomain != $organization->slug) { Auth::logout(); return redirect('login'); }
        } elseif($subdomain != '') {
          $organization = OrganizationFacade::whereFirst(['slug' => $subdomain]);
        }
      } else {
        abort(404);
      }

      // Set the organization in the session if it exists.
      $request->session()->forget('organization');
      if($organization != '') { $request->session()->put('organization', $organization); }
      return $next($request);
    }
}
