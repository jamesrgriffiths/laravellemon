<?php

namespace App\Repositories\Interfaces;

use App\Repositories\RepositoryInterface;

interface LogRepositoryInterface extends RepositoryInterface {

  // Log an exception
  public function logException($request, $exception);

  // Log a request
  public function logRequest($request);

}
