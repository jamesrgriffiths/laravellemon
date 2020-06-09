<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserTypesTable extends Migration {

  public function up() {
    Schema::create('user_types', function (Blueprint $table) {
      $table->id();

      $table->string('name')->nullable();
      $table->longText('route_access');

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
    Schema::dropIfExists('user_types');
  }

}
