<?php

namespace App\Repositories\Facades;

use Illuminate\Support\Facades\Facade;

class LogFacade extends Facade {

  protected static function getFacadeAccessor() {
    return 'App\Repositories\Interfaces\LogRepositoryInterface';
  }

}
