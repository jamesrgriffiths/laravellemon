<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOrganizationToUsersTable extends Migration {

  public function up() {
    Schema::table('users', function (Blueprint $table) {
      $table->bigInteger('organization_id')->unsigned()->nullable()->after('id');
      $table->foreign('organization_id')->references('id')->on('organizations');
    });
  }

  public function down() {
    Schema::table('users', function (Blueprint $table) {
      $table->dropForeign(['organization_id']);
      $table->dropColumn('organization_id');

    });
  }

}
