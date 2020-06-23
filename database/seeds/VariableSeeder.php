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
      DB::table('variables')->insert([
        'type' => 'Route Access',
        'key' => 'routes_protected',
        'value' => "logout,login,register,verification,password,ignition,,/",
        'protected' => 1,
        'info' => "Global Route Access Variables are used in the middleware
          CheckUserAccess and SetSessionRoutes. routes_protected prevents these routes
          from being assigned in a way that will break the functionality of the
          system."]);
      DB::table('variables')->insert([
        'type' => 'Route Access',
        'key' => 'routes_public',
        'value' => "",
        'protected' => 1,
        'info' => "Global Route Access Variables are used in the middleware
          CheckUserAccess and SetSessionRoutes. routes_public has routes available for all
          clients (logged in or not)."]);
      DB::table('variables')->insert([
        'type' => 'Route Access',
        'key' => 'routes_logged_in',
        'value' => "home",
        'protected' => 1,
        'info' => "Global Route Access Variables are used in the middleware
          CheckUserAccess and SetSessionRoutes. routes_logged_in has routes available for
          all logged in clients."]);
      DB::table('variables')->insert([
        'type' => 'Route Access',
        'key' => 'routes_admin',
        'value' => "logs,organizations,users,user_types,variables",
        'protected' => 1,
        'info' => "Global Route Access Variables are used in the middleware
          CheckUserAccess and SetSessionRoutes. routes_admin has all routes that are only
          accessible to admin users (set by the is_admin flag)"]);
    }
}
