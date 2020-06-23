<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Helpers\Helper;
use App\Repositories\Facades\OrganizationFacade;
use App\Repositories\Facades\VariableFacade;

class VariableController extends Controller {

  // Show the database variables
  public function index(Request $request) {

    // Other options
    if($request->option && $request->option == 'get_assignable_routes_array') {
      return VariableFacade::getAssignableRoutesArray($request->option_value,'route_access');
    }

    // Special Data
    $organizations = OrganizationFacade::getAll('name');
    $types = VariableFacade::getDistinctFields('type');

    // Filters
    $filters = [];
    $organization_options = [['id'=>0,'name'=>'All Organizations'],['id'=>'','name'=>'Global']];
    foreach($organizations as $organization) { array_push($organization_options,['id'=>$organization->id,'name'=>$organization->name]); }
    $type_options = [['id'=>'','name'=>'All Types']];
    foreach($types as $type) { array_push($type_options,['id'=>$type->type,'name'=>$type->type]); }

    // Filter Conditions
    $filter_conditions = [];
    $filter_organization = $request->has('filter_organization') ? $request->filter_organization : 0;
    $filter_type = $request->has('filter_type') ? $request->filter_type : '';
    if($filter_organization == '') {
      $filter_conditions['organization_id::null'] = '';
    } elseif($filter_organization) {
      $filter_conditions['organization_id'] = $filter_organization;
    }
    if($filter_type) {
      $filter_conditions['type'] = $filter_type;
    }

    // Filter the organization if the client is under one, then set the available filters accordingly
    if(session('organization')) {
      $filter_conditions['organization_id'] = session('organization')->id;
    } else {
      array_push($filters,['prop'=>'filter_organization','all_values'=>$organization_options]);
    }
    array_push($filters,['prop'=>'filter_type','all_values'=>$type_options]);

    // Special values for the variables
    $variables = $filter_conditions ? VariableFacade::where($filter_conditions,'type,key','ASC') : VariableFacade::getAll('type,key','ASC');
    foreach($variables as $variable) {
      $variable->organization_name = $variable->organization ? $variable->organization->name : 'Global';
      $variable->organization_id = $variable->organization_id ?: '';
      $variable->routes = $variable->type == 'Route Access' ? VariableFacade::getAssignableRoutesArray($variable->value,'route_access') : null;
    }

    $data = [
      'filters' => $filters,
      'filter_organization' => $filter_organization,
      'filter_type' => $filter_type,
      'variables' => $variables,
      'active_organization' => session('organization'),
      'organizations' => $organizations,
    ];

    return $request->vue ? $data : view('layouts.app')->with($data);
  }

  // Store the variable, will return 0 for duplicate organization, type, and key.
  public function store(Request $request) {
    $type = $request->has('type') ? $request->type : '';
    $key = $request->has('key') ? $request->key : '';
    $value = $request->has('value') ? $request->value : '';
    $info = $request->has('info') ? $request->info : '';
    $organization_id = $request->has('organization_id') ? $request->organization_id ?: NULL : NULL;
    if(VariableFacade::whereFirst(['organization_id' => $organization_id, 'type' => $type, 'key' => $key])) { return 0; }
    return VariableFacade::store(['type' => $type, 'key' => $key, 'value' => $value, 'info' => $info, 'organization_id' => $organization_id]);
  }

  // Update the variable, will return 0 for duplicate organization, type, and key
  public function update(Request $request, $id) {
    $variable = VariableFacade::find($id);
    $value = $request->has('value') ? $request->value : $variable->value;
    $organization_id = $request->has('organization_id') ? $request->organization_id == 0 ? NULL : $request->organization_id : $variable->organization_id;
    if(VariableFacade::whereFirst(['organization_id' => $organization_id, 'type' => $variable->type, 'key' => $variable->key, 'id::!=' => $variable->id])) { return 0; }
    return VariableFacade::update($id,['value' => $value, 'organization_id' => $organization_id]);
  }

  // Deletes a given database variable
  public function destroy(Request $request, $id) {
    return VariableFacade::delete($id);
  }

}
