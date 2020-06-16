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
    $user_types = UserTypeFacade::getAllPaginated('name','ASC',25);
    foreach($user_types as $user_type) {
      $user_type->user_count = $user_type->users->count();
      $user_type->routes = VariableFacade::getAssignableRoutesArray($user_type->route_access,'user_type');
    }
    $data = [
      "page" => $page,
      "pages" => Helper::getVisiblePages($user_types->lastPage(),$page,3),
      'user_types' => $user_types
    ];
    return $request->vue ? $data : view('layouts.app')->with($data);
  }

  public function store(Request $request) {
    return UserTypeFacade::store(['name' => $request->name ? $request->name : '', 'route_access' => '']);
  }

  public function update(Request $request, $id) {
    $user_type = UserTypeFacade::find($id);
    return UserTypeFacade::update($id,[
      'name' => $request->name ? $request->name : $user_type->name,
      'route_access' => $request->route_access ? $request->route_access : $user_type->route_access
    ]);
  }

  public function destroy(Request $request, $id) {
    return UserTypeFacade::delete($id);
  }

}
