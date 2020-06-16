<?php

namespace App\Repositories\Classes;

use App\Helpers\Helper;
use App\Repositories\Repository;
use App\Repositories\Facades\UserTypeFacade;
use App\Repositories\Interfaces\VariableRepositoryInterface;
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
      $this::getValueArrayByTypeAndKey('Route Access','routes_protected'),
      $this::getValueArrayByTypeAndKey('Route Access','routes_public'),
      $this::getValueArrayByTypeAndKey('Route Access','routes_logged_in'),
      $this::getValueArrayByTypeAndKey('Route Access','routes_admin') );

    // Route access can not assign routes that are already assigned to users
    if($type == 'route_access') {
      $user_types = UserTypeFacade::getAll();
      $user_assigned_routes = [];
      foreach($user_types as $user_type) {
        $user_assigned_routes = array_merge($user_assigned_routes,explode(",",$user_type->route_access));
      }
      $unassignable_routes = array_merge($unassignable_routes,$user_assigned_routes);
    }

    foreach($this::getSystemRoutes() as $route) {
      if(in_array($route,$active_routes)) {
        $routes[] = (object)['active' => 1, 'name' => $route];
      } elseif(!in_array($route,$unassignable_routes)) {
        $routes[] = (object)['active' => 0, 'name' => $route];
      }
    }
    return $routes;
  }

  // Returns an array of routes on this system by name.
  public function getSystemRoutes() {
    $routes = [];
    foreach(Route::getRoutes() as $route) {
      $routes[] = explode('.',$route->getName())[0];
    }
    return Helper::cleanArray($routes);
  }

  // Return the value found by type and key in an array format, assuming comma
  // seperation and removing blank values.
  public function getValueArrayByTypeAndKey($type,$key) {
    $object = $this::whereFirst(['type' => $type, 'key' => $key]);
    if($object) {
      $value = $object->value;
      return Helper::cleanArray(explode(",",$value));
    }
    return [];
  }

}
