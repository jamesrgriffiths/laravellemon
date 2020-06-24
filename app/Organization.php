<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Organization extends Model {
  use SoftDeletes;

  protected $dates = ['deleted_at'];

  // The attributes that are mass assignable.
  protected $fillable = [ 'name', 'slug', 'created_by', 'updated_by', 'deleted_by' ];

  // Relationships
  public function users() {
    return $this->hasMany('App\User');
  }

  public function variables() {
    return $this->hasMany('App\Variables');
  }

}
