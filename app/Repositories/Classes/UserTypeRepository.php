<?php

namespace App\Repositories\Classes;

use App\Repositories\Repository;
use App\Repositories\Interfaces\UserTypeRepositoryInterface;

class UserTypeRepository extends Repository implements UserTypeRepositoryInterface {

  // Needed for base Repository class usaage
  protected $modelClassName = 'App\UserType';

}
