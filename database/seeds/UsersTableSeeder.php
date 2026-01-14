<?php

use Illuminate\Database\Seeder;
use App\User;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name' => 'Ibnu Ahkam',
            'email' => 'ibnuahkam08@gmail.com',
            'role' => 'Admin',
            'password' => Hash::make('123456'),
        ]);
    }
}
