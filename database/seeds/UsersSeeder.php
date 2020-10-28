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
            'password' => Hash::make(123),
            'remember_token' => '',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
        User::create([
            'full_name' => '',
            'email' => 'user2@m.ru',
            'password' => Hash::make(123),
            'remember_token' => '',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
        User::create([
            'full_name' => '',
            'email' => 'user3@m.ru',
            'password' => Hash::make(123),
            'remember_token' => '',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
        User::create([
            'full_name' => '',
            'email' => 'user4@m.ru',
            'password' => Hash::make(123),
            'remember_token' => '',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }
}
