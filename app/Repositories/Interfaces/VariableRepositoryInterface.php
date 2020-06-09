<?php

namespace App\Repositories\Interfaces;

use App\Repositories\RepositoryInterface;

interface VariableRepositoryInterface extends RepositoryInterface {

  public function getRoutesAssignable();

  public function getRoutesAssigned();

  public function getRoutesUnassigned();

  public function getRoutesUser();

  public function getSystemRoutes();

  public function getValueArrayByKey($key);

}
