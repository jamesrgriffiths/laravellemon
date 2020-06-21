<?php

namespace App\Repositories;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;

class BackendServiceProvider extends ServiceProvider {

  public function register() {

    // Bindings
    $this->app->bind('App\Repositories\Interfaces\LogRepositoryInterface','App\Repositories\Classes\LogRepository');
    $this->app->bind('App\Repositories\Interfaces\OrganizationRepositoryInterface','App\Repositories\Classes\OrganizationRepository');
    $this->app->bind('App\Repositories\Interfaces\UserRepositoryInterface','App\Repositories\Classes\UserRepository');
    $this->app->bind('App\Repositories\Interfaces\UserTypeRepositoryInterface','App\Repositories\Classes\UserTypeRepository');
    $this->app->bind('App\Repositories\Interfaces\VariableRepositoryInterface','App\Repositories\Classes\VariableRepository');

    // Facade aliases
    $this->app->booting(function() {
      $loader = AliasLoader::getInstance();

      $loader->alias('LogFacade','App\Repositories\Facades\LogFacade');
      $loader->alias('OrganizationFacade','App\Repositories\Facades\OrganizationFacade');
      $loader->alias('UserFacade','App\Repositories\Facades\UserFacade');
      $loader->alias('UserTypeFacade','App\Repositories\Facades\UserTypeFacade');
      $loader->alias('VariableFacade','App\Repositories\Facades\VariableFacade');
    });

  }

}
