<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Repositories\Facades\OrganizationFacade;
use App\Repositories\Facades\UserFacade;
use App\Repositories\Facades\UserTypeFacade;
use Carbon\Carbon;
use Illuminate\Http\Request;

class UserController extends Controller {

  // Show the Users
  public function index(Request $request) {
    $page = $request->page ? $request->page : 1;

    // filters
    $filter_active = isset($request->filter_active) ? $request->filter_active : -1;
    $filter_verified = isset($request->filter_verified) ? $request->filter_verified : -1;
    $filter_user_type = isset($request->filter_user_type) ? $request->filter_user_type : 0;
    $filter_conditions = [];
    if($filter_active != -1) { $filter_conditions['is_active'] = $filter_active; }
    if($filter_verified != -1) {
      if($filter_verified == 1) {
        $filter_conditions['email_verified_at::notnull'] = '';
      } else {
        $filter_conditions['email_verified_at::null'] = '';
      }
    }
    if($filter_user_type != 0) { $filter_conditions['user_type_id'] = $filter_user_type; }

    // Filter organization if there is one
    if(session('organization')) { $filter_conditions['organization_id'] = session('organization')->id; }

    $users = $filter_conditions ? UserFacade::wherePaginated($filter_conditions,'name','ASC',25) : UserFacade::getAllPaginated('name','ASC',25);
    $current_user = UserFacade::find(auth()->user()->id);

    foreach($users as $user) {
      $user->userType = $user->userType; // This enables access to the relationship with vue
      $user->organization = $user->organization; // This enables access to the relationship with vue
      $user->created_at_formatted = $user->created_at ? $user->created_at->format('F d, Y') : '';
      $user->last_logged_in_formatted = $user->last_logged_in ? $user->last_logged_in->format('F d, Y g:i A') : '';
    }

    $data = [
      "page" => $page,
      "pages" => Helper::getVisiblePages($users->lastPage(),$page,3),
      "filter_active" => $filter_active,
      'users' => $users,
      'current_user' => $current_user,
      'user_types' => UserTypeFacade::getAll('name'),
      'active_organization' => session('organization'),
      'organizations' => OrganizationFacade::getAll('name')
    ];

    return $request->vue ? $data : view('layouts.app')->with($data);
  }

  public function store(Request $request) {

  }

  public function update(Request $request, $id) {
    $user = UserFacade::find($id);

    $organization_id = isset($request->organization_id) ? $request->organization_id : $user->organization_id;
    $organization_id = $organization_id == 0 ? NULL : $organization_id;

    $user_type_id = isset($request->user_type_id) ? $request->user_type_id : $user->user_type_id;
    $user_type_id = $user_type_id == 0 ? NULL : $user_type_id;

    // Manual email verification
    if(isset($request->email_verified_at) && $user->email_verified_at xor $request->email_verified_at) {
      $user->email_verified_at = $request->email_verified_at ? Carbon::now()->toDateTimeString() : null;
    }

    $updated_user = UserFacade::update($id,[
      'name' => isset($request->name) ? $request->name : $user->name,
      'email' => isset($request->email) ? $request->email : $user->email,
      'organization_id' => $organization_id,
      'user_type_id' => $user_type_id,
      'is_admin' => isset($request->is_admin) ? $request->is_admin : $user->is_admin,
      'is_active' => isset($request->is_active) ? $request->is_active : $user->is_active,
      'email_verified_at' => $user->email_verified_at
    ]);
    $updated_user->userType = $updated_user->userType;
    return $updated_user;
  }

  public function destroy(Request $request, $id) {
    return UserFacade::delete($id);
  }

}
