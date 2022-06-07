<?php

namespace Database\Seeders;

use Carbon\Carbon;
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
        DB::Table('users')->insertOrIgnore(
            [
                [
            //                    'id'         => '1',
                    'username'   =>'debs',
                    'name'       => 'Debora Cavalcante',
                    'email'      => 'debs@mail.com',
                    'password'   => Hash::make('12345678'),
                    'idade'      => 18,
                    'role_id'    => '1',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ],
                [
            //                    'id'         => '2',
                    'username'   => 'admin',
                    'name'       => 'Administrador',
                    'email'      => 'admin@mail.com',
                    'password'   => Hash::make('admin'),
                    'idade'      => 18,
                    'role_id'    => '2',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ],
            ]
        );
    }
}
