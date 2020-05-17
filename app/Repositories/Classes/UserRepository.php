<?php

namespace App\Repositories\Classes;

use App\Repositories\Repository;
use App\Repositories\Interfaces\UserRepositoryInterface;

class UserRepository extends Repository implements UserRepositoryInterface {

  // Needed for base Repository class usaage
  protected $modelClassName = 'App\User';

}
