<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller {

  // Show the home page
  public function index(Request $request) {
    $data = [
      //
    ];
    return $request->vue ? $data : view('layouts.app')->with($data);
  }

}
