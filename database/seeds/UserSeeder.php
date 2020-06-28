<?php

use App\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'admin',
            'email' => 'admin@poltektedc.ac.id',
            'username' => 'admin',
            'password' => bcrypt('admin'),
            'role' => 'administrator'
        ]);
    }
}
