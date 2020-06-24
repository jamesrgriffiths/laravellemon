<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserType extends Model {
  use SoftDeletes;

  protected $dates = ['deleted_at'];

  // The attributes that are mass assignable.
  protected $fillable = [ 'organization_id', 'name', 'route_access', 'created_by', 'updated_by', 'deleted_by' ];

  // Relationships
  public function users() {
    return $this->hasMany('App\User');
  }

}
