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

  // Store a new organization, will return 0 for duplicate name or slug.
  public function store(Request $request) {
    $name = $request->has('name') ? $request->name : '';
    $slug = $request->has('slug') ? $request->slug : '';
    $organization_by_name = OrganizationFacade::whereFirst(['name' => $name]);
    $organization_by_slug = OrganizationFacade::whereFirst(['slug' => $slug]);
    if($organization_by_name || $organization_by_slug) { return 0; }
    return OrganizationFacade::store(['name' => $name, 'slug' => $slug]);
  }

  // Update the organization, will return 0 for duplicate name or slug (on a different record).
  public function update(Request $request, $id) {
    $organization = OrganizationFacade::find($id);
    $name = $request->has('name') ? $request->name : $organization->name;
    $slug = $request->has('slug') ? $request->slug : $organization->slug;
    $organization_by_name = OrganizationFacade::whereFirst(['id::!=' => $organization->id, 'name' => $name]);
    $organization_by_slug = OrganizationFacade::whereFirst(['id::!=' => $organization->id, 'slug' => $slug]);
    if($organization_by_name || $organization_by_slug) { return 0; }
    return OrganizationFacade::update($id,['name' => $name, 'slug' => $slug]);
  }

  public function destroy(Request $request, $id) {
    return OrganizationFacade::delete($id);
  }

}
