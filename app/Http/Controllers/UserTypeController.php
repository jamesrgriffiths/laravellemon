<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserTypeController extends Controller {

  // Show the User Types
  public function index() {
    return view('user_types');
  }

}
