<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Helpers\Helper;
use App\Repositories\Facades\UserFacade;

class UserController extends Controller {

  // Show the Users
  public function index(Request $request) {
    $page = $request->page ? $request->page : 1;
    $users = UserFacade::getAllPaginated('name','desc',25);

    $data = [
      "page" => $page,
      "pages" => Helper::getVisiblePages($users->lastPage(),$page,3),
      'users' => $users
    ];

    return $request->vue ? $data : view('layouts.app')->with($data);
  }

}
