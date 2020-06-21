<?php

namespace App\Repositories\Classes;

use App\Repositories\Repository;
use App\Repositories\Interfaces\OrganizationRepositoryInterface;

class OrganizationRepository extends Repository implements OrganizationRepositoryInterface {

  // Needed for base Repository class usaage
  protected $modelClassName = 'App\Organization';

}
