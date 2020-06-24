<?php

namespace App\Repositories\Facades;

use Illuminate\Support\Facades\Facade;

class VariableFacade extends Facade {

  protected static function getFacadeAccessor() {
    return 'App\Repositories\Interfaces\VariableRepositoryInterface';
  }

}
