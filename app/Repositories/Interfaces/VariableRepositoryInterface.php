<?php

namespace App\Repositories\Interfaces;

use App\Repositories\RepositoryInterface;

interface VariableRepositoryInterface extends RepositoryInterface {

  public function getAssignableRoutesArray($active_routes_string,$type);

  public function getLoggedInUserRoutes($user_id);

  public function getSystemRoutes();

  public function getAllSystemRoutes();

  public function getTypes();

  public function getValueArray($organization_id,$type,$key);

}
