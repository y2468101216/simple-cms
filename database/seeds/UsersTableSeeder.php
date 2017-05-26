<?php

use Illuminate\Database\Seeder;
use App\Model\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = new User;
        $user->name = str_random(10);
        $user->email = 'test1@gmail.com';
        $user->password = bcrypt('secret');
        $user->save();
    }
}
