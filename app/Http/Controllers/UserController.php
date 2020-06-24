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

    // Special Data
    $current_user = UserFacade::find(auth()->user()->id);
    $user_types = UserTypeFacade::getAll('name');

    // Filters
    $filters = [];
    $active_options = [['id'=>-1,'name'=>'All'],['id'=>1,'name'=>'Active'],['id'=>0,'name'=>'Inactive']];
    $verified_options = [['id'=>-1,'name'=>'All'],['id'=>1,'name'=>'Verified'],['id'=>0,'name'=>'Unverified']];
    $user_type_options = [['id'=>0,'name'=>'All User Types']];
    foreach($user_types as $user_type) { array_push($user_type_options,['id'=>$user_type->id,'name'=>$user_type->name]); }

    // Filter Conditions
    $filter_conditions = [];
    $filter_active = $request->has('filter_active') ? $request->filter_active : -1;
    $filter_verified = $request->has('filter_verified') ? $request->filter_verified : -1;
    $filter_user_type = $request->has('filter_user_type') ? $request->filter_user_type : 0;
    if($filter_active != -1) { $filter_conditions['is_active'] = $filter_active; }
    if($filter_verified != -1) {
      if($filter_verified == 1) {
        $filter_conditions['email_verified_at::notnull'] = '';
      } else {
        $filter_conditions['email_verified_at::null'] = '';
      }
    }
    if($filter_user_type != 0) { $filter_conditions['user_type_id'] = $filter_user_type; }

    // Filter the organization if the client is under one
    if(session('organization')) {
      $filter_conditions['organization_id'] = session('organization')->id;
    }

    // Set filter options
    array_push($filters,['prop'=>'filter_active','all_values'=>$active_options]);
    array_push($filters,['prop'=>'filter_verified','all_values'=>$verified_options]);
    array_push($filters,['prop'=>'filter_user_type','all_values'=>$user_type_options]);

    $users = $filter_conditions ? UserFacade::wherePaginated($filter_conditions,'name','ASC',25) : UserFacade::getAllPaginated('name','ASC',25);
    foreach($users as $user) {
      $user->userType = $user->userType; // This enables access to the relationship with vue
      $user->organization = $user->organization; // This enables access to the relationship with vue
      $user->created_at_formatted = $user->created_at ? $user->created_at->format('F d, Y') : '';
      $user->last_logged_in_formatted = $user->last_logged_in ? $user->last_logged_in->format('F d, Y g:i A') : '';
    }

    $data = [
      "page" => $page,
      "pages" => Helper::getVisiblePages($users->lastPage(),$page,3),
      "filters" => $filters,
      "filter_active" => $filter_active,
      "filter_verified" => $filter_verified,
      "filter_user_type" => $filter_user_type,
      'users' => $users,
      'current_user' => $current_user,
      'user_types' => $user_types,
      'active_organization' => session('organization'),
      'organizations' => OrganizationFacade::getAll('name')
    ];

    return $request->vue ? $data : view('layouts.app')->with($data);
  }

  public function store(Request $request) {

  }

  public function update(Request $request, $id) {
    $user = UserFacade::find($id);
    $name = $request->has('name') ? $request->name : $user->name;
    $email = $request->has('email') ? $request->email : $user->email;
    $organization_id = $request->has('organization_id') ? $request->organization_id == 0 ? NULL : $request->organization_id : $user->organization_id;
    $user_type_id = $request->has('user_type_id') ? $request->user_type_id == 0 ? NULL : $request->user_type_id : $user->user_type_id;
    $is_admin = $request->has('is_admin') ? $request->is_admin : $user->is_admin;
    $is_active = $request->has('is_active') ? $request->is_active : $user->is_active;

    // Manual email verification
    if(isset($request->email_verified_at) && $user->email_verified_at xor $request->email_verified_at) {
      $user->email_verified_at = $request->email_verified_at ? Carbon::now()->toDateTimeString() : null;
    }

    return UserFacade::update($id,[
      'name' => $name,
      'email' => $email,
      'organization_id' => $organization_id,
      'user_type_id' => $user_type_id,
      'is_admin' => $is_admin,
      'is_active' => $is_active,
      'email_verified_at' => $user->email_verified_at
    ]);
  }

  public function destroy(Request $request, $id) {
    return UserFacade::delete($id);
  }

}
