<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Helpers\Helper;

class TestController extends Controller {

  public function index(Request $request) {
    dd($request);
  }

}
