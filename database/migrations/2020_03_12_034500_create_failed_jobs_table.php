<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFailedJobsTable extends Migration {

  public function up() {
    Schema::create('failed_jobs', function (Blueprint $table) {
      $table->id();
      $table->text('connection');
      $table->text('queue');
      $table->longText('payload');
      $table->longText('exception');
      $table->timestamp('failed_at')->useCurrent();
      
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
    Schema::dropIfExists('failed_jobs');
  }

}
