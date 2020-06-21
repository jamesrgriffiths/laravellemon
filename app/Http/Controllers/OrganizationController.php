<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Repositories\Facades\OrganizationFacade;
use Illuminate\Http\Request;

class OrganizationController extends Controller {

  // Show the Organizations
  public function index(Request $request) {
    $page = $request->page ? $request->page : 1;

    // Filter organization if there is one
    $filter_conditions = [];
    if(session('organization')) { $filter_conditions['id'] = session('organization')->id; }

    $organizations = $filter_conditions ? OrganizationFacade::wherePaginated($filter_conditions,'name','ASC',25) : OrganizationFacade::getAllPaginated('name','ASC',25);

    foreach($organizations as $organization) {
      $organization->user_count = $organization->users->count();
    }

    $data = [
      "page" => $page,
      "pages" => Helper::getVisiblePages($organizations->lastPage(),$page,3),
      'active_organization' => session('organization'),
      "organizations" => $organizations
    ];

    return $request->vue ? $data : view('layouts.app')->with($data);
  }

  public function store(Request $request) {
    return OrganizationFacade::store([
      'name' => $request->name ?: '',
      'slug' => $request->slug ?: ''
    ]);
  }

  public function update(Request $request, $id) {
    $organization = OrganizationFacade::find($id);
    return OrganizationFacade::update($id,[
      'name' => isset($request->name) ? $request->name : $organization->name,
      'slug' => isset($request->slug) ? $request->slug : $organization->slug
    ]);
  }

  public function destroy(Request $request, $id) {
    return OrganizationFacade::delete($id);
  }

}
