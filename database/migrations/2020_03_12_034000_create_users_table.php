<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration {

  public function up() {
    Schema::create('users', function (Blueprint $table) {
      $table->id();

      $table->string('name');
      $table->string('email')->unique();

      $table->boolean('is_admin')->default(0);
      $table->boolean('is_active')->default(1);

      $table->timestamp('last_logged_in')->nullable();
      $table->timestamp('email_verified_at')->nullable();
      $table->string('password');
      $table->rememberToken();

      $table->timestamps();
      $table->softDeletes();

      $table->bigInteger('created_by')->unsigned()->nullable();
      $table->foreign('created_by')->references('id')->on('users');
      $table->bigInteger('updated_by')->unsigned()->nullable();
      $table->foreign('updated_by')->references('id')->on('users');
      $table->bigInteger('deleted_by')->unsigned()->nullable();
      $table->foreign('deleted_by')->references('id')->on('users');
    });
  }

  public function down() {
    Schema::dropIfExists('users');
  }

}
