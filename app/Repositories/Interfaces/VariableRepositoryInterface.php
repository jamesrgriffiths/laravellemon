<?php

namespace App\Repositories\Interfaces;

use App\Repositories\RepositoryInterface;

interface VariableRepositoryInterface extends RepositoryInterface {

  public function getAssignableRoutesArray($active_routes_string,$type);

  public function getSystemRoutes();

  public function getValueArrayByTypeAndKey($type,$key);

}
