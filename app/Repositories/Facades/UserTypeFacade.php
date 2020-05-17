<?php

namespace App\Repositories\Facades;

use Illuminate\Support\Facades\Facade;

class UserTypeFacade extends Facade {

  protected static function getFacadeAccessor() {
    return 'App\Repositories\Interfaces\UserTypeRepositoryInterface';
  }

}
