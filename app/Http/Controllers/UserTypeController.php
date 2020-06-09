<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Helpers\Helper;
use App\Repositories\Facades\UserTypeFacade;
use App\Repositories\Facades\VariableFacade;

class UserTypeController extends Controller {

  // Show the User Types
  public function index(Request $request) {
    $page = $request->page ? $request->page : 1;

    $public_routes = VariableFacade::getValueArrayByKey('routes_public');
    $logged_in_routes = VariableFacade::getValueArrayByKey('routes_logged_in');
    $admin_routes = VariableFacade::getValueArrayByKey('routes_admin');
    $special_routes = array_merge($public_routes,$logged_in_routes,$admin_routes);

    $unassigned_routes = VariableFacade::getRoutesUnassigned();

    $user_routes = [];
    $user_types = UserTypeFacade::getAllPaginated('name','ASC',25);
    foreach($user_types as $user_type) {
      $user_type->user_count = $user_type->users->count();
      $user_type->route_access = Helper::cleanArray(explode(',',$user_type->route_access));
      $user_type->routes = $this->createAssignableRouteArray($user_type->route_access,VariableFacade::getRoutesAssignable(),$special_routes);
      $user_routes = array_merge($user_routes,$user_type->route_access);
    }

    $data = [
      "page" => $page,
      "pages" => Helper::getVisiblePages($user_types->lastPage(),$page,3),
      'user_types' => $user_types,
      'public_routes' => $this->createAssignableRouteArray(VariableFacade::getValueArrayByKey('routes_public'),$unassigned_routes,array_merge($user_routes,$logged_in_routes,$admin_routes)),
      'logged_in_routes' => $this->createAssignableRouteArray(VariableFacade::getValueArrayByKey('routes_logged_in'),$unassigned_routes,array_merge($user_routes,$public_routes,$admin_routes)),
      'admin_routes' => $this->createAssignableRouteArray(VariableFacade::getValueArrayByKey('routes_admin'),$unassigned_routes,array_merge($user_routes,$public_routes,$logged_in_routes)),
    ];

    return $request->vue ? $data : view('layouts.app')->with($data);
  }

  public function store(Request $request) {
    $user_type = UserTypeFacade::store(['name' => '', 'route_access' => '']);
    return UserTypeFacade::update($user_type->id,['name' => 'USER TYPE #'.$user_type->id]);
  }

  public function update(Request $request, $id) {
    $route_access = $request->route_access ? $request->route_access : '';
    if($id == 0) {
      $variable = VariableFacade::whereFirst(['key' => 'routes_'.$request->type]);
      VariableFacade::update($variable->id,['value' => $route_access]);
    } else {
      $name = $request->name ? $request->name : 'USER TYPE #'.$id;
      return UserTypeFacade::update($id,['name' => $name, 'route_access' => $route_access]);
    }
  }

  public function destroy(Request $request, $id) {
    return UserTypeFacade::delete($id);
  }

  // PRIVATE FUNCTIONS

  private function createAssignableRouteArray($assigned,$not_assigned,$unavailable=[]) {
    $routes = [];
    $compare_routes = [];
    foreach($assigned as $a) {
      $routes[] = (object)['active' => 1, 'name' => $a];
      $compare_routes[] = $a;
    }
    foreach($not_assigned as $na) {
      if(!in_array($na,$compare_routes)) {
        $routes[] = (object)['active' => 0, 'name' => $na];
        $compare_routes[] = $na;
      }
    }
    foreach($unavailable as $un) {
      if(!in_array($un,$compare_routes)) {
        $routes[] = (object)['active' => -1, 'name' => $un];
        $compare_routes[] = $un;
      }
    }
    usort($routes, function($a, $b) { return strcmp($a->name, $b->name); });
    return $routes;
  }

}
