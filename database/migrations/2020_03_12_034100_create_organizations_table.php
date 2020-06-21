<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrganizationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('organizations', function (Blueprint $table) {
          $table->id();

          $table->string('name')->nullable();
          $table->string('slug')->nullable();

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

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('organizations');
    }
}
