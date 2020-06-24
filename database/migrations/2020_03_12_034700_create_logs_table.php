<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLogsTable extends Migration {

  public function up() {
    Schema::create('logs', function (Blueprint $table) {
      $table->id();

      $table->bigInteger('user_id')->unsigned()->nullable();
      $table->foreign('user_id')->references('id')->on('users');

      $table->enum('type',['error','request'])->nullable();
      $table->string('ip_address')->default('');
      $table->string('device')->default('');
      $table->string('url')->default('');
      $table->string('class')->default('');
      $table->longText('message');
      $table->longText('trace');
      $table->double('run_time',8,2)->nullable();

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
    Schema::dropIfExists('logs');
  }

}
