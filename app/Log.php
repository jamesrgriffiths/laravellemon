<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Log extends Model {
  use SoftDeletes;

  protected $dates = ['deleted_at'];

  // The attributes that are mass assignable.
  protected $fillable = [ 'user_id', 'type', 'ip_address', 'device', 'url', 'class', 'message', 'trace', 'run_time', 'created_by', 'updated_by', 'deleted_by' ];

  // Relationships
  public function user() {
    return $this->belongsTo('App\User');
  }

}
