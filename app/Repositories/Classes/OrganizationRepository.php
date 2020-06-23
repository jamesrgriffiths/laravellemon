<?php

namespace App\Repositories\Classes;

use App\Repositories\Facades\VariableFacade;
use App\Repositories\Repository;
use App\Repositories\Interfaces\OrganizationRepositoryInterface;

class OrganizationRepository extends Repository implements OrganizationRepositoryInterface {

  // Needed for base Repository class usaage
  protected $modelClassName = 'App\Organization';

  public function delete($id) { 
    $variables = VariableFacade::where(['organization_id' => $id]);
    foreach($variables as $variable) {
      VariableFacade::delete($variable->id);
    }
    parent::delete($id);
  }

}
