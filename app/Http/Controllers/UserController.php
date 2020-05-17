<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Repositories\Facades\UserFacade;

class UserController extends Controller {

  // Show the Users
  public function index() {
    $users = UserFacade::getAllPaginated('name','desc',25);

    $data = [
      'users' => $users
    ];

    return view('users')->with($data);
  }

}
