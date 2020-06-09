<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserTypesToUsersTable extends Migration {

  public function up() {
    Schema::table('users', function (Blueprint $table) {
      $table->bigInteger('user_type_id')->unsigned()->nullable()->after('email');
      $table->foreign('user_type_id')->references('id')->on('user_types');
    });
  }

  public function down() {
    Schema::table('users', function (Blueprint $table) {
      $table->dropForeign(['user_type_id']);
      $table->dropColumn('user_type_id');

    });
  }

}
