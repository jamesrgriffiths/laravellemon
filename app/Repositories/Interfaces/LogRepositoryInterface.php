<?php

namespace App\Repositories\Interfaces;

use App\Repositories\RepositoryInterface;

interface LogRepositoryInterface extends RepositoryInterface {

  // Get distinct classes
  public function getClasses();

  // Get distinct IPs
  public function getIps();

  // Get distinct types
  public function getTypes();

  // Log an exception
  public function logException($request, $exception);

  // Log a request
  public function logRequest($request);

}
