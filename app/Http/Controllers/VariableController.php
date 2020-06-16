<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Helpers\Helper;
use App\Repositories\Facades\VariableFacade;

class VariableController extends Controller {

  // Show the database variables
  public function index(Request $request) {

    // Other options
    if($request->option && $request->option == 'get_assignable_routes_array') {
      return VariableFacade::getAssignableRoutesArray($request->option_value,'route_access');
    }

    $variables = VariableFacade::getAll('type,key','asc');
    foreach($variables as $variable) {
      if($variable->type == 'Route Access') {
        $variable->routes = VariableFacade::getAssignableRoutesArray($variable->value,'route_access');
      }
    }

    $data = [
      'variables' => $variables
    ];

    return $request->vue ? $data : view('layouts.app')->with($data);
  }

  // Store a new database variable
  public function store(Request $request) {
    return VariableFacade::store([
      'type' => $request->type ? $request->type : '',
      'key' => $request->key ? $request->key : '',
      'value' => $request->value ? $request->value : ''
    ]);
  }

  // Update a given database variable
  public function update(Request $request, $id) {
    $variable = VariableFacade::find($id);
    return VariableFacade::update($id,['value' => $request->value ? $request->value : $variable.value]);
  }

  // Deletes a given database variable
  public function destroy(Request $request, $id) {
    return VariableFacade::delete($id);
  }

}
