<?php

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'full_name' => '',
            'email' => 'user1@m.ru',
            'password' => Hash::make(123)
        ]);
        User::create([
            'full_name' => '',
            'email' => 'user2@m.ru',
            'password' => Hash::make(123)
        ]);
        User::create([
            'full_name' => '',
            'email' => 'user3@m.ru',
            'password' => Hash::make(123)
        ]);
        User::create([
            'full_name' => '',
            'email' => 'user4@m.ru',
            'password' => Hash::make(123)
        ]);
    }
}
