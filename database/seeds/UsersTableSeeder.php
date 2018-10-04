<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::table('users')->insert([
            [
                'id' => 1,
                'name' => 'Abdul Alim',
                'role_id' => 1,
                'email' => 'alimifypro@gmail.com',
                'userextra_id' => 1,
                'password' => Hash::make('0987654321')

            ],
            [
                'id' => 2,
                'name' => 'Zisan Abdullah',
                'email' => 'caller@mail.com',
                'role_id' => 2,
                'userextra_id' => 2,
                'password' => Hash::make('calleraccount')
            ]
        ]);

        DB::table('userextras')->insert([
            [
                'id' => 1,
                'phone' => '01767436576'

            ],
            [
                'id' => 2,
                'phone' => '298428934792'
            ]
        ]);

    }
}
