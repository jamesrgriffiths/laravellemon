<?php

namespace App\Repositories\Facades;

use Illuminate\Support\Facades\Facade;

class UserFacade extends Facade {

  protected static function getFacadeAccessor() {
    return 'App\Repositories\Interfaces\UserRepositoryInterface';
  }

}
