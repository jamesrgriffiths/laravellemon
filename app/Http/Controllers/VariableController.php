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

    // Filter organization if there is one
    $filter_conditions = [];
    if(session('organization')) { $filter_conditions['organization_id'] = session('organization')->id; }

    $variables = $filter_conditions ? VariableFacade::where($filter_conditions,'type,key','ASC') : VariableFacade::getAll('type,key','ASC');
    foreach($variables as $variable) {
      $variable->organization = $variable->organization; // This enables the relationship in vue
      if($variable->type == 'Route Access') {
        $variable->routes = VariableFacade::getAssignableRoutesArray($variable->value,'route_access');
      }
    }

    $data = [
      'variables' => $variables,
      'active_organization' => session('organization'),
      'organizations' => OrganizationFacade::getAll('name')
    ];

    return $request->vue ? $data : view('layouts.app')->with($data);
  }

  // Store a new database variable
  public function store(Request $request) {

    // Return 0 if there is an existing variable.
    if( ($request->organization_id && VariableFacade::whereFirst(['organization_id' => $request->organization_id,'type' => $request->type, 'key' => $request->key]))
      || (!$request->organization_id && VariableFacade::whereFirst(['type' => $request->type, 'key' => $request->key])) ) {
        return 0;
      }

    // Return the new variable.
    return VariableFacade::store([
      'type' => $request->type ?: '',
      'key' => $request->key ?: '',
      'value' => $request->value ?: '',
      'info' => $request->info ?: '',
      'organization_id' => $request->organization_id ?: NULL
    ]);

  }

  // Update a given database variable
  public function update(Request $request, $id) {
    $variable = VariableFacade::find($id);

    $organization_id = isset($request->organization_id) ? $request->organization_id : $variable->organization_id;
    $organization_id = $organization_id == 0 ? NULL : $organization_id;

    return VariableFacade::update($id,[
      'value' => isset($request->value) ? $request->value : $variable->value,
      'organization_id' => $organization_id
    ]);
  }

  // Deletes a given database variable
  public function destroy(Request $request, $id) {
    return VariableFacade::delete($id);
  }

}
