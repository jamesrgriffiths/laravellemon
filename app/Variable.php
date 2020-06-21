<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Variable extends Model {
  use SoftDeletes;

  protected $dates = ['deleted_at'];

  // The attributes that are mass assignable.
  protected $fillable = [ 'organization_id', 'type', 'key', 'value', 'protected', 'created_by', 'updated_by', 'deleted_by' ];

  // Relationships
  public function organization() {
    return $this->belongsTo('App\Organization');
  }

}
