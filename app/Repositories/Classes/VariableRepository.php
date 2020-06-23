<?php

namespace App\Repositories\Classes;

use App\Helpers\Helper;
use App\Repositories\Repository;
use App\Repositories\Facades\UserFacade;
use App\Repositories\Facades\UserTypeFacade;
use App\Repositories\Interfaces\VariableRepositoryInterface;
use App\Variable;
use Config;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class VariableRepository extends Repository implements VariableRepositoryInterface {

  // Needed for base Repository class usaage
  protected $modelClassName = 'App\Variable';

  // Create an array of routes assignable and already assigned, ordered with an active key.
  // $active_route_string - The comma seperated string of routes already assigned.
  // $type - The type of route to assign, either user_type or route_access.
  public function getAssignableRoutesArray($active_routes_string,$type) {
    $routes = [];
    $active_routes = explode(',',$active_routes_string);
    $unassignable_routes = array_merge(
      $this::getValueArray(NULL,'Route Access','routes_protected'),
      $this::getValueArray(NULL,'Route Access','routes_public'),
      $this::getValueArray(NULL,'Route Access','routes_logged_in'),
      $this::getValueArray(NULL,'Route Access','routes_admin') );

    // Route access can not assign routes that are already assigned to users
    if($type == 'route_access') {
      $user_types = UserTypeFacade::getAll();
      $user_assigned_routes = [];
      foreach($user_types as $user_type) {
        $user_assigned_routes = array_merge($user_assigned_routes,explode(",",$user_type->route_access));
      }
      $unassignable_routes = array_merge($unassignable_routes,$user_assigned_routes);
    }

    foreach($this::getAllSystemRoutes() as $route) {
      if(in_array($route,$active_routes)) {
        $routes[] = (object)['active' => 1, 'name' => $route];
      } elseif(!in_array($route,$unassignable_routes)) {
        $routes[] = (object)['active' => 0, 'name' => $route];
      }
    }
    return $routes;
  }

  public function getLoggedInUserRoutes($user_id) {
    $user = UserFacade::find($user_id);

    // Logged In Routes
    $accessible_routes = $this::getValueArray(NULL,'Route Access','routes_logged_in');

    // The email must be verified before accessing user type specific pages
    if($user->email_verified_at) {

      // Admin Routes
      if($user->is_admin) {
        $accessible_routes = array_merge($accessible_routes,$this::getValueArray(NULL,'Route Access','routes_admin'),$this::getSystemRoutes());
      }

      // User Routes
      if($user->userType) {
        $accessible_routes = array_merge($accessible_routes,explode(',',$user->userType->route_access));
      }

    }

    return Helper::cleanArray($accessible_routes);
  }

  // Returns an array of routes on this system by name. - Does not include protected routes.
  public function getSystemRoutes() {
    $routes = [];
    $protected_routes = $this::getValueArray(NULL,'Route Access','routes_protected');
    foreach($this::getAllSystemRoutes() as $route) {
      if(!in_array($route,$protected_routes)) {
        $routes[] = $route;
      }
    }
    return Helper::cleanArray($routes);
  }

  // Returns an array of routes on this system by name. - Includes protected routes.
  public function getAllSystemRoutes() {
    $routes = [];
    foreach(Route::getRoutes() as $route) {
      $routes[] = explode('.',$route->getName())[0];
    }
    return Helper::cleanArray($routes);
  }

  // Returns an array of distinct types found in the variables table.
  public function getTypes() {
    return Variable::select('type')->orderBy('type','ASC')->distinct()->get();
  }

  // Return the value found by organization, type, and key in an array format.
  // $organization_id REQUIRED:
  //    NULL will look for only null (global) organization variables.
  //    0 will look for all organization variables
  // $type REQUIRED
  // $key REQUIRED
  public function getValueArray($organization_id,$type,$key) {
    $object = NULL;
    if($organization_id) {
      $object = $this::whereFirst(['organization_id' => $organization_id, 'type' => $type, 'key' => $key]);
    } elseif($organization_id == 0) {
      $object = $this::whereFirst(['type' => $type, 'key' => $key]);
    } else {
      $object = $this::whereFirst(['organization_id::null' => '', 'type' => $type, 'key' => $key]);
    }

    if($object) {
      $value = $object->value;
      return Helper::cleanArray(explode(",",$value));
    }
    return [];
  }

}
