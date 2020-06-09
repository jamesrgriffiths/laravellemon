<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Repositories\Facades\UserFacade;

class UserController extends Controller {

  // Show the Users
  public function index(Request $request) {
    $users = UserFacade::getAllPaginated('name','desc',25);

    $data = [
      'users' => $users
    ];

    return $request->vue ? $data : view('layouts.app')->with($data);
  }

}
