<?php

use App\User;
use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = new User;
        $admin->fill([
            'name'=>'admin',
            'email'=>'example@example.com',
            'password'=>'$2y$10$uVyogulVya6/DoK05tIxmuqwf0QUN4tuWiHI8HBtqZ3vPCbLomocy',
        ]);
        $admin->save();


    }
}