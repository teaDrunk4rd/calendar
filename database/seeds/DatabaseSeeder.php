<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call(UsersSeeder::class);
        $this->call(EventTypesSeeder::class);
        $this->call(EventsSeeder::class);
    }
}
