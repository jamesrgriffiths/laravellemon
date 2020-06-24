<?php

namespace App\Http\Controllers;

use App\Repositories\Facades\UserFacade;
use Auth;
use Illuminate\Http\Request;

class HomeController extends Controller {

  // Show the home page
  public function index(Request $request) {
    $data = [
      "user" => Auth::user()
    ];
    return $request->vue ? $data : view('layouts.app')->with($data);
  }

  public function update(Request $request, $user_id) {
    $user = UserFacade::find($user_id);
    if($request->method == 'resend_verification_email') {
      $user->sendEmailVerificationNotification();
    }
  }

}
