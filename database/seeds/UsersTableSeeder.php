<?php

use Illuminate\Database\Seeder;
use App\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
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
