<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail {

  use Notifiable;
  use SoftDeletes;

  // The attributes that are mass assignable.
  protected $fillable = [ 'name', 'email', 'user_type_id', 'is_admin', 'is_active', 'last_logged_in', 'password', 'created_by', 'updated_by', 'deleted_by' ];

  // The attributes that should be hidden for arrays.
  protected $hidden = [ 'password', 'remember_token' ];

  // The attributes that should be cast to native types.
  protected $casts = [ 'last_logged_in' => 'datetime', 'email_verified_at' => 'datetime' ];

  // Relationships
  public function logs() {
    return $this->hasMany('App\Log');
  }

  public function userType() {
    return $this->belongsTo('App\UserType');
  }

}
