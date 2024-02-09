<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //

        DB::table('users')->insert(
            [
                'firstname' => 'arab',
                'lastname' => 'medicine',
                'email' => 'arab-medicine@arab.com',
                'password' => Hash::make('arabmedicine@com'),
                'role' => 'admin'
            ]
        );
    }
}
