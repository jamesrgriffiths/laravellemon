<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Repositories\Facades\UserFacade;
use App\Repositories\Facades\UserTypeFacade;
use Illuminate\Http\Request;

class UserController extends Controller {

  // Show the Users
  public function index(Request $request) {
    $page = $request->page ? $request->page : 1;
    $users = UserFacade::getAllPaginated('name','desc',25);
    $current_user = UserFacade::find(auth()->user()->id);

    foreach($users as $user) {
      $user->userType = $user->userType; // This enables access to the relationship with vue
      $user->created_at_formatted = $user->created_at ? $user->created_at->format('F d, Y') : '';
      $user->last_logged_in_formatted = $user->last_logged_in ? $user->last_logged_in->format('F d, Y g:i A') : '';
    }

    $data = [
      "page" => $page,
      "pages" => Helper::getVisiblePages($users->lastPage(),$page,3),
      'users' => $users,
      'current_user' => $current_user,
      'user_types' => UserTypeFacade::getAll('name')
    ];

    return $request->vue ? $data : view('layouts.app')->with($data);
  }

  public function store(Request $request) {
    // $user_type = UserTypeFacade::store(['name' => '', 'route_access' => '']);
    // return UserTypeFacade::update($user_type->id,['name' => 'USER TYPE #'.$user_type->id]);
  }

  public function update(Request $request, $id) {
    $user = UserFacade::find($id);
    $user_type_id = isset($request->user_type_id) ? $request->user_type_id : $user->user_type_id;
    $user_type_id = $user_type_id == 0 ? NULL : $user_type_id;
    $updated_user = UserFacade::update($id,[
      'name' => isset($request->name) ? $request->name : $user->name,
      'email' => isset($request->email) ? $request->email : $user->email,
      'user_type_id' => $user_type_id,
      'is_admin' => isset($request->is_admin) ? $request->is_admin : $user->is_admin,
      'is_active' => isset($request->is_active) ? $request->is_active : $user->is_active
    ]);
    $updated_user->userType = $updated_user->userType;
    return $updated_user;
  }

  public function destroy(Request $request, $id) {
    return UserFacade::delete($id);
  }

}
