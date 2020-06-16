<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Variable extends Model {
  use SoftDeletes;

  protected $dates = ['deleted_at'];

  // The attributes that are mass assignable.
  protected $fillable = [ 'type', 'key', 'value', 'protected', 'created_by', 'updated_by', 'deleted_by' ];

}
