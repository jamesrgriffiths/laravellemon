<?php

use Illuminate\Database\Seeder;

class VariableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
      DB::table('variables')->insert(['key' => 'routes_protected', 'value' => "logout,login,register,password,ignition,,/"]);
      DB::table('variables')->insert(['key' => 'routes_public', 'value' => ""]);
      DB::table('variables')->insert(['key' => 'routes_logged_in', 'value' => "home"]);
      DB::table('variables')->insert(['key' => 'routes_admin', 'value' => "logs,users,user_types"]);
    }
}
