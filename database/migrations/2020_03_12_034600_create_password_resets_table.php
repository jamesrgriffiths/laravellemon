<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePasswordResetsTable extends Migration {

  public function up() {
    Schema::create('password_resets', function (Blueprint $table) {
      $table->string('email')->index();
      $table->string('token');
      $table->timestamp('created_at')->nullable();
      
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
    Schema::dropIfExists('password_resets');
  }

}
