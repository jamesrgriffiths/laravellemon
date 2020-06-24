<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run() {
      // This loads in constant variables that are needed for the production environment.
      $this->call(VariableSeeder::class);
    }
}
