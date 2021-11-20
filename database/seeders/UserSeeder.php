<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
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
        //admin
        User::create([
            'nama_depan' => 'Admin',
            'nama_belakang' => 'Adel',
            'is_admin' => 1,
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password')
        ]);
        //customer
        User::create([
            'nama_depan' => 'James',
            'nama_belakang' => 'Franco',
            'is_admin' => 0,
            'email' => 'james@gmail.com',
            'password' => Hash::make('password')
        ]);
    }
}
