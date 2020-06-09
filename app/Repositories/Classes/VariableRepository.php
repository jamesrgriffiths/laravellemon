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

  // Get all routes that are not in one of the 4 route variables
  public function getRoutesAssignable() {
    $routes = [];
    $protected_routes = $this::getValueArrayByKey('routes_protected');
    $public_routes = $this::getValueArrayByKey('routes_public');
    $logged_in_routes = $this::getValueArrayByKey('routes_logged_in');
    $admin_routes = $this::getValueArrayByKey('routes_admin');
    $special_routes = array_merge($protected_routes,$public_routes,$logged_in_routes,$admin_routes);
    foreach($this::getSystemRoutes() as $route) {
      if (!in_array($route,$special_routes) && !in_array($route,$routes)) {
        $routes[] = $route;
      }
    }
    return Helper::cleanArray($routes);
  }

  // Get all routes that have been assigned either to one of the 4 route
  // variables or to at least one user type.
  public function getRoutesAssigned() {
    $protected_routes = $this::getValueArrayByKey('routes_protected');
    $public_routes = $this::getValueArrayByKey('routes_public');
    $logged_in_routes = $this::getValueArrayByKey('routes_logged_in');
    $admin_routes = $this::getValueArrayByKey('routes_admin');
    $user_routes = $this::getRoutesUser();
    $assigned_routes = array_merge($protected_routes,$public_routes,$logged_in_routes,$admin_routes,$user_routes);
    return Helper::cleanArray($assigned_routes);
  }

  // Get all routes that have not been assigned to either one of the 4 route
  // variables or to at least one user type.
  public function getRoutesUnassigned() {
    $assigned_routes = $this::getRoutesAssigned();
    $unassigned_routes = [];
    foreach($this::getSystemRoutes() as $route) {
      if (!in_array($route,$assigned_routes) && !in_array($route,$unassigned_routes)) {
        $unassigned_routes[] = $route;
      }
    }
    return Helper::cleanArray($unassigned_routes);
  }

  // Get all routes assigned to user types.
  public function getRoutesUser() {
    $routes = [];
    $user_types = UserTypeFacade::getAll();
    foreach($user_types as $user_type) {
      $routes = array_merge($routes,explode(",",$user_type->route_access));
    }
    return Helper::cleanArray($routes);
  }

  // Returns an array of routes on this system by name.
  public function getSystemRoutes() {
    $routes = [];
    foreach(Route::getRoutes() as $route) {
      $routes[] = explode('.',$route->getName())[0];
    }
    return Helper::cleanArray($routes);
  }

  // Return the value found by key in an array format, assuming comma seperation
  // and removing blank values.
  public function getValueArrayByKey($key) {
    $object = $this::whereFirst(['key' => $key]);
    if($object) {
      $value = $object->value;
      return Helper::cleanArray(explode(",",$value));
    }
    return [];
  }

}
